<?php
/**
 * Created by PhpStorm V.2018.
 * User: Administrator - Newsboy9248@163.com
 * Date: 2018/5/29 - 10:30
 */

namespace App\Http\Controllers\Agent;

use App\Models\Common;
use App\Models\Users;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Priv;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        // 判断权限与错误提示处理
        $can = Priv::CFS_Priv('sysuser_manage');
        if(!$can){return redirect()->route('forbidden');}

        // 调用分组信息 检索
        $roles = DB::table('roles')->where('status','=','yes')->get();

        $data = User::search_un_usergroup($request);
        //$data = User::search($request);

        return view("agent.users.index",[
            'data' => $data,
            'request' => $request,
            'roles' => $roles
        ]);
    }


    public function create(Request $request)
    {
        // 判断权限与错误提示处理
        $can = Priv::CFS_Priv('sysuser_add');
        if(!$can){return redirect()->route('forbidden');}

        if (Common::isPost($request)){

            $this->validate($request,
                [
                    "name" => 'required|string',
                    "status" => 'required',
                    "email" => 'required|email',
                ],
                [
                    "name.required" => '用户名必填！',
                    "email.required" => '邮件必填！',
                    "status.required" => '启用状态必选！'
                ]
            );

            $model = new Users();
            $model->name = $request->name;
            $model->password = bcrypt($request->password);
            $model->role_id = $request->role_id;
            $model->status = $request->status;
            $model->email = $request->email;

            if($model->save()){
                // 保存成功、记录日志、跳转处理
                return redirect()->route('agent.users');
            }
        }

        // 角色、用户组列表
        $roles = DB::table("roles")->whereRaw('status = "yes"' )->orderBy('id', 'Asc')->get();

        return view("agent.users.create",['roles'=>$roles]);
    }


    public function edit(Request $request)
    {
        // 判断权限与错误提示处理
        $can = Priv::CFS_Priv('sysuser_edit');
        if(!$can){return redirect()->route('forbidden');}

        $id = $request->id;
        $model = User::find($id);

        if (Common::isPost($request)){
            $model->name = $request->name;
            $model->role_id = $request->role_id;

            $updated_at = now()->toDateTimeString();
            $model->updated_at = $updated_at;
            $model->status = $request->status;

            if($model->save()){
                return redirect()->route('agent.users');
            }
        }

        // 角色、用户组列表
        $roles = DB::table("roles")->whereRaw('status = "yes"' )->orderBy('id', 'Asc')->get();

        return view("agent.users.edit",['model'=>$model, 'roles'=>$roles]);
    }
}