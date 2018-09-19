<?php
/**
 * Created by PhpStorm V.2018.
 * User: Administrator - Newsboy9248@163.com
 * Date: 2018/6/12 - 17:26
 */

namespace App\Http\Controllers\Agent;

use App\Models\Common;
use App\Models\Agent;
use App\Models\Menuactions;
use App\Models\Menus;
use App\Models\Rolerelations;
use App\Models\Roles;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;

class RolerelationsController extends Controller
{
    /*public function __construct()
    {
        $this->middleware('auth');
    }*/

    public function index(Request $request)
    {
        $agent_id = Common::loginUser()->agent_id;

        // 右侧区域内容
        $role_id = 2;//默认进入后显示为代账管理员
        $main_data = Menus::get_menus_all_list($role_id);

        // 左侧菜单
        //$roles = DB::table("roles")->where('status', "yes" )->Where('add_by', "sys" )->orWhere('agent_id', '=', $agent_id)->orderBy('id', 'Asc')->get();

        $roles = DB::select('select * from roles where status = :status and ( add_by = :add_by or agent_id = :agent_id)', [':status'=>'yes',':add_by'=>'sys',':agent_id'=>$agent_id]);

        // 取代账公司名称
        $agent_row = Agent::find($agent_id);
        if($agent_row){
            $agent_name = $agent_row->name;
        }else{
            $agent_name = '';
        }


        return view("agent.rolerelations.index",[
            'menu_data' => $main_data,
            'roles' => $roles,
            'agent_name' => $agent_name,
            'id' => '2',
            'request' => $request
        ]);
    }

    /**
     * 代账公司管理员添加角色
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function addrole(Request $request)
    {
        $agent_id = Common::loginUser()->agent_id;

        if (Common::isPost($request)){

            $this->validate($request,
                [
                    "role_name" => 'required|string|min:6|max:20',
                ],
                [
                    "role_name.required" => '用户名必填！',
                ]
            );

            $model = new Roles();
            $model->role_name = $request->role_name;
            $model->add_by = 'agent';
            $model->agent_id = $agent_id;

            if($model->save()){
                return redirect()->route('agent.rolerelations');
            }
        }

        return view("agent.rolerelations.addrole");
    }

    /**
     * 代账公司管理员修改角色
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $id = $request->id;
        $model = Roles::find($id);

        // 只能修改当前代账公司管理员添加的角色，系统默认角色组不可编辑
        $agent_id = Common::loginUser()->agent_id;
        $agent_id_find = $model->agent_id;
        $add_by = $model->add_by;
        if($add_by == 'agent' && !empty($agent_id_find) && $agent_id_find == $agent_id){
            if (Common::isPost($request)){

                $this->validate($request,
                    [
                        "role_name" => 'required|string|min:6|max:20',
                    ],
                    [
                        "role_name.required" => '用户名必填！',
                    ]
                );

                $model->role_name = $request->role_name;

                $updated_at = now()->toDateTimeString();
                $model->updated_at = $updated_at;

                if($model->save()){
                    return redirect()->route('agent.rolerelations');
                }
            }

            return view("agent.rolerelations.edit",['model'=>$model]);
        }else{
            // 不可修改转入到角色权限列表
            return redirect()->route('agent.rolerelations');
        }
    }

    /**
     * 代账公司管理员删除角色
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function del(Request $request)
    {
        $id = $request->id;
        $model = Roles::find($id);

        // 有删除权限　且　此角色没有分配用户才可以删除
        $agent_id = Common::loginUser()->agent_id;
        $agent_id_find = $model->agent_id;
        $add_by = $model->add_by;

        $role_user_num = Users::where('agent_id', '=', $agent_id)->where('role_id', '=', $id)->count();

        if($add_by == 'agent' && !empty($agent_id_find) && $agent_id_find == $agent_id && $role_user_num == 0)
        {
            // 更改角色启用状态，删除操作由超级管理员执行比较好
            $data['status'] = 'no';
            DB::table('roles')->where('id',$id)->update($data);

            // 删除角色关系表　role_relations 相关数据(agent_id  role_id)
            DB::delete('delete from role_relations where agent_id = ? and role_id = ?', [$agent_id, $id]);

            // 更新授权关系表 auth_relations 中相关数据

            return redirect()->route('agent.rolerelations');
        }else{
            return redirect()->route('agent.rolerelations');
        }
    }

    /**
     * 系统设置　　修改会员权限列表页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function operation(Request $request)
    {
        $id = $request->id;

        $agent_id = Common::loginUser()->agent_id;

        // 取代账公司名称
        $agent_row = Agent::find($agent_id);
        if($agent_row){
            $agent_name = $agent_row->name;
        }else{
            $agent_name = '';
        }

        $model_roles = Roles::find($id);
        $agent_id_find = $model_roles->agent_id;

        if($id == 2 || $id == 3){
            // 右侧区域内容
            $main_data = Menus::get_menus_all_list($id);

            // 左侧菜单
            //$roles = DB::table("roles")->where('status', '=', "yes" )->where('add_by', '=', "sys" )->orWhere('agent_id', '=', $agent_id)->orderBy('id', 'Asc')->get();

            $roles = DB::select('select * from roles where status = :status and ( add_by = :add_by or agent_id = :agent_id)', [':status'=>'yes',':add_by'=>'sys',':agent_id'=>$agent_id]);

            return view("agent.rolerelations.index",[
                'menu_data' => $main_data,
                'roles' => $roles,
                'agent_name' => $agent_name,
                'id' => $id,
                'request' => $request
            ]);
        }else{
            if(!empty($agent_id_find) && $agent_id_find == $agent_id){
                // 右侧区域内容
                $main_data = Menus::get_menus_all_list($id);

                // 左侧菜单
                //$roles = DB::table("roles")->where('status', '=', "yes" )->where('add_by', '=', "sys" )->orWhere('agent_id', '=', $agent_id)->orderBy('id', 'Asc')->get();

                $roles = DB::select('select * from roles where status = :status and ( add_by = :add_by or agent_id = :agent_id)', [':status'=>'yes',':add_by'=>'sys',':agent_id'=>$agent_id]);

                return view("agent.rolerelations.index",[
                    'menu_data' => $main_data,
                    'roles' => $roles,
                    'agent_name' => $agent_name,
                    'id' => $id,
                    'request' => $request
                ]);
            }else{
                return redirect()->route('agent.rolerelations');
            }

        }
    }

    /**
     * 更新授权操作
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changepermission(Request $request)
    {
        $menuaction_id = $request->jsid;
        $permission = $request->iskcz;
        $role_id = $request->roid;
        $agent_id = Common::loginUser()->agent_id;
        $user_role_id = Common::loginUser()->role_id;

        if($user_role_id == 2){
            $time_now = now()->toDateTimeString();

            if( !empty($menuaction_id) && !empty($role_id) && !empty($agent_id) && $permission >= 0 )
            {
                if($role_id == 2){
                    return response()->json(['status' => 'err', 'code' => 205, 'msg' => '系统管理员权限不可更改。有任何疑问请联系技术开发人员。']);
                }else{
                    $check_row_num = DB::table("role_relations")->where('menuaction_id', $menuaction_id)->where('agent_id', $agent_id)->where('role_id', $role_id)->count();
                    if($check_row_num == 0)
                    {

                        DB::insert('insert into role_relations (menuaction_id, agent_id, role_id, permission, created_at) values (?, ?, ?, ?, ?)', [$menuaction_id, $agent_id, $role_id, $permission, $time_now]);

                        return response()->json(['status' => 'success', 'code' => 200, 'msg' => '授权成功。']);
                    }elseif($check_row_num == 1){
                        DB::table('role_relations')
                            ->where('menuaction_id', $menuaction_id)
                            ->where('role_id', $role_id)
                            ->update(['permission' => $permission, 'updated_at' => $time_now]);

                        return response()->json(['status' => 'success', 'code' => 200, 'msg' => '更新授权成功。']);
                    }else{

                        return response()->json(['status' => 'err', 'code' => 404, 'msg' => '授权失败。']);
                    }
                }
            }else{
                return response()->json(['status' => 'err', 'code' => 800, 'msg' => '数据异常，授权失败。']);
            }
        }else{
            // 非系统管理员（代账公司管理员）角色禁止编辑权限
            return response()->json(['status' => 'err', 'code' => 405, 'msg' => '权限不足，操作失败。']);
        }
    }


    /**
     * 代账公司管理员添加角色
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function addrolenew(Request $request)
    {
        $user_role_id = Common::loginUser()->role_id;
        $agent_id = Common::loginUser()->agent_id;

        if($user_role_id == 2)
        {
            $model = new Roles();
            $model->role_name = $request->name;
            $model->add_by = 'agent';
            $model->agent_id = $agent_id;

            if($model->save()){
                return response()->json(['status' => 'success', 'code' => 200, 'msg' => '新增角色成功。']);
            }
        }else{
            return response()->json(['status' => 'err', 'code' => 405, 'msg' => '权限不足，操作失败。']);
        }
    }
}