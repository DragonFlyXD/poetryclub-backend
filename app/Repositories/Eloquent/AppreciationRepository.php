<?php

namespace App\Repositories\Eloquent;

use Illuminate\Container\Container as App;
use App\Repositories\Eloquent\CategoryRepository as Category;

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
     * @return mixed
     */
    public function index()
    {
        // 获取分页数据
        $paginate = $this->model
            ->orderBy('created_at', 'desc')->paginate(10)->toArray();
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
        $poem = $this->with(['user.profile.poems' => function ($query) {
            $query->orderBy('poems.pageviews_count', 'desc');
        }, 'user.profile.appreciations' => function ($query) {
            $query->orderBy('appreciations.pageviews_count', 'desc');
        }, 'poem', 'tags', 'comments.user.profile', 'comments' => function ($query) {
            $query->orderBy('comments.created_at', 'desc');
        }])->find($id);
        // 页面浏览数 +1
        $poem->increment('pageviews_count');
        return $this->transformModel($poem)
            ?: $this->errorNotFound();
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
            return $this->respondWith(['updated' => true]);
        }
        return $this->respondWith(['updated' => false]);
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