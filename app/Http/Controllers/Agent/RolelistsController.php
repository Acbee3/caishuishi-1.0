<?php
/**
 * Created by PhpStorm V.2018.
 * User: Administrator - Newsboy9248@163.com
 * Date: 2018/5/29 - 16:10
 */

namespace App\Http\Controllers\Agent;

use App\Models\Common;
use App\Models\Rolelists;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Priv;

class RolelistsController extends Controller
{
    public function index(Request $request)
    {
        // 判断权限与错误提示处理
        $can = Priv::CFS_Priv('sysrolelist_manage');
        if(!$can){return redirect()->route('forbidden');}

        //$roles = Roles::All();
        $role_lists = DB::table("role_lists")->orderBy('id', 'Desc')->paginate(10);

        return view("agent.rolelists.index",[
            'data' => $role_lists,
            'request' => $request
        ]);
    }

    public function create(Request $request)
    {
        // 判断权限与错误提示处理
        $can = Priv::CFS_Priv('sysrolelist_add');
        if(!$can){return redirect()->route('forbidden');}

        if (Common::isPost($request)){

            $this->validate($request,
                [
                    "action_name" => 'required|string|unique:role_lists',
                    "action_code" => 'required|string|unique:role_lists',
                    "status" => 'required',
                ],
                [
                    "action_name.required" => '权限名称必填！',
                    "action_name.unique:role_lists" => '权限名称已经存在！',
                    "action_code.required" => '权限代码必填！',
                    "action_code.unique:role_lists" => '权限代码已经存在！',
                    "status.required" => '启用状态必选！'
                ]
            );

            $model = new Rolelists();
            $model->action_name = $request->action_name;
            $model->action_code = $request->action_code;
            $model->status = $request->status;

            if($model->save()){
                // 保存成功、记录日志、跳转处理
                return redirect()->route('agent.rolelists');
            }
        }

        return view("agent.rolelists.create");
    }


    public function edit(Request $request)
    {
        // 判断权限与错误提示处理
        $can = Priv::CFS_Priv('sysrolelist_edit');
        if(!$can){return redirect()->route('forbidden');}

        $id = $request->id;
        $model = Rolelists::find($id);

        if (Common::isPost($request)){
            $this->validate($request,
                [
                    "action_name" => 'required|string',
                    "action_code" => 'required|string',
                    "status" => 'required',
                    "sort_order" => 'required|numeric||between:1,99',
                ],
                [
                    "action_name.required" => '权限名称必填！',
                    "action_code.required" => '权限代码必填！',
                    "status.required" => '启用状态必选！',
                    "sort_order.required" => '排序必填！',
                    "sort_order.numeric" => '排序必须为数字！'
                ]
            );

            $model->action_name = $request->action_name;
            $model->action_code = $request->action_code;
            $model->status = $request->status;
            $model->sort_order = $request->sort_order;

            $updated_at = now()->toDateTimeString();
            $model->updated_at = $updated_at;

            if($model->save()){

                // 如果停用当前权限代码，处理 roles角色表里 role_list 相关的权限
                if( $request->status == 'no'){
                    $now_action_code = $request->action_code;
                    DB::table('roles')->update(['role_list' => DB::raw("REPLACE(role_list, '".$now_action_code."', '')")]);
                }

                return redirect()->route('agent.rolelists');
            }
        }

        return view("agent.rolelists.edit",['model'=>$model]);
    }

    public function addchild(Request $request)
    {
        $parent_id = $request->parent_id;

        $parent_row = Rolelists::find($parent_id);
        $parent_name = $parent_row->action_name;

        if (Common::isPost($request)){

            $this->validate($request,
                [
                    "action_name" => 'required|string|unique:role_lists',
                    "action_code" => 'required|string|unique:role_lists',
                    "status" => 'required',
                ],
                [
                    "action_name.required" => '权限名称必填！',
                    "action_name.unique:role_lists" => '权限名称已经存在！',
                    "action_code.required" => '权限代码必填！',
                    "action_code.unique:role_lists" => '权限代码已经存在！',
                    "status.required" => '启用状态必选！'
                ]
            );

            $model = new Rolelists();
            $model->action_name = $request->action_name;
            $model->action_code = $request->action_code;
            $model->status = $request->status;

            $model->parent_id = $request->parent_id;

            if($model->save()){
                // 保存成功、记录日志、跳转处理
                return redirect()->route('agent.rolelists');
            }
        }

        return view("agent.rolelists.addchild", ['parent_id'=>$parent_id, 'parent_name'=>$parent_name]);
    }


    /*
     *  生成权限列表session
     */
    public function createsession()
    {

        return redirect()->route('agent.rolelists');
    }

    /*
     *  更新权限列表session
     */
    public function updatesession()
    {

        return redirect()->route('agent.rolelists');

    }
}