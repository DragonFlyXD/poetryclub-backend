<?php

namespace App\Repositories\Eloquent;

use App\Http\Frontend\Models\Poem;

class RatingRepository extends Repository
{

    /**
     * 指定模型名称
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Http\Frontend\Models\Rating';
    }

    /**
     * 存储评分
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store($request)
    {
        // 评分项
        $ratingItem = $request->except(['model', 'type']);
        // 格式化评分
        $score = rating($ratingItem);
        // 获取被评论模型名
        $modelName = $this->getModelNameFormType($request->type);
        // 若该用户已评论过此诗文,则直接具体评分
        $model = $request->type === 'poem' ? (new \App\Http\Frontend\Models\Poem()) : (new \App\Http\Frontend\Models\Appreciation());
        $rated = $model->find($request->model)->rated(id());
        if ($rated) {
            return $this->respondWith(['rated' => $score]);
        }
        // 存储评分入库
        $this->create([
            'user_id' => id(),
            'ratingable_id' => $request->model,
            'ratingable_type' => $modelName,
            key($ratingItem) => current($ratingItem)
        ]);
        // 标准化该模型的评分总数
        $this->normalizeModelCount($request->model, $modelName, 'ratings_count');
        return $this->respondWith(['rated' => $score]);
    }
}