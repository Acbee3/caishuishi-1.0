<?php
/**
 * Created by PhpStorm V.2018.
 * User: Administrator - Newsboy9248@163.com
 * Date: 2018/6/1 - 15:49
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;

class Menus extends Model
{
    protected $table = "menus";

    /**
     * 取会员角色组
     *
     */
    public static function get_roles_list()
    {
        $res = DB::table("roles")->whereRaw('status = "yes"' )->orderBy('id', 'Asc')->get();

        //$res = $res->toArray();
        //$res = Common::cfs_object_to_array($res);
        //$menu_info = Menus::find();

        foreach ($res as $key => $v)
        {
            $list_arr[$key]['id']   = $v->id;
            $list_arr[$key]['role_name']   = $v->role_name;
            //$list_arr[$key]['check']   = $menu_info->role_ids;
            //$list_arr[$key]['check']   = 'checked';
        }

        if(isset($list_arr))
        {
            return $list_arr;
        }

    }


    /**
     * 根据后台设定菜单位置及分配权限调用菜单数据
     * @param $menu_id
     * @param $role_id
     * @param $agent_id
     * @param $company_id
     * @return mixed
     */
    public static function get_menus_list($menu_id, $role_id, $agent_id, $company_id)
    {
        $res = DB::table('menu_actions')->where('menu_id','=',$menu_id)->where('parent_id','=','0')->where('status','=','yes')->orderBy('sort_order', 'ASC')->get();

        $list_arr = array();
        foreach ($res as $key => $v)
        {
            // 根据权限取菜单信息
            $check_role_id = Menus::check_if_roleid_in_roleids($role_id, $v->role_ids);
            if($check_role_id){
                $list_arr[$key]['id']   = $v->id;
                $list_arr[$key]['action_name']   = $v->action_name;
                $list_arr[$key]['action_route']   = $v->action_route;
                $list_arr[$key]['parent_id']   = $v->parent_id;
                $list_arr[$key]['role_ids']   = $v->role_ids;
                $list_arr[$key]['sort_order']   = $v->sort_order;
                $list_arr[$key]['child_ids']   = $v->child_ids;

                $child_arr = Menus::get_child_menus_list($v->id, $role_id);
                if($child_arr){
                    $list_arr[$key]['ishow'] = true;
                }else{
                    $list_arr[$key]['ishow'] = false;
                }

                $list_arr[$key]['child_arr'] = $child_arr;
            }else{
                unset($list_arr[$key]);
            }
        }

        if(isset($list_arr))
        {
            return $list_arr;
        }
    }

    /**
     * 获取菜单的子级（二级、三级……）
     * @param $parent_id
     * @return bool
     */
    public static function get_child_menus_list($parent_id, $role_id)
    {
        $res = DB::table('menu_actions')->where('parent_id','=',$parent_id)->where('status','=','yes')->get();

        $list_arr = array();
        if($res){
            foreach ($res as $key => $v)
            {
                $check_role_id = Menus::check_if_roleid_in_roleids($role_id, $v->role_ids);
                if($check_role_id){
                    $list_arr[$key]['id']   = $v->id;
                    $list_arr[$key]['action_name']   = $v->action_name;
                    $list_arr[$key]['action_route']   = $v->action_route;
                    $list_arr[$key]['parent_id']   = $v->parent_id;
                    $list_arr[$key]['role_ids']   = $v->role_ids;
                    $list_arr[$key]['sort_order']   = $v->sort_order;
                    $list_arr[$key]['child_ids']   = $v->child_ids;

                    $child_arr = Menus::get_child_menus_list($v->id, $role_id);
                    if($child_arr){
                        $list_arr[$key]['ishow'] = true;
                    }else{
                        $list_arr[$key]['ishow'] = false;
                    }

                    $list_arr[$key]['child_arr'] = $child_arr;
                }else{
                    unset($list_arr[$key]);
                }
            }

            if(isset($list_arr))
            {
                return $list_arr;
            }

        }else{
            return $list_arr;
        }
    }

    /**
     * 判断当前会员role_id是否包含在role_ids里
     * @param $role_id
     * @param $role_ids
     * @return bool
     */
    public static function check_if_roleid_in_roleids($role_id, $role_ids)
    {
        $arr0 = explode(',',$role_id);
        $arr1 = explode(',',$role_ids);

        //array_intersect 计算数组的交集
        if ($arr0 == array_intersect($arr0, $arr1)) {
            $flag = true;
        }else {
            $flag = false;
        }

        return $flag;
    }

    /**
     * 获取所有菜单列表  用于　基础设置　角色权限
     * @param $role_id
     * @return array
     */
    public static function get_menus_all_list($role_id)
    {
        //$user_info = Auth::user();
        $user_info = Common::loginUser();
        //$user_id = $user_info->id;
        $agent_id = $user_info->agent_id;

        $res = DB::table('menu_actions')->where('parent_id','=','0')->where('status','=','yes')->where('sys_mark','!=','sys')->orderBy('sort_order', 'ASC')->get();

        $list_arr = array();
        foreach ($res as $key => $v)
        {
            $list_arr[$key]['id']   = $v->id;
            $list_arr[$key]['action_name']   = $v->action_name;
            $list_arr[$key]['action_route']   = $v->action_route;
            $list_arr[$key]['parent_id']   = $v->parent_id;
            $list_arr[$key]['role_ids']   = $v->role_ids;
            $list_arr[$key]['sort_order']   = $v->sort_order;
            //$list_arr[$key]['child_ids']   = $v->child_ids;

            // 子级菜单
            $child_arr = Menus::get_child_menus_all_list($v->id, $role_id, $agent_id);
            if($child_arr){
                $list_arr[$key]['ishow'] = true;
            }else{
                $list_arr[$key]['ishow'] = false;
            }
            $list_arr[$key]['child_arr'] = $child_arr;

            // 单选按钮状态及取值　　0查看　　1操作   2无权限
            if($role_id == 2){
                $list_arr[$key]['val'] = '1';
                $list_arr[$key]['disabled0'] = 'disabled';
                $list_arr[$key]['disabled1'] = '';
                $list_arr[$key]['disabled2'] = 'disabled';
            }else{
                $list_arr[$key]['val'] = Menus::get_role_permission_val($v->id, $role_id, $agent_id);
                $list_arr[$key]['disabled0'] = '';
                $list_arr[$key]['disabled1'] = '';
                $list_arr[$key]['disabled2'] = '';
            }
        }

        if(isset($list_arr))
        {
            return $list_arr;
        }
    }

    /**
     * 基础设置　角色权限　取二级三级及状态、赋值
     * @param $parent_id
     * @param $role_id
     * @param $user_id
     * @param $agent_id
     * @return array
     */
    public static function get_child_menus_all_list($parent_id, $role_id, $agent_id)
    {
        $res = DB::table('menu_actions')->where('parent_id','=',$parent_id)->where('status','=','yes')->where('sys_mark','!=','sys')->get();

        $list_arr = array();
        if($res){
            foreach ($res as $key => $v)
            {
                $list_arr[$key]['id']   = $v->id;
                $list_arr[$key]['action_name']   = $v->action_name;
                $list_arr[$key]['action_route']   = $v->action_route;
                $list_arr[$key]['parent_id']   = $v->parent_id;
                $list_arr[$key]['role_ids']   = $v->role_ids;
                $list_arr[$key]['sort_order']   = $v->sort_order;
                //$list_arr[$key]['child_ids']   = $v->child_ids;

                $child_arr = Menus::get_child_menus_all_list($v->id, $role_id, $agent_id);
                if($child_arr){
                    $list_arr[$key]['ishow'] = true;
                }else{
                    $list_arr[$key]['ishow'] = false;
                }

                $list_arr[$key]['child_arr'] = $child_arr;

                // 0查看　　1操作   2无权限
                if($role_id == 2){
                    $list_arr[$key]['val'] = '1';
                    $list_arr[$key]['disabled0'] = 'disabled';
                    $list_arr[$key]['disabled1'] = '';
                    $list_arr[$key]['disabled2'] = 'disabled';
                }else{
                    $list_arr[$key]['val'] = Menus::get_role_permission_val($v->id, $role_id, $agent_id);
                    $list_arr[$key]['disabled0'] = '';
                    $list_arr[$key]['disabled1'] = '';
                    $list_arr[$key]['disabled2'] = '';
                }
            }

            if(isset($list_arr))
            {
                return $list_arr;
            }

        }else{
            return $list_arr;
        }

    }


    /**
     * 取基础设置　会员权限表存储权限数据
     * @param $menu_action_id
     * @param $role_id
     * @param $agent_id
     * @return int
     */
    public static function get_role_permission_val($menu_action_id, $role_id, $agent_id)
    {
        $res = DB::table('role_relations')->where('agent_id',$agent_id)->where('menuaction_id',$menu_action_id)->where('role_id',$role_id)->first();

        $val = 2;
        if($res){
            $val = $res->permission;
        }

        return $val;
    }
}