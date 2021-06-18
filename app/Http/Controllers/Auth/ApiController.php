<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use \Validator;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:airlock')->except('login', 'register');
    }

    protected function username()
    {
        return 'email';
    }

//    注册用户
    public function register(Request $request)
    {
//        验证用户信息是否符合格式
        $data = $this->validator($request->all())->validate();
//        添加用户
        $user = $this->create($data);
//        响应返回token
        $token = $this->issue_token($user);
//        因为注册是第一次登录 并没有设置头像 直接返回空就好
        return ['data'=>$user,$token,'avatar'=>'','status'=>200];
    }

//    验证规则方法
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users',],
            'name' => ['required', 'string', 'max:255', 'unique:users',],
            'password' => ['required', 'string', 'min:8', 'confirmed',],
        ]);
    }
//    添加用户方法
    protected function create(array $data)
    {
        return User::forceCreate([
            'email' => $data['email'],
            'name' => $data['name'],
//            加密密码
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ]);
    }
//    用户退出
    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
//
        return ['message' => __('auth.sign_out_successfully')];
    }

    public function revoke_all_tokens()
    {
        if (!auth()->user()->tokenCan('*')) {
            abort(403, __('auth.forbidden'));
        }
        auth()->user()->tokens()->delete();

        return ['message' => __('auth.sign_out_successfully')];
    }

//    用户登录
    public function login()
    {
        $user = User::where($this->username(), request($this->username()))
            ->firstOrFail();

        if (!password_verify(request('password'), $user->password)) {
            abort(403, __('auth.failed'));
        }
        //        响应返回token
        $token = $this->issue_token($user);
//        因为注册是第一次登录 并没有设置头像 直接返回空就好
        return ['data'=>$user,$token,'avatar'=>'','status'=>200];
    }

    /**
     * @param $user
     * @return array
     */
    protected function issue_token($user): array
    {
        return [
            'token' => $user->createToken('general user', ['general_user'])->plainTextToken
        ];
    }

}
