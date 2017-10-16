<?php

namespace App\Http\Frontend\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Http\Frontend\Models\Category
 *
 * @mixin \Eloquent
 * @property int $id 自增ID
 * @property string $name 分类名字
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Category whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Category whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Category whereUpdatedAt($value)
 */
class Category extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    protected $dates = ['deleted_at'];

}
