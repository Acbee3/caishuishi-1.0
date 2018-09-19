<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    use AuthenticatesUsers;
    protected $redirectTo = '/admin/index/index';

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function login(Request $request)
    {
        try {

            /*if(Session::get('milkcaptcha') !== $request->input('code')) {
                throw new \Exception("验证码错误");
            }*/

            $return = User::apiLogin($request);
            if ($return['result'] == "error") {
                throw new \Exception($return['message']);
            }

            $userInfo = User::getUserInfo($return['data']['token']);
            if ($userInfo['result'] == "error") {
                throw new \Exception($return['message']);
            }

            if ($userInfo["data"]["user"]['is_admin']['cfs'] != true) {
                throw new \Exception("账号不能登陆后台！");
            }

            $user = User::updateUser($userInfo['data']['user'], $return['data']['token']);
            if ($user['result'] == "error") {
                throw new \Exception($return['message']);
            }

            Auth::guard('admin')->loginUsingId($user['data']['id']);
//            Auth::loginUsingId($user['data']['id']);
            return redirect($this->redirectTo);

        } catch (\Exception $e) {
            return redirect()->refresh()->withErrors($e->getMessage())->withInput();
        }

        /*$data = [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'form_params' => $body
        ];

        $client = new Client();
        $res = $client->post('http://'.env("UC_DOMAIN").'/api/login', $data);

        dd($res->getBody()->getContents());


        if(Session::get('milkcaptcha') !== $request->input('code')) {
            return redirect()->refresh()->withErrors('验证码错误')->withInput();
        }

        $this->validateLogin($request);


        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);*/
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ],
            [
                "name.required" => '用户名必填！',
                'password.required' => '密码必填！',
            ]);
    }

    /**
     * 用户登录系统页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loginForm(Request $request)
    {
        return view("admin.login.login");
    }


    public function Logout(Request $request)
    {
        Auth::guard('admin')->logout();
        //request()->session()->flush();
        //request()->session()->regenerate();
        return redirect('/admin/login/login');
    }


    /**
     * 自定义认证驱动
     * @return [type] [description]
     */
    protected function guard()
    {
        return auth()->guard('admin');
    }
}
