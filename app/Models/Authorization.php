<?php
/**
 * Created by PhpStorm V.2018.
 * User: Administrator - Newsboy9248@163.com
 * Date: 2018/6/15 - 17:00
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;

class Authorization extends Model
{
    public static function Get_Authorization_Company_Lists($request, $pageSize)
    {
        // 取代账公司id
        $agent_id = Common::loginUser()->agent_id;
        //$agent_id = Auth::user()->agent_id;

        // 取客户信息列表
        $company = DB::table("company")->where('agent_id','=',$agent_id)->where('status','yes')->orderBy('id', 'DESC')->paginate($pageSize);
        $row_num = $company->count();
        if($row_num >= 1){
            $list = array();

            // 后续需要在数组里加入角色相关循环信息
            foreach ($company as $key => $v){
                $list[$key]['id']   = $v->id;
                $list[$key]['company_code']   = $v->company_code;
                $list[$key]['company_name']   = $v->company_name;

                $list[$key]['roles_arr'] = Authorization::Get_Auth_Roles_List_Info($agent_id, $v->id);
            }

            return $list;
        }else{
            dd('此代账公司名下尚未添加客户公司');
            return '';
        }
    }

    public static function Get_Auth_Roles_List_Info($agent_id, $company_id)
    {
        $list = array();
        $list = DB::select('select id, role_name from roles where status = :status and ( add_by = :add_by or agent_id = :agent_id)', [':status'=>'yes',':add_by'=>'sys',':agent_id'=>$agent_id]);

        // 构建新数组
        $arr = array();
        foreach ($list as $key => $v){
            $arr[$key]['id']   = $v->id;
            $arr[$key]['name']   = $v->role_name;
            $arr[$key]['set_names'] = Authorization::Get_Names_Str_ByIds($agent_id, $company_id, $v->id);
        }
        return $arr;

    }

    public static function Get_Names_Str_ByIds($agent_id, $company_id, $role_id)
    {
        $res = DB::table('auth_relations')->where('agent_id','=',$agent_id)->where('company_id','=',$company_id)->where('role_id','=',$role_id)->orderBy('id', 'ASC')->get();

        $names = '';
        if($res->count() >= 1){
            foreach ($res as $key => $v)
            {
                $names .= Users::find($v->user_id)->name.',';
            }
        }else{
            $names = '';
        }
        $names = rtrim($names, ",");

        return $names;

    }

    public static function Get_Auth_Roles_list()
    {
        // 取代账公司id
        //$agent_id = Auth::user()->agent_id;
        $agent_id = Common::loginUser()->agent_id;

        // 取客户信息列表
        $lists = DB::select('select * from roles where status = :status and ( add_by = :add_by or agent_id = :agent_id)', [':status'=>'yes',':add_by'=>'sys',':agent_id'=>$agent_id]);

        return $lists;
    }

    public static function Get_Auth_Agent_Users($role_id)
    {
        // 查找符合条件的用户拼接成 checkbox
        // <input type="checkbox" name="users[]" value="" >

        if(empty($role_id)){
            $checkbox = '';
        }else{
            //$agent_id = Auth::user()->agent_id;
            $agent_id = Common::loginUser()->agent_id;

            // 此处上线需要调整一下注释
            $res = DB::table('users')->where('agent_id','=',$agent_id)->where('status','=','yes')->where('role_id','=',$role_id)->orderBy('id', 'ASC')->get();
            //$res = DB::table('users')->orderBy('id', 'ASC')->get();// 仅测试时使用

            $list = array();
            $checkbox = '';
            if($res->count() >= 1){
                foreach ($res as $key => $v)
                {
                    $list[$key]['id']   = $v->id;
                    $list[$key]['name']   = $v->name;
                    $list[$key]['phone']   = $v->phone;

                    $checkbox .= '<label class="checkbox-inline"><input name="users" value="'.$v->id.'" type="checkbox">'.$v->name.'</label>';
                }
            }else{
                $checkbox = '<label>请先给本角色组添加相关人员再进行授权操作。</label>';
            }
        }

        return $checkbox;
    }


    public static function Get_Users_NameByIds_SaveAuth($ids, $role_id, $company_id)
    {
        if(empty($ids)){
            $names = '';
        }else{
            //$agent_id = Auth::user()->agent_id;
            $agent_id = Common::loginUser()->agent_id;

            // 删除客户授权表相关已授权记录
            DB::delete('delete from auth_relations where agent_id = ? and company_id = ? and role_id = ?', [$agent_id, $company_id, $role_id]);

            $id_arr = explode(",", $ids);
            $names = '';
            $time_now = now()->toDateTimeString();
            foreach($id_arr as $key => $v)
            {
                // 插入新的授权更新  $user_id = $v;
                DB::insert('insert into auth_relations (agent_id, company_id, role_id, user_id, created_at) values (?, ?, ?, ?, ?)', [$agent_id, $company_id, $role_id, $v, $time_now]);

                $user_row = Users::find($v);
                $names .= $user_row->name.',';
            }
            $names = rtrim($names, ",");
        }

        return $names;
    }

}