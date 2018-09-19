<?php

namespace App\Http\Controllers\Agent;

use App\Entity\BusinessDataConfig\BusinessConfig;
use App\Entity\Fund;
use App\Entity\Period;
use App\Http\Controllers\Controller;
use App\Models\Common;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LoginController extends Controller
{

    use AuthenticatesUsers;
    protected $redirectTo = '/agent/system';

    public function test()
    {
    }

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

            /*if($userInfo["data"]["user"]['is_admin']['cfs'] != true){
                throw new \Exception("账号不能登陆后台！");
            }*/

            $user = User::updateUser($userInfo['data']['user'], $return['data']['token']);
            if ($user['result'] == "error") {
                throw new \Exception($return['message']);
            }
//            Auth::loginUsingId($user['data']['id']);
            Auth::guard('agent')->loginUsingId($user['data']['id']);
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
     * 用户登录系统页面 旧版
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
//    public function loginForm2()
//    {
//        return view("agent.login.login");
//    }

    /**
     * 用户登录系统页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loginForm()
    {
        return view("agent.login.login2");
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function Logout()
    {
        //$this->guard('agent')->logout();
        Common::loginAuth()->logout();
        //request()->session()->flush();
        //request()->session()->regenerate();
        return redirect('/agent/login/login');
    }

    /**
     * 自定义认证驱动
     * @return mixed [type] [description]
     */
    protected function guard()
    {
        return auth()->guard('agent');
    }
}
