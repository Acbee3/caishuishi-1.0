<?php
/**
 * Created by PhpStorm V.2018.
 * User: Administrator - Newsboy9248@163.com
 * Date: 2018/5/30 - 17:31
 */

namespace App\Models;

use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;

Class Priv extends Model
{
    /**
     *  CFS操作权限判断处理
     */
    public static function CFS_Priv($action_code)
    {
        // 获取当前认证用户的ID...
        //$id = Auth::id();
        $id = Common::loginUserId();

        $can_tf = Priv::CFS_get_userstatusroleid_info();
        if ($can_tf) {
            // 取会员可操作权限
            if (session()->has('User_sess')) {
                // 通过session取权限
                $all_session = session()->all();
                $user_session = $all_session['User_sess'];
                $priv_session = $user_session['rs_list'];

                $now_user_priv = $priv_session;
            } else {
                // 通过联表读表取数据
                $now_user_priv = Priv::CFS_get_useractionlist_info($id);
            }

            if ($now_user_priv == 'all') {
                // 全部权限
                return true;
            } else {
                if (strpos($now_user_priv, $action_code) !== false) {
                    // 有权限，放行
                    return true;
                } else {
                    // 无权限  提示无操作权限
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    /**
     *  会员状态 及 权限组相关判断
     */
    public static function CFS_get_userstatusroleid_info()
    {
        // 获取当前认证用户...
        //$user = Auth::user();
        $user = Common::loginUser();
        $user_baseinfo = $user->toArray();

        $u_id = $user_baseinfo['id'];
        $u_status = $user_baseinfo['status'];
        $u_roleid = $user_baseinfo['role_id'];

        // 此处限制权限：会员状态未设置、禁用； 角色组未分配； 空会员ID、0值会员ID
        if (empty($u_status) || $u_status == 'no' || empty($u_roleid) || empty($u_id) || $u_id == '0') {
            return false;
        } else {
            return true;
        }
    }

    /**
     *  取会员可操作权限详细信息
     */
    public static function CFS_get_useractionlist_info($id)
    {
        // 当前会员权限
        $userinfo = DB::table("users as u")->select('u.id', 'u.role_id', 'rs.id as rs_id', 'rs.role_name as rs_name', 'rs.role_list as rs_list')->leftjoin('roles as rs', 'u.role_id', '=', 'rs.id')->where('u.id', '=', $id)->get();

        if ($userinfo) {
            $userinfo = $userinfo->toArray();
            $userinfo = Common::cfs_object_to_array($userinfo[0]);

            //$userinfo['password'] = '';
            $userinfo['rs_name'] = '***';// 角色组名称session中不可见

            // 存储会员数据到session， 操作与判断
            session(['User_sess' => $userinfo]);

            $now_user_priv = $userinfo['rs_list'];

            if (!empty($now_user_priv)) {
                // 有分配会员权限
                return $now_user_priv;
            } else {
                // 所在的角色组未分配权限
                return false;
            }
        } else {
            return false;
        }


    }

    /**
     *  取会员可操作权限详细信息
     */
    public static function CFS_get_useractionlist_info_all($id)
    {
        // 当前会员权限
        $userinfo = DB::table("users as u")->select('u.*', 'rs.id as rs_id', 'rs.role_name as rs_name', 'rs.role_list as rs_list')->leftjoin('roles as rs', 'u.role_id', '=', 'rs.id')->where('u.id', '=', $id)->get();

        if ($userinfo) {
            $userinfo = $userinfo->toArray();
            $userinfo = Common::cfs_object_to_array($userinfo[0]);

            $userinfo['password'] = '';
            $userinfo['rs_name'] = '***';// 角色组名称session中不可见

            // 存储会员数据到session， 操作与判断
            session(['User_sess' => $userinfo]);

            $now_user_priv = $userinfo['rs_list'];

            if (!empty($now_user_priv)) {
                // 有分配会员权限
                return $now_user_priv;
            } else {
                // 所在的角色组未分配权限
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     *  取权限session信息
     */
    public static function CFS_get_Priv_session()
    {
        $priv_session = '';

        if (session()->has('User_sess')) {
            // 通过session取权限
            $all_session = session()->all();
            $user_session = $all_session['User_sess'];
            $priv_session = $user_session['rs_list'];
        } else {
            // 通过联表读表取数据
            //$id = Auth::id();
            $id = Common::loginUserId();
            $priv_session = Priv::CFS_get_useractionlist_info($id);
        }

        return $priv_session;
    }
}

