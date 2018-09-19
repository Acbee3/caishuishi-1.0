<?php
/**
 * Created by PhpStorm V.2018.
 * User: Administrator - Newsboy9248@163.com
 * Date: 2018/5/29 - 10:30
 */

namespace App\Http\Controllers\Agent;

use App\Models\Common;
use App\Models\Roles;
use App\Models\Rolelists;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Priv;

class RolesController extends Controller
{
    public function index(Request $request)
    {
        // 判断权限与错误提示处理
        $can = Priv::CFS_Priv('sysrole_manage');
        if(!$can){return redirect()->route('forbidden');}

        //$roles = Roles::All();
        $roles = DB::table("roles")->orderBy('id', 'Desc')->paginate(15);

        return view("agent.roles.index",[
            'data' => $roles,
            'request' => $request
        ]);
    }

    public function create(Request $request)
    {
        // 判断权限与错误提示处理
        $can = Priv::CFS_Priv('sysrole_add');
        if(!$can){return redirect()->route('forbidden');}

        if (Common::isPost($request)){

            $this->validate($request,
                [
                    "role_name" => 'required|string|unique:roles',
                    "status" => 'required',
                ],
                [
                    "role_name.required" => '角色必填！',
                    "role_name.unique:roles" => '角色已经存在！',
                    "status.required" => '启用状态必选！'
                ]
            );

            $model = new Roles();
            $model->role_name = $request->role_name;
            $model->status = $request->status;
            $model->role_desc = $request->role_desc;

            $model->add_by = 'admin';

            // 设置权限
            $set_actions = $request->actions;
            $set_actions = @join(",", $set_actions);
            if(strpos($set_actions,'all') !==false){
                $set_actions = 'all';
            }
            $model->role_list = $set_actions;

            if($model->save()){
                // 保存成功、记录日志、跳转处理
                return redirect()->route('agent.roles');
            }
        }

        // 取权限列表
        $id = '';
        $actionlist = Common::get_rolelistsArr($id);

        return view("agent.roles.create", ['actionlist' => $actionlist]);
    }


    public function edit(Request $request)
    {
        // 判断权限与错误提示处理
        $can = Priv::CFS_Priv('sysrole_edit');
        if(!$can){return redirect()->route('forbidden');}

        $id = $request->id;
        $model = Roles::find($id);

        if (Common::isPost($request)){
            $model->role_name = $request->role_name;
            $model->status = $request->status;
            $model->role_desc = $request->role_desc;

            $updated_at = now()->toDateTimeString();
            $model->updated_at = $updated_at;

            // 设置权限
            $set_actions = $request->actions;
            $set_actions = @join(",", $set_actions);
            if(strpos($set_actions,'all') !==false){
                $set_actions = 'all';
            }
            $model->role_list = $set_actions;

            if($model->save()){
                return redirect()->route('agent.roles');
            }
        }

        // 取权限列表
        $actionlist = Common::get_rolelistsArr($id);
        $role_list = $request->role_list;

        return view("agent.roles.edit",['model'=>$model, 'actionlist' => $actionlist, 'role_list' => $role_list]);
    }
}