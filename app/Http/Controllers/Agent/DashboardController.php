<?php
/**
 * Created by PhpStorm V.2018.
 * User: Administrator - Newsboy9248@163.com
 * Date: 2018/5/29 - 10:17
 */

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Menus;
use App\Models\Priv;
use App\Models\Agent;
use Illuminate\Support\Facades\DB;
use Auth;


class DashboardController extends Controller
{
    /*
     * 控制面板页面
     */
    public function dashboard()
    {
        // 判断权限与错误提示处理
        $can = Priv::CFS_Priv('agent_enter');
        if(!$can){return redirect()->route('forbidden');}

        /**
         *  用于菜单项权限显示控制
         */
        $priv_list = Priv::CFS_get_Priv_session();


        // 会员管理
        if(strpos($priv_list,'sysuser_manage') !==false || $priv_list == 'all') {
            $menu['sysuser_manage'] = 'true';
        }else{
            $menu['sysuser_manage'] = 'false';
        }

        // 角色管理
        if(strpos($priv_list,'sysrole_manage') !==false || $priv_list == 'all') {
            $menu['sysrole_manage'] = 'true';
        }else{
            $menu['sysrole_manage'] = 'false';
        }

        // 角色权限管理
        if(strpos($priv_list,'sysrolelist_manage') !==false || $priv_list == 'all') {
            $menu['sysrolelist_manage'] = 'true';
        }else{
            $menu['sysrolelist_manage'] = 'false';
        }

        // 取代账中心菜单 临时使用，后期可以改用session
        $agent_menu = DB::table('menu_actions')->where('menu_id','=','1')->where('status','=','yes')->get();
        //print_r($agent_menu);
        //exit;

        //return view('agent.main.dashboard', ['menu' => $menu, 'agent_menu' => $agent_menu]);

        // 临时屏蔽首页  以客户列表页面替换首页
        return redirect(route("agent.companies"));
    }


    /**
     * 代账中心页头数据信息
     * API接口链接：　agent/api/agent_header
     */
    public function dashboard_agent_header()
    {
        //$id = Auth::id();
        $id = Common::loginUserId();
        if($id > 0){
            // 会员信息
            //$user = Auth::user();
            $user = Common::loginUser();

            //$data['userinfo'] = $user;
            $data['user_name'] = $user['name'];

            // 取会员所属代账公司ID及扩展信息
            $agent_id = $user['agent_id'];
            if(!empty($agent_id) && $agent_id > 0){
                $agent_row = Agent::find($agent_id);
                $company_name = $agent_row->name;
                $data['company_name'] = $company_name;
            }else{
                $data['company_name'] = '未设置归属代账公司';
            }

            // 当前路由  暂时保留备用
            //$data['route_now'] = \Request::getRequestUri();
            $data['route_now'] = '';

            // 后续菜单输出需要根据会员角色进一步处理;
            // 处理思路：角色role_id、代理记账公司agent_id、业务公司表company_id


            // 菜单
            //$data['items'] = DB::table('menu_actions')->where('menu_id','=','1')->where('parent_id','=','0')->where('status','=','yes')->get();

            // 更新获取菜单数据方法, 便于后续控制  time:201806081800
            $menu_id = 1;
            $role_id = $user['role_id'];
            $company_id = $user['company_id'];
            $data['items'] = Menus::get_menus_list($menu_id, $role_id, $agent_id, $company_id);

            return response()->json(['status'=>1,'msg'=>'data success！','data'=>$data]);
        }else{
            return response()->json(['status'=>0,'msg'=>'data err！','data'=>'NULL']);
        }
    }
}