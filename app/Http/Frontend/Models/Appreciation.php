<?php

namespace App\Http\Frontend\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Frontend\Models\Appreciation
 *
 * @mixin \Eloquent
 * @property int $id 自增ID
 * @property int $user_id 用户ID
 * @property int $poem_id 诗文ID
 * @property string $title 标题
 * @property string $body 内容
 * @property int $pageviews_count 浏览量总数
 * @property int $comments_count 评论总数
 * @property int $favorites_count 收藏总数
 * @property int $appreciations_count
 * @property int $shares_count 分享总数
 * @property int $likes_count 被点赞总数
 * @property bool $is_original 是否为原创诗文
 * @property bool $is_valid 是否已发布
 * @property bool $close_comment 是否关闭评论
 * @property bool $is_hidden 是否隐藏该诗文
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Appreciation whereAppreciationsCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Appreciation whereBody($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Appreciation whereCloseComment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Appreciation whereCommentsCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Appreciation whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Appreciation whereFavoritesCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Appreciation whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Appreciation whereIsHidden($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Appreciation whereIsOriginal($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Appreciation whereIsValid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Appreciation whereLikesCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Appreciation wherePageviewsCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Appreciation wherePoemId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Appreciation whereSharesCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Appreciation whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Appreciation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Appreciation whereUserId($value)
 */
class Appreciation extends Model
{
    protected $fillable = [
        'user_id', 'poem_id', 'title', 'body', 'summary', 'is_original'
    ];

    protected static function boot()
    {
        parent::boot();
        // 限制只能查询有效的品鉴
        static::addGlobalScope('is_valid', function (Builder $builder) {
            $builder->where('is_valid', 1);
        });
    }

    /**
     * 获取模型的索引名称
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'appreciations_index';
    }

    /**
     * 得到该模型可索引数据的数组
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();
        return $array;
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
     * 获取该诗文所属的用户模型
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 获取该诗文所属的标签模型
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }

    /**
     * 获取该诗文所有的评论模型
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * 获取该诗文所有的评分
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function ratings()
    {
        return $this->morphMany(Rating::class, 'ratingable');
    }

    /**
     * 获取该诗文所属的点赞模型
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function votes()
    {
        return $this->morphToMany(Vote::class, 'votable')->withTimestamps();
    }

    /**
     * 获取该诗文所属的收藏模型
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favorites()
    {
        return $this->morphToMany(Favorite::class, 'favoritable')->withTimestamps();
    }

    /**
     * 获取该诗文的评分状态
     *
     * @param $user
     * @return bool
     */
    public function rated($user)
    {
        return !!$this->ratings()->where('user_id', $user)->count();
    }

    /**
     * 获取该诗文的点赞状态
     *
     * @param $user
     * @return bool
     */
    public function voted($user)
    {
        return !!$this->votes()->where('user_id', $user)->count();
    }

    /**
     * 获取该诗文的收藏状态
     *
     * @param $user
     * @return bool
     */
    public function favored($user)
    {
        return !!$this->favorites()->where('user_id', $user)->count();
    }

    /**
     * 存储诗文标签
     *
     * @param $tags
     */
    public function storeTags($tags)
    {
        return $this->tags()->attach($tags);
    }

    /**
     * 切换诗文的点赞状态
     *
     * @param $vote
     * @return array
     */
    public function toggleVote($vote)
    {
        return $this->votes()->toggle($vote);
    }

    /**
     * 切换诗文的收藏状态
     *
     * @param $favorite
     * @return array
     */
    public function toggleFavorite($favorite)
    {
        return $this->favorites()->toggle($favorite);
    }

}
