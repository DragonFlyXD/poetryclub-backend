<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\ApiRepositoryInterface;
use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

/**
 * Repository 抽象类
 *
 * Class Repository
 * @package App\Repositories\Eloquent
 */
abstract class Repository implements RepositoryInterface, ApiRepositoryInterface
{
    private $app;
    protected $model;
    protected $statusCode = 200;

    /**
     * 依赖注入 Container与创建模型
     *
     * Repository constructor.
     * @param $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     * 指定模型名称
     *
     * @return mixed
     */
    abstract function model();

    /**
     * 根据模型名创建Eloquent ORM 实例
     *
     * @return bool|\Illuminate\Database\Eloquent\Builder
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());
        if (!$model instanceof Model) {
            return false;
        }
        return $this->model = $model;
    }

    /*
    |--------------------------------------------------------------------------
    | 数据库相关
    |--------------------------------------------------------------------------
    |
    | 含有数据库的CRUD操作,分页等
    |
    |
    */

    /**
     * 根据主键查找数据
     *
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'))
    {
        return $this->model->find($id, $columns);
    }

    /**
     * 根据指定键与值查找数据
     *
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = array('*'))
    {
        return $this->model->where($attribute, '=', $value)->first($columns);
    }

    /**
     * 获取所有数据
     *
     * @param array $columns
     * @return mixed
     */
    public function all($columns = array('*'))
    {
        return $this->model->get($columns);
    }

    /**
     * 预加载
     *
     * @param $relations
     * @return mixed
     */
    public function with($relations)
    {
        return $this->model->with(is_string($relations) ? func_get_args() : $relations);
    }

    /**
     * 批量创建
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * 根据主键更新
     *
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        return $this->model->where($attribute, '=', $id)->update($data);
    }

    /**
     * 根据主键删除数据
     *
     * @param $ids
     * @return mixed
     */
    public function delete($ids)
    {
        return $this->model->destroy($ids);
    }

    /**
     * 获取分页数据
     *
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 10, $columns = array('*'))
    {
        return $this->model->paginate($perPage, $columns);
    }

    /*
    |--------------------------------------------------------------------------
    | API相关
    |--------------------------------------------------------------------------
    |
    |
    |
    |
    */

    /**
     * 获取状态码
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * 设置状态码
     *
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * 根据数据类型来产生响应
     *
     * @param $data
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function respondWith($data, array $headers = [])
    {
        if (!$data) {
            return $this->errorNotFound('Requested response not found。');
        } elseif ($data instanceof Collection || $data instanceof LengthAwarePaginator || $data instanceof Model) {
            return $this->respondWithItem($data, $headers);
        } elseif (is_string($data) || is_array($data)) {
            return $this->respondWithArray($data, $headers);
        } else {
            return $this->errorInternalError();
        }
    }

    /**
     * 产生响应并处理Collection对象或Eloquent模型
     *
     * @param $item
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithItem($item, array $headers = [])
    {
        $response = response()->json($item->toArray(), $this->statusCode, $headers);
        return $response;
    }

    /**
     * 产生响应并处理数组或字符串
     *
     * @param array $array
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithArray(array $array, array $headers = [])
    {
        $response = response()->json($array, $this->statusCode, $headers);
        return $response;
    }

    /**
     * 产生响应并且返回错误
     *
     * @param $message
     * @param $errorCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($message, $errorCode)
    {
        return $this->respondWithArray([
            'error' => [
                'code' => $errorCode,
                'http_code' => $this->statusCode,
                'message' => $message
            ]
        ]);
    }

    /**
     * 请求不允许
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorForbidden($message = 'Forbidden')
    {
        return $this->setStatusCode(403)->respondWithError($message, self::CODE_FORBIDDEN);
    }

    /**
     * 服务器内部产生错误
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorInternalError($message = "Internal Error")
    {
        return $this->setStatusCode(500)->respondWithError($message, self::CODE_INTERNAL_ERROR);
    }

    /**
     * 没有找到指定资源
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorNotFound($message = 'Resource Not Found')
    {
        return $this->setStatusCode(404)->respondWithError($message, self::CODE_NOT_FOUND);
    }

    /**
     * 请求授权失败
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorUnauthorized($message = "Unauthorized")
    {
        return $this->setStatusCode(401)->respondWithError($message, self::CODE_UNAUTHORIZED);
    }

    /**
     * 请求错误
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorWrongArgs($message = 'Wrong Arguments')
    {
        return $this->setStatusCode(400)->respondWithError($message, self::CODE_WRONG_ARGS);
    }

    /**
     * 无法处理的请求实体
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorUnprocessableEntity($message = "Unprocessable Entity")
    {
        return $this->setStatusCode(422)->respondWithError($message, self::CODE_UNPROCESSABLE_ENTITY);
    }

    /**
     * 自定义验证数据
     *
     * @param Request $request
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return mixed|void
     */
    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        Validator::make($request->all(), $rules, $messages, $customAttributes)->validate();
    }

    /*
    |--------------------------------------------------------------------------
    | 杂项
    |--------------------------------------------------------------------------
    |
    | 
    |
    |
    */
    /**
     * 格式化时间
     *
     * @param $date
     * @return mixed
     */
    public function transformTime($date)
    {
        return \Carbon\Carbon::parse($date)->diffForHumans();
    }

    /**
     * 获取用户的昵称
     *
     * @param $user
     * @return mixed
     */
    public function getNickname($user)
    {
        return array_get($user, 'profile.nickname') ?: $user['name'];
    }

    /**
     * 格式化个人信息
     *
     * @param $user
     * @param $other
     * @return mixed
     */
    public function transformUser($user, $other = false)
    {
        $user = collection($user)->except('confirmation_token');
        $profile = collection($user->get('profile'))->except('id', 'user_id', 'nickname', 'created_at', 'updated_at');

        // 若设置了性别,格式化gender
        if ($profile->get('gender')) {
            $profile['gender'] = $profile['gender'] === 1 ? '男' : '女';
        } else {
            $profile['gender'] = '';
        }

        // 格式化该用户的创建时间、ProfileUrl、昵称
        $user['publish_time'] = $this->transformTime($user['created_at']);
        $user['profileUrl'] = '/user/' . $user['name'];
        $user['nickname'] = $this->getNickname($user);

        return $other
            ? $user
            : $user->merge($profile)->forget('profile');
    }

    /**
     * 格式化评论信息
     *
     * @param $comments
     * @return mixed
     */
    public function transformComment($comments)
    {
        // 如果评论为空,则返回 nul
        if (empty($comments)) {
            return null;
        }
        $comments = collection($comments);
        return $comments->map(function ($comment) {
            // 格式化评论的发布时间
            $comment['publish_time'] = $this->transformTime($comment['created_at']);

            // 设置profile地址链接与用户昵称
            $user['profileUrl'] = '/user/' . $comment['user']['name'];
            $user['avatar'] = $comment['user']['avatar'];
            $user['nickname'] = $this->getNickname($comment['user']);

            // 若为回复评论,则获取被回复者的个人信息
            $parent = null;
            if ($parent_id = $comment['parent_id']) {
                $ori_parent = (new \App\Http\Frontend\Models\Comment())
                    ->with('user.profile')
                    ->find($parent_id);
                $parent['profileUrl'] = '/user/' . $ori_parent['user']['name'];
                $parent['nickname'] = $this->getNickname($ori_parent['user']);
                $parent['body'] = $ori_parent['body'];
            }

            return collection($comment)
                ->merge($user)
                ->merge(['parent' => $parent])
                ->forget('user');
        });

    }

    /**
     * 获取指定模型的所有评论
     *
     * @param $id
     * @return mixed
     */
    public function getModelCommentsById($id)
    {
        return $this->transformComment($this->with('comments.user')->find($id)->comments);
    }

    /**
     * 格式化模型信息
     *
     * @param $model
     * @param string $type
     * @param bool $isRepeat
     * @return mixed
     */
    public function transformModel($model, $type = 'poem', $isRepeat = false)
    {
        // 设置作品的 Date 格式的发表时间
        $model['publish_time'] = $this->transformTime($model['created_at']);
        $model = collection($model);
        $user = $this->transformUser($model['user']);

        // 若不是重复格式化模型
        if (!$isRepeat) {
            // 如果该用户有诗文作品
            if ($user->has('poems')) {
                $user['poems'] = collection($user['poems'])->map(function ($work) {
                    // 设置诗文作品的地址链接和名字
                    $work['workUrl'] = '/poem/' . $work['id'];
                    $work['workName'] = $work['title'];
                    $work['publish_time'] = $this->transformTime($work['created_at']);
                    return collection($work)
                        ->only('workUrl', 'workName', 'publish_time');
                });
            };

            // 如果该用户有品鉴作品
            if ($user->has('appreciations')) {
                $user['appreciations'] = collection($user['appreciations'])->map(function ($work) {
                    // 设置作品的地址链接和名字
                    $work['workUrl'] = '/appreciation/' . $work['id'];
                    $work['workName'] = $work['title'];
                    $work['publish_time'] = $this->transformTime($work['created_at']);
                    return collection($work)
                        ->only('workUrl', 'workName', 'publish_time');
                });
            };

            // 若有评论,则格式化它
            if ($model->has('comments')) {
                $model['comments'] = $this->transformComment($model['comments']);
            }

            // 获取分类名
            $model['category'] = \App\Http\Frontend\Models\Category::withTrashed()->find($model['category_id'])->name;

            // 格式化该诗文下的品鉴
            if ($type === 'poem' && $model->has('appreciations')) {
                // 格式化品鉴集合
                $model['appreciations'] = $this->transformModels(collect($model['appreciations']), 'appreciation', true);
                // 已登录用户是否品鉴过该诗文
                if (check()) {
                    $model['appreciated'] = false;
                    foreach ($model['appreciations'] as $appreciation) {
                        if ($appreciation['user_id'] === id()) {
                            $model['appreciated'] = true;
                            break;
                        }
                    }
                }
            }

            // 格式化品鉴的源诗文
            if ($type === 'appreciation' && $model->has('poem') && $model['poem']) {
                $model['poem'] = $this->transformModel(collect($model['poem']), 'poem', true);
            }
        }

        // 设置作品地址链接
        if ($type === 'poem') {
            $model->prepend('/poem/' . $model['id'], 'poemUrl');
        } elseif ($type === 'appreciation') {
            $model->prepend('/appreciation/' . $model['id'], 'appreciationUrl');
        }

        return $model
            ->prepend($user['profileUrl'], 'profileUrl')// 设置作者个人主页地址
            ->prepend($user['nickname'], 'authorName')// 设置作者昵称
            ->merge(['user' => $user]);
    }

    /**
     * 格式化模型集合
     *
     * @param $models
     * @param string $type
     * @param bool $isRepeat
     * @return mixed
     */
    public function transformModels($models, $type = 'poem', $isRepeat = false)
    {
        // 如果用户已经登录
        if (check()) {
            return $models->map(function ($item) use ($type, $isRepeat) {
                $data = $this->transformModel($item, $type, $isRepeat);
                // 若不是重复格式化模型
                if (!$isRepeat) {
                    // 点赞状态
                    $data['voted'] = $item->voted($id = id());
                    // 收藏状态
                    $data['favored'] = $item->favored($id);
                }
                return $data;
            });
        }

        return $models->map(function ($item) use ($type) {
            return $this->transformModel($item, $type);
        });
    }

    /**
     * 切换动作状态
     *
     * @param $id
     * @param Model $model
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function toggleAction($id, Model $model)
    {
        // 动作字段 与 返回信息
        $field = null;
        $info = null;
        // 查询指定ID的模型信息
        $data = $this->find($id);
        // 如果 model 没有相关用户的数据,则新建之。反之,则返回该模型
        $model = $model->firstOrCreate(['user_id' => id()]);
        // attached 与 detached 数组
        if ($model instanceof \App\Http\Frontend\Models\Vote) {
            // 点赞模型
            $toggle = $data->toggleVote($model->id);
            $field = 'likes_count';
            $info = 'voted';
        } elseif ($model instanceof \App\Http\Frontend\Models\Favorite) {
            // 收藏模型
            $toggle = $data->toggleFavorite($model->id);
            $field = 'favorites_count';
            $info = 'favored';
        }
        // 如果是 attached 行为,则给该目标的总数 +1
        if (!empty($toggle['attached'])) {
            $data->increment($field);
            (new \App\Http\Frontend\Models\User())->find(id())->increment($field);
            return $this->respondWith([$info => true]);
        }
        // 反之则 -1
        $data->decrement($field);
        (new \App\Http\Frontend\Models\User())->find(id())->decrement($field);
        return $this->respondWith([$info => false]);
    }

    /**
     * 获取动作状态
     *
     * @param $id
     * @param $action
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function getActionStatus($id, $action)
    {
        $value = null;
        if ($action === 'voted') {   // 点赞
            $value = $this->find($id)->voted(id());
        } elseif ($action === 'favored') {     // 收藏
            $value = $this->find($id)->favored(id());
        } elseif ($action === 'rated') {   //评分
            $rating = collection($this->find($id)->rated(id()))
                ->only(['rating_1', 'rating_2', 'rating_3', 'rating_4', 'rating_5'])
                ->filter(function ($item) {
                    return !!$item;
                })
                ->toArray();
            $value = rating($rating);
        }
        return $this->respondWith([$action => $value]);
    }

    /**
     * 根据指定类型获取模型名
     *
     * @param $type
     * @return string
     */
    public function getModelNameFormType($type)
    {
        if ($type === 'poem') {
            return 'App\Http\Frontend\Models\Poem';
        } elseif ($type === 'appreciation') {
            return 'App\Http\Frontend\Models\Appreciation';
        }
    }

    /**
     * 标准化模型
     *
     * @param $id
     * @param $name
     * @param string $type
     */
    public function normalizeModelCount($id, $name, $type = 'comments_count')
    {
        $model = null;
        if ($name === 'App\Http\Frontend\Models\Poem') {
            // 如果该模型为诗文模型,则该诗文模型的评论总数 +1
            $model = (new \App\Http\Frontend\Models\Poem());
        } elseif ($name === 'App\Http\Frontend\Models\Appreciation') {
            $model = (new \App\Http\Frontend\Models\Appreciation());
        }

        if ($model) {
            (new \App\Http\Frontend\Models\User())->find(id())->increment($type);
            $model->find($id)->increment($type);
        }
    }

    /**
     * 获取指定模型的评分
     *
     * @param $model
     * @return float
     */
    public function getRatingsByModel($model)
    {
        // 获取评分总数
        $ratings = collection($model->ratings)->map(function ($item) {
            return collection($item)
                ->only(['rating_1', 'rating_2', 'rating_3', 'rating_4', 'rating_5'])
                ->filter(function ($score) {
                    return !!$score;
                });
        });
        // 获取 四舍五入小数点后一位 的平均分值
        $score = $ratings->avg(function ($item) {
            // 格式化评分
            return rating($item->toArray());
        });
        return round($score, 1);
    }

}