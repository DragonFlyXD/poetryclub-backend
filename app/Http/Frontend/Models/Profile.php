<?php

namespace App\Http\Frontend\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Frontend\Models\Profile
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Frontend\Models\Poem[] $poems
 * @mixin \Eloquent
 * @property int $id 自增ID
 * @property int $user_id 用户ID
 * @property string $nickname 昵称
 * @property bool $gender 性别 1>男 2>女
 * @property string $birthday 生日
 * @property string $signature 私人语录
 * @property string $location 居住地
 * @property string $occupation 职业
 * @property string $bio 个人简介
 * @property string $poet 最钟爱的诗人
 * @property bool $is_hidden 是否隐藏该个人信息
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Profile whereBio($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Profile whereBirthday($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Profile whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Profile whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Profile whereIsHidden($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Profile whereLocation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Profile whereNickname($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Profile whereOccupation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Profile wherePoet($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Profile whereSignature($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Profile whereUserId($value)
 */
class Profile extends Model
{
    protected $fillable = [
        'user_id', 'nickname', 'gender', 'birthday', 'signature', 'location', 'occupation', 'bio', 'poet'
    ];

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
     * 获取该个人信息所归属的用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 获取该用户下的所有诗文
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function poems()
    {
        return $this->hasMany(Poem::class, 'user_id', 'user_id');
    }

    /**
     * 获取该用户下的所有品鉴
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appreciations()
    {
        return $this->hasMany(Appreciation::class, 'user_id', 'user_id');
    }
}
