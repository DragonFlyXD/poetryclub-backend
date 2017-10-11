<?php

namespace App\Http\Frontend\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Zizaco\Entrust\Traits\EntrustUserTrait;

/**
 * User Model
 *
 * Class User
 *
 * @package App\Http\Frontend\Models
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Frontend\Models\User[] $followers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Frontend\Models\User[] $followersUser
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Frontend\Models\Poem[] $poems
 * @property-read \App\Http\Frontend\Models\Profile $profile
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @mixin \Eloquent
 * @property int $id 自增ID
 * @property string $name 用户名
 * @property string $email 邮箱地址
 * @property string $mobile 手机号码
 * @property string $password 密码
 * @property string $avatar 头像地址
 * @property string $confirmation_token 邮箱token
 * @property bool $is_active 激活状态
 * @property bool $role_level 角色等级
 * @property int $works_count 作品总数
 * @property int $favorites_count 收藏总数
 * @property int $likes_count 被点赞总数
 * @property int $comments_count 评论总数
 * @property int $shares_count 分享总数
 * @property int $followers_count 关注总数
 * @property int $followings_count 被关注总数
 * @property string $remember_token 记住用户名
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\User whereAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\User whereCommentsCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\User whereConfirmationToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\User whereFavoritesCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\User whereFollowersCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\User whereFollowingsCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\User whereIsActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\User whereLikesCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\User whereMobile($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\User whereRoleLevel($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\User whereSharesCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\User whereWorksCount($value)
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens, EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'mobile', 'avatar',
        'confirmation_token', 'is_active', 'social_id', 'social_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 获取密码授权时的 自定义username字段的 User Model
     *
     * @param $username
     * @return mixed
     */
    public function findForPassport($username)
    {
        return static::where(key($username), '=', current($username))->first();
    }

    /**
     * 判断所有者
     *
     * @param Model $model
     * @return bool
     */
    public function owns(Model $model)
    {
        return $this->id == $model->user_id;
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
     * 获取该用户下的所有诗文
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function poems()
    {
        return $this->hasMany(Poem::class);
    }

    /**
     * 获取该用户下所有的品鉴
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appreciations()
    {
        return $this->hasMany(Appreciation::class);
    }

    /**
     * 获取该用户的个人信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * 获取关注者列表
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany(self::class, 'followers', 'follower_id', 'followed_id')->withTimestamps();
    }

    /**
     * 获取被关注者列表
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followings()
    {
        return $this->belongsToMany(self::class, 'followers', 'followed_id', 'follower_id')->withTimestamps();
    }

    /**
     * 切换关注状态
     *
     * @param $user
     * @return array
     */
    public function toggleFollow($user)
    {
        return $this->followers()->toggle($user);
    }

    /**
     * 获取关注者状态
     *
     * @param $user
     * @return bool
     */
    public function followed($user)
    {
        return !!$this->followers()->where('followed_id', $user)->count();
    }
}
