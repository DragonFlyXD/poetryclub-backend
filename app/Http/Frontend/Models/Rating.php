<?php

namespace App\Http\Frontend\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Frontend\Models\Rating
 *
 * @mixin \Eloquent
 * @property int $id 自增ID
 * @property int $user_id 用户ID
 * @property int $ratingable_id
 * @property string $ratingable_type
 * @property bool $rating_1 评为1分
 * @property bool $rating_2 评为2分
 * @property bool $rating_3 评为3分
 * @property bool $rating_4 评为4分
 * @property bool $rating_5 评为5分
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Rating whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Rating whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Rating whereRating1($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Rating whereRating2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Rating whereRating3($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Rating whereRating4($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Rating whereRating5($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Rating whereRatingableId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Rating whereRatingableType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Rating whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Rating whereUserId($value)
 */
class Rating extends Model
{
    protected $fillable = [
        'user_id','ratingable_id','ratingable_type','rating_1','rating_2','rating_3','rating_4','rating_5'
    ];
}
