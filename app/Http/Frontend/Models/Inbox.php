<?php

namespace App\Http\Frontend\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Http\Frontend\Models\Inbox
 *
 * @property-read \App\Http\Frontend\Models\User $fromUser
 * @property-read \App\Http\Frontend\Models\User $toUser
 * @mixin \Eloquent
 * @property int $id 自增ID
 * @property int $from_user_id 发送私信的用户ID
 * @property int $to_user_id 接收私信的用户ID
 * @property string $body 私信内容
 * @property string $read_at 阅读私信内容的时间
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property bool $from_user_deleted
 * @property bool $to_user_deleted
 * @property int $dialog_id
 * @property bool $is_reply
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Inbox whereBody($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Inbox whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Inbox whereDialogId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Inbox whereFromUserDeleted($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Inbox whereFromUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Inbox whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Inbox whereIsReply($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Inbox whereReadAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Inbox whereToUserDeleted($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Inbox whereToUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Inbox whereUpdatedAt($value)
 */
class Inbox extends Model
{

    protected $table = "messages";
    protected $fillable = [
        'from_user_id', 'to_user_id', 'body','dialog_id','is_reply'
    ];

    /*
    |--------------------------------------------------------------------------
    | 模型关联
    |--------------------------------------------------------------------------
    |
    | 
    |
    |
    */

    /**
     * 获取发送者信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * 获取接受者信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
