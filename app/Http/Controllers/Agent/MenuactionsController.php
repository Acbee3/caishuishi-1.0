<?php
/**
 * Created by PhpStorm V.2018.
 * User: Administrator - Newsboy9248@163.com
 * Date: 2018/6/1 - 15:51
 */

namespace App\Http\Controllers\Agent;

use App\Models\Common;
use App\Models\Priv;
use App\Models\Menus;
use App\Models\Roles;
use App\Models\Menuactions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class MenuactionsController extends Controller
{
    public function index(Request $request)
    {
        // 判断权限与错误提示处理
        $can = Priv::CFS_Priv('menuactions_manage');
        if(!$can){return redirect()->route('forbidden');}

        $data = DB::table("menu_actions")->orderBy('id', 'Desc')->paginate(15);

        return view("agent.menuactions.index",[
            'data' => $data,
            'request' => $request
        ]);
    }

    public function create(Request $request)
    {
        // 判断权限与错误提示处理
        $can = Priv::CFS_Priv('menuactions_add');
        if(!$can){return redirect()->route('forbidden');}

        if (Common::isPost($request)){

            $this->validate($request,
                [
                    "action_name" => 'required|string',
                    "action_route" => 'required|string',
                    "status" => 'required',
                ],
                [
                    "action_name.required" => '链接名称必填！',
                    //"action_name.unique:menu_actions" => '链接名称已经存在！',
                    "action_route.required" => '链接路由必填！',
                    //"action_route.unique:menu_actions" => '链接路由已经存在！',
                    "status.required" => '启用状态必选！'
                ]
            );

            $model = new Menuactions();
            $model->action_name = $request->action_name;
            $model->action_route = $request->action_route;
            $model->status = $request->status;

            $model->menu_id = $request->menu_id;
            $model->parent_id = $request->parent_id;

            $roleids = $request->role_ids;
            $roleids = @join(",", $roleids);
            $model->role_ids = $roleids;

            if($model->save()){
                // 保存成功、记录日志、跳转处理
                return redirect()->route('agent.menuactions');
            }
        }

        // 菜单位置列表
        $menus = DB::table("menus")->whereRaw('status = "yes"' )->orderBy('id', 'Asc')->get();

        $rolelist = Menus::get_roles_list();

        $menu_actions = DB::table("menu_actions")->whereRaw('status = "yes"' )->orderBy('id', 'Asc')->get();

        return view("agent.menuactions.create",['menus'=>$menus, 'rolelist'=>$rolelist, 'menu_actions'=>$menu_actions]);
    }


    public function edit(Request $request)
    {
        // 判断权限与错误提示处理
        $can = Priv::CFS_Priv('menuactions_edit');
        if(!$can){return redirect()->route('forbidden');}

        $id = $request->id;
        $model = Menuactions::find($id);

        // 临时使用，后续结合前端js处理优化成选中状态   start
        $ids = $model->role_ids;
        if(!empty($ids)){
            $ids = explode(',',$ids);
            $arr = '';
            foreach($ids as $key=>$v){
                $agent_id = Roles::find($v)->role_name;
                $arr .= $agent_id.',';
            }
            $role_names = $arr;
        }else{
            $role_names = '未选择角色';
        }

        //end

        if (Common::isPost($request)){
            $this->validate($request,
                [
                    "action_name" => 'required|string',
                    "action_route" => 'required|string',
                    "status" => 'required',
                ],
                [
                    "action_name.required" => '链接名称必填！',
                    "action_route.required" => '链接路由必填！',
                    "status.required" => '启用状态必选！',
                ]
            );

            $model->action_name = $request->action_name;
            $model->action_route = $request->action_route;
            $model->status = $request->status;

            $model->menu_id = $request->menu_id;

            $model->parent_id = $request->parent_id;

            $roleids = $request->role_ids;
            $roleids = @join(",", $roleids);
            $model->role_ids = $roleids;

            $updated_at = now()->toDateTimeString();
            $model->updated_at = $updated_at;

            if($model->save()){

                return redirect()->route('agent.menuactions');
            }
        }

        // 菜单位置列表
        $menus = DB::table("menus")->whereRaw('status = "yes"' )->orderBy('id', 'Asc')->get();

        $rolelist = Menus::get_roles_list();

        $menu_actions = DB::table("menu_actions")->whereRaw('status = "yes"' )->orderBy('id', 'Asc')->get();

        return view("agent.menuactions.edit",['model'=>$model, 'menus'=>$menus, 'rolelist'=>$rolelist, 'role_names'=>$role_names, 'menu_actions'=>$menu_actions]);
    }

}