<?php

namespace App\Repositories\Eloquent;

use App\Mail\PasswordShipped;
use App\Mail\RegisterShipped;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Events\AccessTokenCreated;
use Laravel\Passport\Events\RefreshTokenCreated;
use Overtrue\LaravelSocialite\Socialite;

/**
 * User Repository
 *
 * Class UserRepository
 * @package App\Repositories\Eloquent
 */
class UserRepository extends Repository
{

    protected $username;
    protected $http;

    public function __construct(Client $http, App $app)
    {
        parent::__construct($app);
        $this->http = $http;
    }

    /**
     * 指定模型名称
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Http\Frontend\Models\User';
    }

    /**
     * 获取用户分页数据
     *
     * @param string $query
     * @return mixed
     */
    public function index($query = '')
    {
        // 获取分页数据
        if (!$query) {
            $paginate = $this->model
                ->orderBy('created_at', 'desc')
                ->paginate();

            foreach ($paginate as $key => $value) {
                $user = collection($value)->merge(['profile' => $value['profile'], 'roles' => $value['roles']]);
                $paginate[$key] = $this->transformUser($user);
            }
        } else {
            // 获取模糊查询用户的Profile集合
            $users = (new \App\Http\Frontend\Models\Profile())
                ->where('nickname', 'like', "%$query%")
                ->get();

            // 若有查询参数
            $paginate = $this->model
                ->whereIn('id', $users->pluck('user_id'))
                ->orderBy('created_at', 'desc')
                ->paginate();

            foreach ($paginate as $key => $value) {
                $user = collection($value)->merge(['profile' => $value[$key], 'roles' => $value['roles']]);
                $paginate[$key] = $this->transformUser($user);
            }
        }

        return collection($paginate);
    }

    /**
     * 获取需要更新的用户的数据
     *
     * @param $user
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function edit($user)
    {
        $user = $this->model->with(['profile', 'roles'])->where('name', $user)->first();
        if (!$user) {
            return $this->errorNotFound();
        } else {
            return $this->transformUser($user);
        }
    }

    /**
     * 用户注册
     *
     * @param $request
     * @param bool $isBackend
     * @return mixed|null
     */
    public function register($request, $isBackend = false)
    {
        $user = $this->create([
            'name' => $request->name,
            'email' => $request->get('email', null),
            'mobile' => $request->get('mobile', null),
            'password' => bcrypt($request->password),
            'avatar' => 'http://images.dragonflyxd.com/default.png',
            'confirmation_token' => str_random(40)
        ]);
        // 若注册成功,则发送注册邮件
        // 若为后台添加用户,则不需要发送邮件
        if ($user && !$isBackend) {
            Mail::to($user)
                ->send(new RegisterShipped($user));
        } else if ($user && $isBackend) {
            // 若存在Roles
            if ($roles = $request['roles']) {
                $user->attachRoles($roles);
            }

            // 若为后台添加用户, 激活新注册的用户并初始化个人信息
            $user->is_active = $request->is_active;
            $user->save();
            (new \App\Http\Frontend\Models\Profile())->create([
                'user_id' => $user->id,
                'nickname' => $user->name
            ]);

        }

        return $this->respondWith(['registered' => !!$user]);
    }

    /**
     * 改变指定用户的激活状态
     *
     * @param $user
     * @param $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function updateUserAuth($user, $request)
    {
        $user = $this->findBy('name', $user);
        if ($result = !!$user) {
            // 更新用户的激活状态
            $user->is_active = $request->is_active ? 1 : 0;
            $user->save();
            // 更新的用户的Roles
            if ($roles = $request->roles) {
                $user->roles()->sync($roles);
            }
        }
        return $this->respondWith(['updated' => $result]);
    }

    /**
     * 验证邮箱
     *
     * @param $token
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function verifyRegister($token)
    {
        // 验证该 token 是否存在
        $user = $this->findBy('confirmation_token', $token);
        if (is_null($user)) {
            // 如果 token 失效或不存在,则直接返回
            return $this->respondWith(['registered' => false]);
        }
        // 激活该账户
        $user->is_active = 1;
        $user->confirmation_token = str_random(40);
        $user->save();
        // 初始化该用户的个人信息
        (new \App\Http\Frontend\Models\Profile())->create([
            'user_id' => $user->id,
            'nickname' => $user->name
        ]);
        return $this->respondWith(['registered' => true]);
    }

    /**
     * 用户登录
     *
     * @param $request
     * @param bool $isBackLogin 是否为后台登录
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function login($request, $isBackLogin = false)
    {
        // 判断用户使用什么字段登录
        $username = collect($request)->only(['name', 'mobile', 'email'])
            ->filter(function ($value, $key) {
                if ($value) {
                    $this->username = [$key => $value];
                }
                return !!$value;
            })
            ->toArray();
        // 获取用户账号激活状态
        $activeStatus = $this->findBy($name = key($username), current($username));
        if (!$activeStatus['is_active'] && $activeStatus[$name]) {  // 若账号未激活
            return $this->errorUnauthorized("邮箱未验证。");
        }
        // 合并验证数据
        $username = array_merge($username, ['password' => $request->password, 'is_active' => 1]);
        // 用户认证
        if (Auth::attempt($username, $request->isRemember)) {
            // 若为后台登录,不进行密码授权流程
            if ($isBackLogin) {
                return $this->respondWith(['login' => true]);
            }
            return $this->respondWith($this->oauth($username));
        }
        return $this->errorUnprocessableEntity("用户名或密码错误。");
    }

    /**
     * 跳转至社会化登录授权页面
     *
     * @param $type
     * @return mixed
     */
    public function socialiteRedirect($type)
    {
        return Socialite::driver($type)->stateless()->redirect();
    }

    /**
     * 社会化登录的回调
     *
     * @param $type
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function socialiteCallback($type)
    {
        // 获取该用户的基本个人信息
        $clientUser = Socialite::driver($type)->stateless()->user();
        // 判断该用户是否为第一次授权
        $user = $this->model->where(['social_type' => $type, 'social_id' => $clientUser->id])->first();

        // 每次都更换密码, 来获取明文密码,否则获取不了 access_token
        $password = str_random(16);

        // 若为第一次授权,则将其基本个人信息入库
        if (!$user) {
            // 查询其用户名是否已被注册,若已被注册,则用当前时间戳代替
            $isRegister = $this->findBy('name', $clientUser->name);
            $name = $isRegister ? time() : $clientUser->getName();

            // 抉择邮箱是否被注册
            $clientEmail = $clientUser->getEmail();
            $email = !!$this->findBy('email', $clientEmail) ? null : $clientEmail;
            $user = $this->create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
                'avatar' => $clientUser->getAvatar(),
                'confirmation_token' => str_random(40),
                'is_active' => 1,
                'social_id' => $clientUser->getId(),
                'social_type' => $type
            ]);
        } else {
            // 更换该用户的密码
            $user->password = bcrypt($password);
            $user->save();
        }

        $this->username = ['name' => $user->name];
        $user->password = $password;

        // 重定向至前台 key 页面
        $token = $this->oauth($user)['access_token'];
        return redirect("http://www.dragonflyxd.com/auth/key?token=$token");
    }

    /**
     * OAuth2 密码授权流程
     *
     * @param $user
     * @return mixed
     */
    private function oauth($user)
    {
        // 获取访问令牌
        $response = $this->http->post('http://www.dragonflyxd.com/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => config('services.oauth.password.client_id'),
                'client_secret' => config('services.oauth.password.client_secret'),
                'username' => $this->username,
                'password' => $user['password'],
                'scope' => ''
            ]
        ]);
        $access_token = array_get(json_decode((string)$response->getBody(), true), 'access_token');
        return array_merge($this->getUserByToken($access_token), ['access_token' => $access_token]);
    }

    /**
     * 通过密码授权令牌获取用户数据
     *
     * @param $accessToken
     * @return mixed
     */
    public function getUserByToken($accessToken)
    {
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ];
        $request = new \GuzzleHttp\Psr7\Request('GET', 'http://www.dragonflyxd.com/api/user', $headers);
        $response = $this->http->send($request);
        return json_decode((string)$response->getBody(), true);
    }

    /**
     * 获取用户个人信息
     *
     * @return mixed
     */
    public function getUserProfile()
    {
        return $this->transformUser($this->with('profile')->find(id()));
    }

    /**
     * 根据用户名查找用户
     *
     * @param $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($name)
    {
        return $this->transformUser($this->with('profile')->where('name', $name)->first())
            ?: $this->errorNotFound();
    }

    /**
     * 根据昵称查找用户
     *
     * @param $nickname
     * @return mixed
     */
    public function getUserByNickname($nickname)
    {
        if (!$nickname) return [];

        $users = (new \App\Http\Frontend\Models\Profile())
            ->with('user')
            ->where('nickname', 'like', "%$nickname%")
            ->get();

        return $users->map(function ($user) {
            $user['avatar'] = $user['user']['avatar'];
            return collection($user)->forget('user');
        });
    }

    /**
     * 更新用户头像
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse|mixed|string
     */
    public function changeAvatar($request)
    {
        // 访问七牛的磁盘
        $disk = Storage::disk('qiniu');
        // 获取上传图片
        $file = $request->file('avatar');
        // 设置存储图片名
        $fileName = md5(time() . id()) . '.' . $file->getClientOriginalExtension();
        // 存储图片
        if ($disk->putFileAs('avatars', $file, $fileName)) {
            // 若储存成功
            $domain = config('filesystems.disks.qiniu.domain');
            $avatar = 'http://' . $domain . '/avatars/' . $fileName;
            // 如果用户头像不为初识头像,则删除之前的头像
            if (user()->avatar !== 'http://' . $domain . '/default.png') {
                $pattern = '/^http:\/\/' . $domain . '\//';
                $disk->delete(preg_replace($pattern, '', user()->avatar));
            }
            user()->avatar = $avatar;
            user()->save();
            return $this->respondWith([
                'avatar' => $avatar
            ]);
        };
        return $this->respondWith(['avatar' => false]);
    }

    /**
     * 更新用户个人信息
     *
     * @param $request
     * @param null $user
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function updateUserProfile($user = null, $request)
    {
        if ($user) {
            $userId = $this->findBy('name', $user)->id;
        } else {
            $userId = id() || id('web');
        }

        $result = (new \App\Http\Frontend\Models\Profile())
            ->firstOrCreate(['user_id' => $userId])
            ->update($request->all());

        return $this->respondWith(['updated' => !!$result]);
    }

    /**
     * 获取关注者列表
     *
     * @param $user
     * @return static
     */
    public function followers($user)
    {
        $followers = collection($this->findBy('name', $user)->followers);
        $own = user();
        return $followers->map(function ($item) use ($own) {
            $item = collection($item)->merge(['profile' => $item->profile]);

            // 获取关注状态
            $item['followed'] = check() ? $own->followed($item['id']) : false;

            return $this->transformUser($item, true);
        });
    }

    /**
     * 获取被关注者列表
     *
     * @param $user
     * @return static
     */
    public function followings($user)
    {
        $followings = collection($this->findBy('name', $user)->followings);
        $own = user();
        return $followings->map(function ($item) use ($own) {
            $item = collection($item)->merge(['profile' => $item->profile]);

            // 获取关注状态
            $item['followed'] = check() ? $own->followed($item['id']) : false;

            return $this->transformUser($item, true);
        });
    }

    /**
     * 获取指定用户的作品列表
     *
     * @param $user
     * @return mixed
     */
    public function works($user)
    {
        $user = $this->with('profile')->where('name', $user)->first();

        // 格式化诗文数据
        $poems = collect($user->poems)->map(function ($item) use ($user) {
            $poem = (new \App\Http\Frontend\Models\Poem())->with(['tags', 'comments.user.profile', 'comments' => function ($query) {
                $query->orderBy('comments.created_at', 'desc');
            }])->find($item['id']);

            $poem['user'] = $user;
            return $poem;
        });
        $work['poem'] = $this->transformModels($poems)
            ->sortByDesc('pageviews_count')
            ->values()
            ->all();

        // 格式化品鉴数据
        $appreciations = collect($user->appreciations)->map(function ($item) use ($user) {
            $appreciation = (new \App\Http\Frontend\Models\Appreciation())
                ->with(['user.profile', 'tags', 'poem.user.profile', 'comments.user.profile', 'comments' => function ($query) {
                    $query->orderBy('comments.created_at', 'desc');
                }])->find($item['id']);

            $appreciation['user'] = $user;
            return $appreciation;
        });
        $work['appreciation'] = $this->transformModels($appreciations, 'appreciation')
            ->sortByDesc('pageviews_count')
            ->values()
            ->all();

        $work['author'] = $this->transformUser($user);
        return $work;
    }

    /**
     * 切换用户关注
     *
     * @param $user
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function follow($user)
    {
        if (id() !== intval($user)) {
            // 关注者
            $follower = $this->find(id());
            $toggle = $follower->toggleFollow($user);
            // 如果是 attached 行为
            if (!empty($toggle['attached'])) {
                // 关注者 关注总数 +1
                $follower->increment('followers_count');
                // 被关注者 被关注总数 +1
                (new \App\Http\Frontend\Models\User())->find($user)->increment('followings_count');
                return $this->respondWith(['followed' => true]);
            }
            // 反之 -1
            $follower->decrement('followers_count');
            (new \App\Http\Frontend\Models\User())->find($user)->decrement('followings_count');
        }
        return $this->respondWith(['followed' => false]);
    }

    /**
     * 获取用户关注的状态
     *
     * @param $user
     * @return mixed
     */
    public function followed($user)
    {
        return $this->respondWith(['followed' => $this->find(id())->followed($user)]);
    }

    /**
     * 重置密码
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function resetPassword($request)
    {
        $rules = [
            'old_password' => ['required'],
            'password' => ['required', 'between:6,20', 'regex:/^[a-zA-Z0-9_-]{6,20}$/', 'confirmed'],
        ];
        $message = [
            'old_password.required' => '旧密码不能为空。',
            'password.required' => '新密码不能为空。',
            'password.between.string' => '新密码必须介于6-20个字符之间。',
            'password.regex' => '新密码格式不正确。',
            'password.confirmed' => '新密码两次输入不一致。'
        ];
        // 自定义验证数据
        $this->validate($request, $rules, $message);
        // 验证密码的正确性
        if (Hash::check($request->get('old_password'), user()->password)) {
            // 如果密码正确,修改原密码为新密码
            $this->update(['password' => bcrypt($request->get('password'))], id());
            return $this->respondWith(['reset' => true]);
        }
        return $this->respondWith(['reset' => false]);
    }

    /**
     * 发送重置密码的邮件
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function forgetPassword($request)
    {
        $rules = [
            'email' => ['required', 'max:50', 'regex:/^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/', 'exists:users,email'],
            'is_submit' => ['required']
        ];
        // 自定义验证数据
        $this->validate($request, $rules);
        $user = $this->findBy('email', $request->email);
        Mail::to($user)
            ->send(new PasswordShipped($user));
        return $this->respondWith(['forget' => true]);
    }

    /**
     * 验证重置密码邮箱
     *
     * @param $request
     * @param $token
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function verifyPassword($request, $token)
    {
        $rules = [
            'password' => ['required', 'between:6,20', 'regex:/^[a-zA-Z0-9_-]{6,20}$/', 'confirmed'],
        ];
        // 验证密码的正确性
        $this->validate($request, $rules);
        // 验证该 token 是否存在
        $user = $this->findBy('confirmation_token', $token);
        if (is_null($user)) {
            // 如果 token 失效或不存在,则直接返回
            return $this->respondWith(['verified' => false]);
        }
        // 重置用户的 confirmation_token 与 password
        $user->confirmation_token = str_random(40);
        $user->password = bcrypt($password = $request->password);
        $user->save();

        // 获取用户 access_token
        $this->username = ['name' => $user->name];
        $user->password = $password;
        $token = $this->oauth($user)['access_token'];

        return $this->respondWith(['verified' => true, 'access_token' => $token]);
    }

    /**
     * 用户退出登录
     *
     */
    public function logout()
    {
        // 获取 access_token
        $token = user()->token();
        // 获取 refresh_token
        $refresh_token = DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $token->id)
            ->first();
        // 删除已经 revoke 的令牌
        event(new AccessTokenCreated($token->id, $token->user_id, $token->client_id));
        event(new RefreshTokenCreated($refresh_token->id, $token->id));
        // revoke 用户注销前的令牌
        $token->revoke();
        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $token->id)
            ->update(['revoked' => true]);
        return $this->respondWith(['logout' => true]);
    }

}