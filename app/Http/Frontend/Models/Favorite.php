<?php

namespace App\Http\Frontend\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Frontend\Models\Favorite
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id 用户ID
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Favorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Favorite whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Favorite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Favorite whereUserId($value)
 */
class Favorite extends Model
{
    protected $fillable = ['user_id'];
}
