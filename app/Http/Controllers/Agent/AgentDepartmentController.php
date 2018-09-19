<?php

namespace App\Http\Controllers\Agent;

use App\Models\AgentDepartment;
use App\Models\Common;
use App\Models\Roles;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AgentDepartmentController extends Controller
{
    //

    public function index(Request $request){
        //$user = Auth::user();
        $user = Common::loginUser();
        $request->agent_id = $user->agent_id;

        if(Common::isPost($request)) {

            $request->status = AgentDepartment::STATUS_1;
            $agent = AgentDepartment::search($request);
            $agent = $agent->toArray();

            $data = [];
            foreach ($agent['data'] as $v){
                $arr['id'] = $v['id'];
                $arr['name'] = $v['department_name'];
                $arr['pId'] = $v['pid'];
                $arr['level'] = $v['level'] - 1;

                if($v['pid'] == 0){
                    $arr['open'] = true;
                    $arr['isParent'] = true;
                }
                $data[] = $arr;
                unset($arr['open']);
                unset($arr['isParent']);
            }

            $agent['data'] = $data;
            return response()->json($agent);
        }

        return view('agent.department.index',[]);
    }


    public function create(Request $request){
        //$user = Auth::user();
        $user = Common::loginUser();
        $this->validate($request, [
            "department_name" => 'required|string',
        ],
        [
            "department_name.required" => '部门名称必填！',
        ]);
        try {
            $agent = AgentDepartment::ajaxSaveDepartment($request);
            $agent = $agent->toArray();
            if ($agent) {
                return response()->json(['result' => 'success', 'data' => $agent]);
            }
        }catch (\Exception $e){
            return response()->json(['result' => 'error','message' => $e->getMessage()]);
        }
    }


    /*public function edit(Request $request){
        $user = Auth::user();
        $this->validate($request, [
            "department_id" => 'required|string',
            "department_name" => 'required|string',
        ],
        [
            "department_id.required" => '部门ID缺失！',
            "department_name.required" => '部门名称必填！',
        ]);

        try {
            $agent = AgentDepartment::saveDepartment($request->department_name, $user->agent_id,$request->department_id);
            $agent = $agent->toArray();
            if ($agent) {
                return response()->json(['result' => 'success', 'data' => $agent]);
            }
        }catch (\Exception $e){
            return response()->json(['result' => 'error','message' => $e->getMessage()]);
        }

    }*/


    public function del(Request $request){
        $this->validate($request, [
            "department_id" => 'required|string',
        ],
        [
            "department_id.required" => '部门ID缺失！',
        ]);
        try {
            $agent = AgentDepartment::delDepartment($request);
            if ($agent) {
                return response()->json(['result' => 'success', 'data' => []]);
            }
        }catch (\Exception $e){
            return response()->json(['result' => 'error','message' => $e->getMessage()]);
        }

    }


    public function createUser(Request $request){

        return view('agent.department.create_user',[]);
    }
}
