<?php

namespace App\Mail;

use App\Http\Frontend\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterShipped extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * RegisterShipped constructor.
     * @param User $user
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
        return $this->view('email.user.register')
            ->subject('邮箱验证')
            ->with([
                'verify_url' => 'http://www.dragonflyxd.com/email/register/verify?token=' . $this->user->confirmation_token,
                'name' => $this->user->name
            ]);
    }
}
