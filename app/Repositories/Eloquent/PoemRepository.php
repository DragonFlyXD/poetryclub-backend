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
            $paginate = $this->paginate()->toArray();
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
        $poems = collection($paginate['data'])->map(function ($item) {
            // 这样预加载性能不友好,暂时不知道有什么方法... (查询耗费平均时间: 50ms)
            return $this->with(['user.profile.poems', 'tags', 'comments.user.profile', 'comments' => function ($query) {
                $query->orderBy('comments.created_at', 'desc');
            }])->find($item['id']);
        });
        $paginate['data'] = $this->transformModels($poems)
            ->sortByDesc('created_at')
            ->values()
            ->all();

        return $paginate;
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
            'category_id' => $this->category->findBy('name', $request->category)->id,
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
            return $this->respondWith(['created' => $result, 'poem' => $poem]);
        }
        return $this->respondWith(['created_at' => $result]);
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
        $poem = $this->with(['user.profile.poems', 'tags', 'comments.user.profile', 'comments' => function ($query) {
            $query->orderBy('comments.created_at', 'desc');
        }])->find($id);
        // 页面浏览数 +1
        $poem->increment('pageviews_count');
        return $this->transformModel($poem)
            ?: $this->errorNotFound();
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
        return $this->transformModel($poem)
            ?: $this->errorNotFound();
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
            'category_id' => $this->category->findBy('name', $request->category)->id,
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
            return $this->respondWith(['updated' => true]);
        }
        return $this->respondWith(['updated' => false]);
    }

    /**
     * 删除诗文
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $userId = id() ?: id('web');
        // 若删除成功,作品总数 -1
        if ($result = !!$this->delete($id)){
            (new \App\Http\Frontend\Models\User())->find($userId)->decrement('works_count');
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