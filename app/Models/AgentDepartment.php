<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentDepartment extends Model
{
    //
    protected $table = 'agent_department';

    const STATUS_01 = -1;
    const STATUS_1 = 1;

    const LEVEL_1 = 1;
    const LEVEL_2 = 2;

    public static $statusLables = [
        self::STATUS_1 => "正常",
        self::STATUS_01 => "禁用",
    ];


    public static $levelLables = [
        self::LEVEL_1 => "总公司",
        self::LEVEL_2 => "部门",
    ];
    /**
     * 创建代账公司部门
     * @param $department_name
     * @param $agent_id
     * @param $level
     * @param $department_id
     * @return bool
     */
    public static function saveDepartment($department_name,$agent_id,$department_id=null,$level = self::LEVEL_2){
        if ($department_id){
            $model = AgentDepartment::find($department_id);
        }else{
            $model = new AgentDepartment();
        }
        $model->department_name = $department_name;
        $model->agent_id = $agent_id;
        $model->agent_id = $agent_id;
        $model->status = self::STATUS_1;
        $model->level = $level;
        if($model->save()){
            return $model;
        }
    }

    /**
     * ajax创建代账公司部门
     * @param $request
     * @return bool
     */
    public static function ajaxSaveDepartment($request){
        if(!$request->pid){
            throw new \Exception("pid不能为空！");
        }
        $department = AgentDepartment::find($request->pid);

        if ( count(explode('-',$request->id)) < 2){
            $model = AgentDepartment::find($request->id);
        }else{

            $model = new AgentDepartment();
        }
        $model->department_name = $request->department_name;
        $model->pid = $request->pid;

        $model->agent_id = $department->agent_id;
        $model->status = self::STATUS_1;
        $model->level = $request->level + 1;
        if($model->save()){
            return $model;
        }
    }

    /**
     *  软删除代账公司部门
     * @param $request
     * @return mixed
     */
    public static function delDepartment($request){
        return AgentDepartment::where(['id'=>$request->department_id])->update(['status'=>AgentDepartment::STATUS_01]);
        //return self::search($request);
    }

    /**
     * 代账公司部门列表（查询）
     * @param $request
     * @param int $pageSize
     * @return mixed
     */
    public static function search($request,$pageSize = 1000){

        $model = new self();
        if(!empty($request->department_name)){
            $model = $model->where("department_name",$request->department_name);
        }
        if(!empty($request->agent_id)){
            $model = $model->where("agent_id",$request->agent_id);
        }
        if(!empty($request->level)){
            $model = $model->where("level",$request->level);
        }
        if(!empty($request->status)){
            $model = $model->where("status",$request->status);
        }else{
            $model = $model->where("status",self::STATUS_1);
        }

        $model = $model->orderBy('level','asc')->orderBy('id','asc')->paginate($pageSize);
        return $model;
    }
}
