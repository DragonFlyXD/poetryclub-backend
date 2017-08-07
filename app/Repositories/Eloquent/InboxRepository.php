<?php

namespace App\Repositories\Eloquent;

use Carbon\Carbon;

class InboxRepository extends Repository
{

    /**
     * 指定模型名称
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Http\Frontend\Models\Inbox';
    }

    /**
     * 获取私信列表
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function index()
    {
        // 获取与该用户有关的私信列表
        $messages = $this->model
            // 过滤已删除的私信
            ->where([
                ['to_user_id', id()],
                ['to_user_deleted', '<>', true]
            ])
            ->orWhere([
                ['from_user_id', id()],
                ['from_user_deleted', '<>', true]
            ])
            // 获取私信双方个人信息
            ->with(['fromUser.profile', 'toUser.profile'])
            // 按发送时间降序
            ->latest()
            ->get()
            // 获取发送时间最早的一条私信作为列表预览
            ->unique('dialog_id')
            ->values();

        // 全部私信
        $allMessages = $this->transformMessages($messages);
        // 未读私信
        $unreadMessages = $allMessages->reject(function ($message) {
            return id() === $message['from_user_id'] || !!$message['read_at'];
        })->values()->all();

        return $this->respondWith([
            'allMessages' => $allMessages,
            'unreadMessages' => $unreadMessages,
            'unreadLength' => count($unreadMessages)
        ]);
    }

    /**
     * 获取对话列表
     *
     * @param $dialog
     * @return mixed
     */
    public function show($dialog)
    {
        // 获取对话内容
        $messages = $this->model
            ->where([
                ['dialog_id', $dialog],
                ['to_user_id', id()],
                ['to_user_deleted', '<>', true]
            ])
            ->orWhere([
                ['dialog_id', $dialog],
                ['from_user_id', id()],
                ['from_user_deleted', '<>', true]
            ])
            ->with(['fromUser.profile', 'toUser.profile'])
            ->latest()
            ->get();
        // 标志已读
        $this->markAsRead($messages);
        return $this->transformMessages($messages);
    }

    /**
     * 存储私信内容
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store($request)
    {
        // 检查私信双方是否交流过
        $message = $this->model
            ->where([
                ['from_user_id', id()],
                ['to_user_id', $request->user]
            ])
            ->orWhere([
                ['to_user_id', id()],
                ['from_user_id', $request->user]
            ])
            ->first();
        // 若有交流过,则使用同一个 dialogId。反之,则新建一个 dialogId
        $dialog = $message ? $message->dialog_id : time() . id();
        // 存储私信内容
        $model = $this->create([
            'from_user_id' => id(),
            'to_user_id' => $request->user,
            'body' => $request->body,
            'dialog_id' => $dialog
        ]);
        // 格式化私信数据
        $res = $this->transformMessages($model, true);
        return $this->respondWith(['sent' => !!$model, 'message' => $res]);
    }

    /**
     * 存储对话内容
     *
     * @param $request
     * @param $dialog
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function dialog($request, $dialog)
    {
        // 获取对话ID
        $message = $this->findBy('dialog_id', $dialog);
        // 抉择 to_user_id
        $toUserId = $message->from_user_id === id() ? $message->to_user_id : $message->from_user_id;
        // 获取单个对话内容
        $model = $this->create([
            'from_user_id' => id(),
            'to_user_id' => $toUserId,
            'body' => $request->body,
            'dialog_id' => $dialog
        ]);
        // 格式化私信数据
        $res = $this->transformMessages($model, true);
        return $this->respondWith(['sent' => !!$model, 'message' => $res]);
    }

    /**
     * 全部未读私信标志已读
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function view()
    {
        // 获取对话内容
        $messages = $this->model
            ->where('to_user_id', id())
            ->get();
        // 标志已读
        $this->markAsRead($messages);
        return $this->respondWith(['read' => true]);
    }

    /**
     * 删除对话内容
     *
     * @param $dialog
     * @param $id
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function deleteMessage($dialog, $id)
    {
        // 获取该私信
        $singleLetter = $this->model->where([
            ['dialog_id', $dialog],
            ['id', $id]
        ])->first();
        // 若删除私信对象为发送者
        $singleLetter->from_user_id === id()
            ? $singleLetter->from_user_deleted = true
            // 若删除私信对象为接收者
            : $singleLetter->to_user_deleted = true;
        $singleLetter->save();
        // 若私信双方皆删除了该私信,则将该私信从数据库中剔除
        if ($singleLetter->from_user_deleted && $singleLetter->to_user_deleted) {
            $singleLetter->delete();
        }
        return $this->respondWith(['deleted' => true]);
    }

    /**
     * 删除对话
     *
     * @param $dialog
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function destroy($dialog)
    {
        // 获取对话列表
        $messages = $this->model->where('dialog_id', $dialog)->get();
        $messages->map(function ($item) {
            // 抉择删除对象是发送者还是接受者
            $choice = $item->from_user_id === id() ? 'from_user_deleted' : 'to_user_deleted';
            $item[$choice] = true;
            $item->save();
        });
        // 若私信双方皆删除了私信内容,则将该私信内容从数据库中删除
        $this->model->where([
            ['dialog_id', $dialog],
            ['from_user_deleted', true],
            ['to_user_deleted', true]
        ])->delete();
        return $this->respondWith(['deleted' => true]);
    }

    /**
     * 格式化私信内容
     *
     * @param $messages
     * @param bool $isOnly
     * @return mixed
     */
    public function transformMessages($messages, $isOnly = false)
    {
        // 若格式的为单个私信内容
        if ($isOnly) {
            $messages = collection([$messages]);
        }

        $res = $messages->map(function ($message) {
            $message['publish_time'] = $this->transformTime($message['created_at']);
            // 设置对话列表的 URL
            $message['dialogUrl'] = '/inbox/' . $message->dialog_id;
            // 列表预览显示发送者信息
            $message['user'] = $this->transformUser($message->fromUser);
            return collection($message)
                ->forget(['from_user', 'to_user', 'fromUser', 'toUser']);
        });

        if ($isOnly) {
            return $res[0];
        }
        return $res;
    }

    /**
     * 标志私信已读
     *
     * @param $messages
     */
    public function markAsRead($messages)
    {
        $messages->map(function ($item) {
            // 若 read_at 没有被标志
            if (is_null($item->read_at)) {
                // 只能是接收者才能标志已阅读
                if ($item->to_user_id === id()) {
                    $item->read_at = Carbon::now();
                    $item->save();
                }
            }
        });
    }
}