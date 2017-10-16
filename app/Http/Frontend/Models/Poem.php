<?php

namespace App\Http\Frontend\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * Poem Model
 *
 * Class Poem
 *
 * @package App\Http\Frontend\Models
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Frontend\Models\Comment[] $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Frontend\Models\Favorite[] $favorites
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Frontend\Models\Rating[] $ratings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Frontend\Models\Tag[] $tags
 * @property-read \App\Http\Frontend\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Frontend\Models\Vote[] $votes
 * @mixin \Eloquent
 * @property int $id 自增ID
 * @property int $user_id 用户ID
 * @property int $category_id 分类ID
 * @property string $title 标题
 * @property string $body 内容
 * @property string $dynasty 朝代
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
 * @property string $summary
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Poem whereAppreciationsCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Poem whereBody($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Poem whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Poem whereCloseComment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Poem whereCommentsCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Poem whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Poem whereDynasty($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Poem whereFavoritesCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Poem whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Poem whereIsHidden($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Poem whereIsOriginal($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Poem whereIsValid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Poem whereLikesCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Poem wherePageviewsCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Poem whereSharesCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Poem whereSummary($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Poem whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Poem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Poem whereUserId($value)
 */
class Poem extends Model
{

//    use Searchable;

    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'category_id', 'title', 'body', 'summary', 'dynasty', 'is_original'
    ];

    /**
     * 数据模型的启动方法
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('show', function (Builder $builder) {
            // 限制只能查询有效且未隐藏的诗文
            $builder
                ->where([
                    ['is_valid', 1],
                    ['is_hidden', 0]
                ]);
        });
    }

    /**
     * 获取模型的索引名称
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'poems_index';
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
     * 获取该诗文所属的用户的个人信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'user_id');
    }

    /**
     * 获取该诗文下所属的所有品鉴模型
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appreciations()
    {
        return $this->hasMany(Appreciation::class);
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
     * 获取该诗文的评分
     *
     * @param $user
     * @return bool
     */
    public function rated($user)
    {
        return $this->ratings()->where('user_id', $user)->first();
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
     * 保存标签集
     *
     * @param $tags
     * @return array
     */
    public function storeTags($tags)
    {
        return $this->tags()->attach($tags);
    }

    /**
     * 更新标签集
     *
     * @param $tags
     * @return array
     */
    public function updateTags($tags)
    {
        return $this->tags()->sync($tags);
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
