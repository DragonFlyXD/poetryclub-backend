<?php

namespace App\Http\Frontend\Controllers;

use App\Http\Controller;
use App\Mail\FeedbackShipped;
use App\Repositories\Eloquent\UserRepository as User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public $user;

    /**
     * EmailController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * 注册验证
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function register(Request $request)
    {
        return $this->user->verifyRegister($request->get('token'));
    }

    /**
     * 发送网站建议反馈邮件
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function feedback(Request $request)
    {
        Mail::send('email.user.feedback', [
            'body' => $request->get('body'),
            'subject' => $subject = $request->get('subject')
        ], function ($message) use ($subject) {
            $to = 'dragonfly920130@outlook.com';
            $message->to($to)->subject($subject);
        });
    }
}
