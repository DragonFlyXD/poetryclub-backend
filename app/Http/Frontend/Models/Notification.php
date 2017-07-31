<?php

namespace App\Http\Frontend\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Frontend\Models\Notification
 *
 * @mixin \Eloquent
 * @property int $id 自增ID
 * @property string $type 类型
 * @property int $notifiable_id
 * @property string $notifiable_type
 * @property string $data 内容
 * @property string $read_at 阅读时间
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Notification whereData($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Notification whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Notification whereNotifiableId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Notification whereNotifiableType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Notification whereReadAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Notification whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Notification whereUpdatedAt($value)
 */
class Notification extends Model
{
    protected $fillable = [
        'type', 'notifiable_id', 'notifiable_type', 'data'
    ];
}
