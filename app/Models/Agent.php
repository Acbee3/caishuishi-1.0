<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    //
    protected $table = 'agent';

    const STATUS_0 = 0;
    const STATUS_1 = 1;

    public static $statusLables = [
        self::STATUS_1 => "正常",
        self::STATUS_0 => "禁用",
    ];

    /**
     * 代账公司列表查询
     * @param $request
     * @param int $pageSize
     * @return Agent
     */
    public static function search($request, $pageSize = 15){

        $agent = new Agent();

        if(!empty($request->name)){
            $agent = $agent->where("name","like","%".$request->name."%");
        }

        if(!empty($request->contacts)){
            $agent = $agent->where("contacts","like","%".$request->contacts."%");
        }

        if(!empty($request->phone)){
            $agent = $agent->where("phone","like","%".$request->phone."%");
        }

        if(isset($request->status) && $request->status != ''){
            $agent = $agent->where("status",$request->status);
        }

        $agent = $agent->paginate($pageSize);

        return $agent;
    }

    /**
     * 创建代账公司
     * @param $request
     * @return bool
     */
    public static function saveAgent($request){
        $agent = new Agent();
        $agent->name = $request->name;
        $agent->contacts = $request->contacts;
        $agent->phone = $request->phone;
        $agent->address = $request->address;
        $agent->pic = $request->pic;
        $agent->status = $request->status;
        if($agent->save()){
            return $agent;
        }
        return [];
    }
}
