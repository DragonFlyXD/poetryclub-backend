<?php

namespace App\Http\Frontend\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Frontend\Models\Tag
 *
 * @mixin \Eloquent
 * @property int $id 自增ID
 * @property string $name 标签名字
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Tag whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Tag whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Tag whereUpdatedAt($value)
 */
class Tag extends Model
{
    protected $fillable = [
        'name'
    ];
}
