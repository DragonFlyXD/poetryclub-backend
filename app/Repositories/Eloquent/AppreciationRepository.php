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
        $paginate = $this->paginate()->toArray();
        // 格式化品鉴数据
        $poems = collection($paginate['data'])->map(function ($item) {
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
     * 存储品鉴内容
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store($request)
    {
        // 创建品鉴
        $appreciation = $this->create([
            'user_id' => id(),
            'category_id' => $this->category->findBy('name', $request->category)->id,
            'title' => $request->title,
            'body' => $request->body,
            'summary' => mb_substr($request->body, 0, 150, 'UTF-8')
        ]);
        // 若品鉴有定义标签
        if (($tags = $request->dynamicTags) && count($request->dynamicTags) <= 5) {
            // 标签不存在,则新建之
            $ids = collection($tags)->map(function ($tag) use ($appreciation) {
                return (new  \App\Http\Frontend\Models\Tag)->firstOrCreate(['name' => $tag])->id;
            });
            $appreciation->storeTags($ids);
        }
        return $this->respondWith(['created' => true, 'poem' => $appreciation]);
    }

    /**
     * 获取指定品鉴
     *
     * @param $appreciation
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($appreciation)
    {
        $poem = $this->with(['user.profile.poems', 'tags', 'comments.user.profile', 'comments' => function ($query) {
            $query->orderBy('comments.created_at', 'desc');
        }])->find($appreciation);
        // 页面浏览数 +1
        $poem->increment('pageviews_count');
        return $this->transformModel($poem)
            ?: $this->errorNotFound();
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