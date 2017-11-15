<?php

namespace App\Repositories\Eloquent;

use Illuminate\Container\Container as App;
use App\Repositories\Eloquent\CategoryRepository as Category;
use Illuminate\Http\JsonResponse;

class AppreciationRepository extends Repository
{
    protected $category;

    /**
     * AppreciationRepository constructor.
     * @param App $app
     * @param CategoryRepository $category
     */
    public function __construct(App $app, Category $category)
    {
        parent::__construct($app);
        $this->category = $category;
    }

    /**
     * 指定模型名称
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Http\Frontend\Models\Appreciation';
    }

    /**
     * 获取品鉴列表
     *
     * @param string $query
     * @return mixed
     */
    public function index($query = '')
    {
        // 获取分页数据
        if (!$query) {
            $paginate = $this->model
                ->orderBy('created_at', 'desc')
                ->paginate(10)
                ->toArray();
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

        // 格式化品鉴数据
        $appreciations = collection($paginate['data'])
            ->map(function ($item) {
                return $this->with(['user.profile', 'tags', 'poem.user.profile', 'comments.user.profile', 'comments' => function ($query) {
                    $query->orderBy('comments.created_at', 'desc');
                }])->find($item['id']);
            });

        $paginate['data'] = $this->transformModels($appreciations, 'appreciation')
            ->all();

        return $paginate;
    }

    /**
     * 存储品鉴内容
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store($request)
    {
        // 创建品鉴
        $userId = id() ?: id('web');
        $appreciation = $this->create([
            'poem_id' => $poem = $request->poem,
            'user_id' => $userId,
            'category_id' => $request->category,
            'title' => $request->title,
            'body' => $request->body,
            'summary' => mb_substr($request->body, 0, 150, 'UTF-8')
        ]);
        if ($result = !!$appreciation) {
            // 作者作品总数 +1
            (new \App\Http\Frontend\Models\User())->find($userId)->increment('works_count');
            // 被品鉴诗文的品鉴数量 +1
            (new \App\Http\Frontend\Models\Poem())->find($poem)->increment('appreciations_count');
            // 若诗文有定义标签
            if (($tags = $request->dynamicTags) && count($request->dynamicTags) <= 5) {
                // 标签不存在,则新建之
                $ids = collection($tags)->map(function ($tag) use ($appreciation) {
                    return (new \App\Http\Frontend\Models\Tag)->firstOrCreate(['name' => $tag])->id;
                });
                $appreciation->storeTags($ids);
            }
            return $this->respondWith(['created' => $result, 'appreciation' => $appreciation]);
        }
        return $this->respondWith(['created' => $result]);
    }

    /**
     * 获取指定品鉴
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // (查询耗费平均时间: 10ms)
        $appreciation = $this->with(['user.profile.poems' => function ($query) {
            $query->orderBy('poems.pageviews_count', 'desc');
        }, 'user.profile.appreciations' => function ($query) {
            $query->orderBy('appreciations.pageviews_count', 'desc');
        }, 'poem', 'tags', 'comments.user.profile', 'comments' => function ($query) {
            $query->orderBy('comments.created_at', 'desc');
        }])->find($id);
        if ($appreciation) {
            $appreciation->increment('pageviews_count');
            return $this->transformModel($appreciation);
        } else {
            return $this->errorNotFound();
        }
    }

    /**
     * 搜索指定品鉴
     *
     * @param $keyword
     * @return mixed
     */
    public function scout($keyword)
    {
        return $this->transformModels($this->with(['user.profile', 'tags', 'poem.user.profile', 'comments.user.profile', 'comments' => function ($query) {
            $query->orderBy('comments.created_at', 'desc');
        }])
            ->where('title', 'like', "%$keyword%")
            ->get(), 'appreciation')
            ->sortByDesc('pageviews_count')
            ->values()
            ->all();
    }

    /**
     * 获取需要更新的品鉴的数据
     *
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        $appreciation = $this->with(['user.profile', 'tags', 'poem.user.profile'])->find($id);
        if (!$appreciation) {
            return $this->errorNotFound();
        } else {
            $appreciation = $this->transformModel($appreciation);
            // 获取品鉴的源诗文
            $poem = (new \App\Http\Frontend\Models\Poem())->find($appreciation['poem_id']);
            return ['appreciation' => $appreciation, 'poem' => $poem ?: \GuzzleHttp\json_encode([])];
        }
    }

    /**
     * 更新指定品鉴
     *
     * @param $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function renew($request, $id)
    {
        $result = $this->update([
            'poem_id' => $request->poem,
            'title' => $request->title,
            'body' => $request->body,
            'category_id' => $request->category,
            'summary' => mb_substr($request->body, 0, 150, 'UTF-8')
        ], $id);
        // 若更新成功
        if ($result) {
            // 若品鉴有定义标签
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
     * 删除品鉴
     *
     * @param $ids
     * @return \Illuminate\Http\JsonResponse|mixed
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
     * 切换该品鉴的点赞状态
     *
     * @param $appreciation
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function vote($appreciation)
    {
        return $this->toggleAction($appreciation, (new \App\Http\Frontend\Models\Vote()));
    }

    /**
     * 切换该品鉴的收藏状态
     *
     * @param $appreciation
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function favorite($appreciation)
    {
        return $this->toggleAction($appreciation, (new \App\Http\Frontend\Models\Favorite()));
    }

    /**
     * 获取该品鉴点赞状态
     *
     * @param $appreciation
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function voted($appreciation)
    {
        return $this->getActionStatus($appreciation, 'voted');
    }

    /**
     * 获取该品鉴的收藏状态
     *
     * @param $appreciation
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function favored($appreciation)
    {
        return $this->getActionStatus($appreciation, 'favored');
    }

    /**
     * 获取该品鉴评分状态
     *
     * @param $appreciation
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function rated($appreciation)
    {
        return $this->getActionStatus($appreciation, 'rated');
    }


}