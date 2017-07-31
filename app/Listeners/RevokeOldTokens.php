<?php

namespace App\Listeners;

use App\Http\Frontend\Models\User;
use Carbon\Carbon;
use Laravel\Passport\Events\AccessTokenCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Passport\Token;

class RevokeOldTokens
{

    /**
     * RevokeOldTokens constructor.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  AccessTokenCreated $event
     * @return void
     */
    public function handle(AccessTokenCreated $event)
    {
        Token::where([
            ['id', '<>', $event->tokenId],
            ['user_id', $event->userId],
            ['client_id', $event->clientId],
            ['expires_at', '<', Carbon::now()]
        ])
            ->orwhere('revoked', true)
            ->delete();
    }
}
