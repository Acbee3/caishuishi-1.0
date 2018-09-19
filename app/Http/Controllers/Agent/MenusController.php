<?php
/**
 * Created by PhpStorm V.2018.
 * User: Administrator - Newsboy9248@163.com
 * Date: 2018/6/1 - 15:44
 */

namespace App\Http\Controllers\Agent;

use App\Models\Common;
use App\Models\Priv;
use App\Models\Menus;
use App\Models\Roles;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class MenusController extends Controller
{
    public function index(Request $request)
    {
        // 判断权限与错误提示处理
        $can = Priv::CFS_Priv('menus_manage');
        if(!$can){return redirect()->route('forbidden');}

        $data = DB::table("menus")->orderBy('id', 'Desc')->paginate(15);

        return view("agent.menus.index",[
            'data' => $data,
            'request' => $request
        ]);
    }

    public function create(Request $request)
    {
        // 判断权限与错误提示处理
        $can = Priv::CFS_Priv('menus_add');
        if(!$can){return redirect()->route('forbidden');}

        if (Common::isPost($request)){

            $this->validate($request,
                [
                    "menu_name" => 'required|string|unique:menus',
                    "menu_code" => 'required|string|unique:menus',
                    "status" => 'required',
                ],
                [
                    "menu_name.required" => '菜单名称必填！',
                    "menu_name.unique:menus" => '菜单名称已经存在！',
                    "menu_code.required" => '菜单代码必填！',
                    "menu_code.unique:menus" => '菜单代码已经存在！',
                    "status.required" => '启用状态必选！'
                ]
            );

            $model = new Menus();
            $model->menu_name = $request->menu_name;
            $model->menu_code = $request->menu_code;
            $model->status = $request->status;

            $roleids = $request->role_ids;
            $roleids = @join(",", $roleids);
            $model->role_ids = $roleids;

            if($model->save()){
                // 保存成功、记录日志、跳转处理
                return redirect()->route('agent.menus');
            }
        }

        $rolelist = Menus::get_roles_list();

        return view("agent.menus.create",['rolelist' => $rolelist]);
    }


    public function edit(Request $request)
    {
        // 判断权限与错误提示处理
        $can = Priv::CFS_Priv('menus_edit');
        if(!$can){return redirect()->route('forbidden');}

        $id = $request->id;
        $model = Menus::find($id);

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
                    "menu_name" => 'required|string',
                    "menu_code" => 'required|string',
                    "status" => 'required',
                ],
                [
                    "menu_name.required" => '菜单名称必填！',
                    "menu_code.required" => '菜单代码必填！',
                    "status.required" => '启用状态必选！',
                ]
            );

            $model->menu_name = $request->menu_name;
            $model->menu_code = $request->menu_code;
            $model->status = $request->status;

            $roleids = $request->role_ids;
            $roleids = @join(",", $roleids);
            $model->role_ids = $roleids;

            $updated_at = now()->toDateTimeString();
            $model->updated_at = $updated_at;

            if($model->save()){

                return redirect()->route('agent.menus');
            }
        }

        $rolelist = Menus::get_roles_list();

        return view("agent.menus.edit",['model'=>$model, 'rolelist' => $rolelist, 'role_names'=>$role_names]);

    }

}