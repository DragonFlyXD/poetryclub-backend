<?php

namespace App\Http\Frontend\Controllers;

use App\Http\Controller;
use App\Http\Requests\StoreMessage;
use App\Repositories\Eloquent\InboxRepository as Inbox;
use Illuminate\Http\Request;

class InboxController extends Controller
{

    protected $inbox;

    public function __construct(Inbox $inbox)
    {
        $this->inbox = $inbox;
    }

    /**
     * 获取私信列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->inbox->index();
    }

    /**
     * 获取对话列表
     *
     * @param $dialog
     * @return \Illuminate\Http\Response
     */
    public function show($dialog)
    {
        return $this->inbox->show($dialog);
    }

    /**
     * 存储私信内容
     *
     * @param StoreMessage $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMessage $request)
    {
        return $this->inbox->store($request);
    }

    /**
     * 存储对话内容
     *
     * @param StoreMessage $request
     * @param $dialog
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function dialog(StoreMessage $request, $dialog)
    {
        return $this->inbox->dialog($request, $dialog);
    }

    /**
     * 全部未读私信标志已读
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function view()
    {
        return $this->inbox->view();
    }

    /**
     * 删除对话内容
     *
     * @param $dialog
     * @param $id
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function delete($dialog, $id)
    {
        return $this->inbox->deleteMessage($dialog, $id);
    }

    /**
     * 删除对话
     *
     * @param $dialog
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function destroy($dialog)
    {
        return $this->inbox->destroy($dialog);
    }

}
