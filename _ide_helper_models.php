<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Http\Frontend\Models{
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
	class Appreciation extends \Eloquent {}
}

namespace App\Http\Frontend\Models{
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
	class Category extends \Eloquent {}
}

namespace App\Http\Frontend\Models{
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
	class Comment extends \Eloquent {}
}

namespace App\Http\Frontend\Models{
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
	class Favorite extends \Eloquent {}
}

namespace App\Http\Frontend\Models{
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
	class Follower extends \Eloquent {}
}

namespace App\Http\Frontend\Models{
/**
 * App\Http\Frontend\Models\Inbox
 *
 * @property-read \App\Http\Frontend\Models\User $fromUser
 * @property-read \App\Http\Frontend\Models\User $toUser
 * @mixin \Eloquent
 * @property int $id 自增ID
 * @property int $from_user_id 发送私信的用户ID
 * @property int $to_user_id 接收私信的用户ID
 * @property string $body 私信内容
 * @property string $read_at 阅读私信内容的时间
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property bool $from_user_deleted
 * @property bool $to_user_deleted
 * @property int $dialog_id
 * @property bool $is_reply
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Inbox whereBody($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Inbox whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Inbox whereDialogId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Inbox whereFromUserDeleted($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Inbox whereFromUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Inbox whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Inbox whereIsReply($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Inbox whereReadAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Inbox whereToUserDeleted($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Inbox whereToUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Inbox whereUpdatedAt($value)
 */
	class Inbox extends \Eloquent {}
}

namespace App\Http\Frontend\Models{
/**
 * App\Http\Frontend\Models\Notification
 *
 * @mixin \Eloquent
 * @property int $id 自增ID
 * @property string $type 类型
 * @property int $notifiable_id
 * @property string $notifiable_type
 * @property string $data 内容
 * @property string $read_at 阅读时间
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Notification whereData($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Notification whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Notification whereNotifiableId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Notification whereNotifiableType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Notification whereReadAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Notification whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Notification whereUpdatedAt($value)
 */
	class Notification extends \Eloquent {}
}

namespace App\Http\Frontend\Models{
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
	class Poem extends \Eloquent {}
}

namespace App\Http\Frontend\Models{
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
	class Profile extends \Eloquent {}
}

namespace App\Http\Frontend\Models{
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
	class Rating extends \Eloquent {}
}

namespace App\Http\Frontend\Models{
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
	class Tag extends \Eloquent {}
}

namespace App\Http\Frontend\Models{
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
	class User extends \Eloquent {}
}

namespace App\Http\Frontend\Models{
/**
 * App\Http\Frontend\Models\Vote
 *
 * @mixin \Eloquent
 * @property int $id 自增ID
 * @property int $user_id 用户ID
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Vote whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Vote whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Vote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Frontend\Models\Vote whereUserId($value)
 */
	class Vote extends \Eloquent {}
}

