<?php

namespace App\Repositories\Eloquent;

class CommentRepository extends Repository
{
    /**
     * 指定模型名称
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Http\Frontend\Models\Comment';
    }


    /**
     * 存储评论入库
     *
     * @param $request
     * @return mixed
     */
    public function store($request)
    {
        // 获取被评论模型名
        $model = $this->getModelNameFormType($request->type);
        // 存储数据入库
        $comment = $this->create([
            'user_id' => id(),
            'body' => $request->body,
            'commentable_id' => $request->model,
            'commentable_type' => $model,
            'parent_id' => $request->get('parent', null)
        ]);
        // 标准化该模型的评论总数
        $this->normalizeModelCount($request->model, $model);
        // 获取该评论的用户信息
        $user = collection($comment->user)->merge(['profile' => $comment->user->profile]);
        // 返回新增的评论模型
        return $this->transformComment([collection($comment)->merge(['user' => $user])])[0];
    }
}