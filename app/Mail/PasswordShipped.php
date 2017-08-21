<?php

namespace App\Mail;

use App\Http\Frontend\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordShipped extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * PasswordShipped constructor.
     * @param $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.password.reset')
            ->subject('重置密码')
            ->with([
                'verify_url' => 'http://www.dragonflyxd.com/user/password/reset/' . $this->user->confirmation_token,
                'name' => $this->user->name
            ]);
    }
}
