<?php

namespace App;

use App\Models\Common;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    const LEVEL_1 = 1;
    const LEVEL_2 = 2;
    const LEVEL_3 = 3;

    const STATUS_YES = 'yes';
    const STATUS_NO = 'no';

    public static $levelLabels = [
        self::LEVEL_1 => '管理员',
        self::LEVEL_2 => '代账公司',
        self::LEVEL_3 => '企业主',
    ];

    public static $statusLabels = [
        self::STATUS_YES => '正常',
        self::STATUS_NO => '禁用',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'email', 'phone', 'job_number', 'level', 'agent_id', 'company_id', 'uc_token', 'department_id', 'true_name', 'role_id'
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
     * UC中心登录认证
     * @param $request
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function apiLogin($request)
    {
        $client = new \GuzzleHttp\Client();

        $url = 'http://' . env("UC_DOMAIN") . '/api/login';
        $options['headers'] = ['Accept' => 'application/json'];

        $params["username"] = $request->phone;
        $params["password"] = $request->password;

        if ($request) {
            $options['form_params'] = $params;
        }

        $res = $client->request('post', $url, $options);
        $status = $res->getStatusCode();
        if ($status != 200) {
            return ['result' => 'error', 'message' => '登录失败，请重试！', 'data' => []];
        }
        $body = json_decode($res->getBody()->getContents(), true);
        return $body;
    }

    /**
     * 获取用户信息
     * @param $token
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getUserInfo($token)
    {
        $client = new \GuzzleHttp\Client();

        $url = 'http://' . env("UC_DOMAIN") . '/api/get-details';
        $options['headers'] = ['Accept' => 'application/json'];
        $options['headers'] = ['Authorization' => 'Bearer ' . $token];

        $res = $client->request('post', $url, $options);
        $status = $res->getStatusCode();

        if ($status != 200) {
            return ['result' => 'error', 'message' => '登录失败，请重试！', 'data' => []];
        }
        $body = json_decode($res->getBody()->getContents(), true);

        return $body;

    }


    /**
     * 获取用户信息
     * @param $token
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function updateUser(array $user, $token)
    {

        $data = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'job_number' => $user['job_number'],
            'level' => self::LEVEL_1,
            'uc_token' => $token,
            'role_id' => 1,
        ];


        $result = User::updateOrCreate(['name' => $user['name']], $data);
        if ($result) {
            return ['result' => 'success', 'data' => ['id' => $result->id]];
        }

        return ['result' => 'error', 'message' => "用户同步失败！", 'data' => []];
    }


    /**
     * 去UC用户中心注册用户
     * @param array $user
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function apiRegister(Array $user)
    {
        //$token = Auth::user()->uc_token;
        $token = Common::loginUser()->uc_token;
        $client = new \GuzzleHttp\Client();

        $url = 'http://' . env("UC_DOMAIN") . '/api/register';
        $options['headers'] = ['Accept' => 'application/json'];
        $options['headers'] = ['Authorization' => 'Bearer ' . $token];

        $params["name"] = $user['name'];
        $params["phone"] = $user['phone'];
        $params["password"] = $user['password'];
        $params["level"] = $user['level'];
        $params["agent_id"] = $user['agent_id'];

        if ($params) {
            $options['form_params'] = $params;
        }

        $res = $client->request('post', $url, $options);
        $status = $res->getStatusCode();

        if ($status != 200) {
            throw new \Exception("注册失败!");
            //return ['result' => 'error', 'message' => '注册失败！', 'data' => []];
        }
        $body = json_decode($res->getBody()->getContents(), true);
        return $body;
    }

    /**
     *
     * @param array $user
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function apiEditUser(Array $user)
    {
        //$token = Auth::user()->uc_token;
        $token = Common::loginUser()->uc_token;
        $client = new \GuzzleHttp\Client();

        $url = 'http://' . env("UC_DOMAIN") . '/api/edit-user';
        $options['headers'] = ['Accept' => 'application/json'];
        $options['headers'] = ['Authorization' => 'Bearer ' . $token];

        $params["id"] = $user['id'];
        $params["phone"] = $user['phone'];
        $params["password"] = $user['password'];

        if ($params) {
            $options['form_params'] = $params;
        }

        $res = $client->request('post', $url, $options);
        $status = $res->getStatusCode();

        if ($status != 200) {
            throw new \Exception("注册失败!");
        }

        $body = json_decode($res->getBody()->getContents(), true);

        return $body;

    }

    /**
     * 创建代账公司人员-本地数据库
     * @param array $user
     * @return mixed
     */
    public static function createUser(Array $user)
    {
        $data = [
            'id' => $user['id'],
            'name' => $user['name'],
            'phone' => $user['phone'],
            'level' => self::LEVEL_2,
            'agent_id' => $user['agent_id'],
        ];
        if (!empty($user['role_id'])) {
            $data['role_id'] = $user['role_id'];
        }
        if (!empty($user['department_id'])) {
            $data['department_id'] = $user['department_id'];
        }
        if (!empty($user['true_name'])) {
            $data['true_name'] = $user['true_name'];
        }

        $result = User::updateOrCreate(['id' => $user['id']], $data);
        return $result;
    }

    /**
     * 用户列表
     * @param $request
     * @param int $pageSize
     * @return mixed
     */
    public static function search($request, $pageSize = 10)
    {
        $user = DB::table("users")->orderBy('id', 'DESC');

        if (!empty($request->true_name)) {
            $user->where('true_name', 'like', '%' . $request->true_name . '%');
        }

        if (!empty($request->name)) {
            $user->where('name', 'like', '%' . $request->name . '%');
        }

        if (!empty($request->job_number)) {
            $user->where('job_number', 'like', '%' . $request->job_number . '%');
        }

        if (!empty($request->phone)) {
            $user->where('phone', 'like', '%' . $request->phone . '%');
        }

        if (!empty($request->login_at_start)) {
            $user->where('login_at', ">=", $request->login_at_start);
        }

        if (!empty($request->login_at_end)) {
            $user->where('login_at', "<=", $request->login_at_end);
        }

        if (!empty($request->created_at_start)) {
            $user->where('created_at', ">=", $request->created_at_start);
        }

        if (!empty($request->created_at_end)) {
            $user->where('created_at', "<=", $request->created_at_end);
        }

        if (!empty($request->status)) {
            $user->where('status', $request->status);
        }

        if (!empty($request->agent_id)) {
            $user->where('agent_id', $request->agent_id);
        }

        return $user->paginate($pageSize);

    }

    /**
     * 用户列表  users, usergroup 联表
     * @param $request
     * @param int $pageSize
     * @return mixed
     */
    public static function search_un_usergroup($request, $pageSize = 10)
    {
        $user = DB::table("users as u")->select('u.*', 'rs.id as rs_id', 'rs.role_name as rs_name', 'rs.role_list as rs_list', 'at.name as at_name')->leftjoin('roles as rs', 'u.role_id', '=', 'rs.id')->leftjoin('agent as at', 'u.agent_id', '=', 'at.id')->orderBy('u.id', 'DESC');

        if (!empty($request->name)) {
            $user->where('u.name', 'like', '%' . $request->name . '%');
        }

        if (!empty($request->job_number)) {
            $user->where('u.job_number', 'like', '%' . $request->job_number . '%');
        }

        if (!empty($request->phone)) {
            $user->where('u.phone', 'like', '%' . $request->phone . '%');
        }

        if (!empty($request->login_at_start)) {
            $user->where('u.login_at', ">=", $request->login_at_start);
        }

        if (!empty($request->login_at_end)) {
            $user->where('u.login_at', "<=", $request->login_at_end);
        }

        if (!empty($request->created_at_start)) {
            $user->where('u.created_at', ">=", $request->created_at_start);
        }

        if (!empty($request->created_at_end)) {
            $user->where('u.created_at', "<=", $request->created_at_end);
        }

        if (!empty($request->ug_id)) {
            $user->where('rs.id', "=", $request->rs_id);
        }

        if (!empty($request->status)) {
            $user->where('u.status', $request->status);
        }

        return $user->paginate($pageSize);

    }
}
