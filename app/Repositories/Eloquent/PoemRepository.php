<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\UserRepository as User;
use App\Repositories\Eloquent\CategoryRepository as Category;
use Illuminate\Container\Container as App;

class PoemRepository extends Repository
{

    protected $user;
    protected $category;

    public function __construct(User $user, Category $category, App $app)
    {
        parent::__construct($app);
        $this->user = $user;
        $this->category = $category;
    }


    /**
     * 指定模型名称
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Http\Frontend\Models\Poem';
    }

    /**
     * 诗文列表
     *
     * @param $query
     * @return mixed
     */
    public function index($query = '')
    {
        // 获取分页数据
        if (!$query) {
            $paginate = $this->model
                ->orderBy('created_at', 'desc')->paginate(10)->toArray();
        } else {
            // 若有查询参数
            $paginate = $this->model
                ->where('title', 'like', "%$query%")
                ->paginate(10)
                ->toArray();
        }

        // 若数据为空
        if (!$paginate['total']) {
            return $paginate;
        }

        // 格式化诗文数据
        $poems = collect($paginate['data'])
            ->map(function ($item) {
                return $this->with(['user.profile.poems', 'tags', 'comments.user.profile', 'comments' => function ($query) {
                    $query->orderBy('comments.created_at', 'desc');
                }])->find($item['id']);
            });

        $paginate['data'] = $this->transformModels($poems)
            ->all();

        return $paginate;
    }

    /**
     * 获取热门诗文
     *
     * @return mixed
     */
    public function hotPoems()
    {
        $poems = $this->with(['user.profile.poems', 'tags', 'comments.user.profile', 'comments' => function ($query) {
            $query->orderBy('comments.created_at', 'desc');
        }])->get();

        // 热门诗文排序算法
        $sortAlgorithm = function ($poem) {
            // 点赞、分享、收藏、写赏析的总数
            $meta = $poem['likes_count'] + $poem['shares_count'] + $poem['favorites_count'] + $poem['appreciations_count'];
            // 诗文平均评分
            $rating = $this->getRatingsByModel($poem);
            return (log10($poem['pageviews_count']) * 4
                + $meta / 5
                + $poem['comments_count'] / 100)
            + $rating
            / ($poem['created_at']->timestamp / 3600);
        };

        $sortedPoems = $poems->sort(function ($a, $b) use ($sortAlgorithm) {
            $a = $sortAlgorithm($a);
            $b = $sortAlgorithm($b);

            if ($a == $b) {
                return 0;
            }
            return ($a > $b) ? -1 : 1;
        });
        $result = $this->transformModels($sortedPoems)->values();
        $hotPoems = $result->take(20)->all();
        $hotAuthors = $result
            ->pluck('user')
            ->unique('id')
            ->values()
            ->take(200)
            ->shuffle()
            ->take(5)
            ->all();
        return $this->respondWith(['poem' => $hotPoems, 'author' => $hotAuthors]);
    }

    /**
     * 存储诗文内容
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store($request)
    {
        // 创建诗文
        $userId = id() ?: id('web');
        $poem = $this->create([
            'user_id' => $userId,
            'category_id' => $request->category,
            'title' => $request->title,
            'body' => $request->body,
            'summary' => mb_substr($request->body, 0, 150, 'UTF-8')
        ]);
        if ($result = !!$poem) {
            // 作者作品总数 +1
            (new \App\Http\Frontend\Models\User())->find($userId)->increment('works_count');
            // 若诗文有定义标签
            if (($tags = $request->dynamicTags) && count($request->dynamicTags) <= 5) {
                // 标签不存在,则新建之
                $ids = collection($tags)->map(function ($tag) use ($poem) {
                    return (new \App\Http\Frontend\Models\Tag)->firstOrCreate(['name' => $tag])->id;
                });
                $poem->storeTags($ids);
            }
        }
        return $this->respondWith(['created' => $result, 'poem' => $poem]);
    }

    /**
     * 查找指定诗文
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // (查询耗费平均时间: 10ms)
        $poem = $this->with(['user.profile.poems' => function ($query) {
            $query->orderBy('poems.pageviews_count', 'desc');
        }, 'user.profile.appreciations' => function ($query) {
            $query->orderBy('appreciations.pageviews_count', 'desc');
        }, 'tags', 'appreciations.user.profile', 'comments.user.profile', 'comments' => function ($query) {
            $query->orderBy('comments.created_at', 'desc');
        }])->find($id);

        if ($poem) {
            $poem->increment('pageviews_count');
            return $this->transformModel($poem);
        } else {
            return $this->errorNotFound();
        }
    }

    /**
     * 搜索指定诗文
     *
     * @param $keyword
     * @return mixed
     */
    public function scout($keyword)
    {
        return $this->transformModels($this->with(['user.profile.poems', 'tags', 'comments.user.profile', 'comments' => function ($query) {
            $query->orderBy('comments.created_at', 'desc');
        }])
            ->where('title', 'like', "%$keyword%")
            ->get())
            ->sortByDesc('pageviews_count')
            ->values()
            ->all();
        /*return $this->model->search($search)->get()
            ->map(function ($item) {
                return $this->transformModel($item->with(['user.profile.poems', 'tags', 'comments.user.profile', 'comments' => function ($query) {
                    $query->orderBy('comments.created_at', 'desc');
                }])->find($item['id']));
            });*/
    }

    /**
     * 获取需要更新的诗文的数据
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $poem = $this->with(['user.profile.poems', 'tags'])->find($id);
        if (!$poem) {
            return $this->errorNotFound();
        } else {
           return $this->transformModel($poem);
        }
    }

    /**
     * 更新诗文
     *
     * @param $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function renew($request, $id)
    {
        $result = $this->update([
            'title' => $request->title,
            'body' => $request->body,
            'category_id' => $request->category,
            'summary' => mb_substr($request->body, 0, 150, 'UTF-8')
        ], $id);
        // 若更新成功
        if ($result) {
            // 若诗文有定义标签
            if (($tags = $request->dynamicTags) && count($request->dynamicTags) <= 5) {
                // 标签不存在,则新建之
                $ids = collection($tags)->map(function ($tag) {
                    return (new \App\Http\Frontend\Models\Tag)->firstOrCreate(['name' => $tag])->id;
                });
                $this->find($id)->updateTags($ids);
            }
        }
        return $this->respondWith(['updated' => !!$result]);
    }

    /**
     * 删除诗文
     *
     * @param $ids
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($ids)
    {
        // 获取作者ID
        $userIds = collection($ids)->map(function ($id) {
            return $this->find($id)->user_id;
        });
        // 若删除成功,该作者的作品总数 -1
        if ($result = !!$this->delete($ids)) {
            $userIds->map(function ($id) {
                (new \App\Http\Frontend\Models\User())->find($id)->decrement('works_count');
            });
        }
        return $this->respondWith(['deleted' => $result]);
    }

    /**
     * 切换该诗文的点赞状态
     *
     * @param $poem
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function vote($poem)
    {
        return $this->toggleAction($poem, (new \App\Http\Frontend\Models\Vote()));
    }

    /**
     * 切换该诗文的收藏状态
     *
     * @param $poem
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function favorite($poem)
    {
        return $this->toggleAction($poem, (new \App\Http\Frontend\Models\Favorite()));
    }

    /**
     * 获取该诗文的点赞状态
     *
     * @param $poem
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function voted($poem)
    {
        return $this->getActionStatus($poem, 'voted');
    }

    /**
     * 获取该诗文的收藏状态
     *
     * @param $poem
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function favored($poem)
    {
        return $this->getActionStatus($poem, 'favored');
    }

    /**
     * 获取该诗文的评分状态
     *
     * @param $poem
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function rated($poem)
    {
        return $this->getActionStatus($poem, 'rated');
    }

}