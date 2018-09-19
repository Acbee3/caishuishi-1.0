<?php
/**
 * Created by PhpStorm V.2018.
 * User: Administrator - Newsboy9248@163.com
 * Date: 2018/5/29 - 14:59
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;

//引入软删除
use Illuminate\Database\Eloquent\SoftDeletes;

class Roles extends Model
{
    protected $table = "roles";

    use SoftDeletes;//开启软删除
    protected $dates=['delete_at'];//数据库中软删除字段，用来保存软删除的时间


    public static function Get_Roles_list()
    {
        // 取代账公司id
        //$agent_id = Auth::user()->agent_id;
        $agent_id = Common::loginUser()->agent_id;

        // 取客户信息列表
        $lists = DB::select('select * from roles where status = :status and ( add_by = :add_by or agent_id = :agent_id)', [':status'=>'yes',':add_by'=>'sys',':agent_id'=>$agent_id]);

        return $lists;
    }
}