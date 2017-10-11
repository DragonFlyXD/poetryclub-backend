<?php

namespace App\Http\Frontend\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Frontend\Models\Comment
 *
 * @property-read \App\Http\Frontend\Models\User $user
 * @mixin \Eloquent
 * @property int $id 自增ID
 * @property int $user_id 用户ID
 * @property int $parent_id 父级评论ID
 * @property string $body 内容
 * @property int $commentable_id
 * @property string $commentable_type
 * @property int $likes_count 被点赞总数
 * @property bool $level 评论层级
 * @property bool $is_hidden 是否隐藏该评论
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Comment whereBody($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Comment whereCommentableId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Comment whereCommentableType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Comment whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Comment whereIsHidden($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Comment whereLevel($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Comment whereLikesCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Comment whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Comment whereUserId($value)
 */
class Comment extends Model
{
    protected $fillable = [
        'user_id', 'parent_id', 'body', 'commentable_id', 'commentable_type', 'level'
    ];

    /**
     * 数据模型的启动方法
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('hidden', function (Builder $builder) {
            // 限制只能查询未隐藏的评论
            $builder->where('is_hidden', 0);
        });
    }

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
     * 获取该评论所属的用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
