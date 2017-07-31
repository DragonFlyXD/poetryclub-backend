<?php

namespace App\Http\Frontend\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Frontend\Models\Follower
 *
 * @mixin \Eloquent
 * @property int $id 自增ID
 * @property int $follower_id 关注者ID
 * @property int $followed_id 被关注者ID
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Follower whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Follower whereFollowedId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Follower whereFollowerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Follower whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Follower whereUpdatedAt($value)
 */
class Follower extends Model
{
    protected $fillable = [
        'follower_id', 'followed_id'
    ];
}
