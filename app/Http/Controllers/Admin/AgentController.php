<?php

namespace App\Http\Controllers\Admin;

use App\Models\Agent;
use App\Models\AgentDepartment;
use App\Models\Common;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AgentController extends Controller
{

    /**
     * 代账公司列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){

        $data = Agent::search($request);
        return view('admin.agent.index',[
           'data' => $data,
           'request' => $request,
        ]);
    }


    /**
     * 添加代账公司
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request){
        if (Common::isPost($request)){

            $this->validate($request, [
                "name" => 'required|string|unique:agent',
                "contacts" => 'required|string',
                "phone" => 'required|unique:agent|digits_between:11,11',
                'address' => 'required|string',
                'pic' => 'required|string',

                //'username' => 'required|string|unique:users',
                //'password' => 'required|string|min:6|confirmed',
            ],
            [
                "name.required" => '代账公司名称必填！',
                "name.unique" => '代账公司名称已经存在！',
                "contacts.required" => '联系人必填！',
                'phone.required' => '手机号必填！',
                'phone.unique' => '手机号已经存在！',
                'phone.digits_between' => '手机号需要11位！',
                'address.required' => '地址必须！',
                'pic.required' => '图片必须选择上传！',
                //"username.required" => '用户名必填！',
                //"username.unique" => '用户名已经存在！',
                //'password.confirmed' => '两次密码输入不一致！',
                //'password.required' => '密码必填！！',
                //'password.min' => '密码最少6位！',
            ]);

            try{

                DB::beginTransaction();

                $agent = Agent::saveAgent($request);

                if(!$agent){
                    throw new \Exception("代账公司保存出现问题！");
                }
                $userArr = [
                    'name' => $request->username,
                    'password' => $request->password,
                    'phone' => $request->phone,
                    'agent_id' => $agent->id,
                    'level' => User::LEVEL_2,
                ];


                $return = User::apiRegister($userArr);
                if($return["result"] != "success"){
                    throw new \Exception($return["message"]);
                }

                $userArr['id'] =$return['data']['id'];
                $return = User::createUser($userArr);

                if(!$return){
                    throw new \Exception("用户创建失败！");
                }

                $return = AgentDepartment::saveDepartment($request->name,$agent->id,null,AgentDepartment::LEVEL_1);
                if(!$return){
                    throw new \Exception("部门创建失败！");
                }

                DB::commit();
                return redirect('/admin/agent/index');
            }catch (\Exception $e){
                DB::rollBack();
                return redirect()->refresh()->withErrors($e->getMessage())->withInput();
            }

        }
        return view("admin.agent.create",[]);
    }


    public function edit(Request $request){
        $agent = Agent::find($request->id);

        if(Common::isPost($request)){

            $this->validate($request, [
                "contacts" => 'required|string',
                "phone" => 'required|digits_between:11,11|unique:agent,phone,'.$agent->id,
                'address' => 'required|string',
                'pic' => 'required|string',

                //'username' => 'required|string|unique:users',
                //'password' => 'required|string|min:6|confirmed',
            ],
            [

                "contacts.required" => '联系人必填！',
                'phone.required' => '手机号必填！',
                'phone.unique' => '手机号已经存在！',
                'phone.digits_between' => '手机号需要11位！',
                'address.required' => '地址必须！',
                'pic.required' => '图片必须选择上传！',
            ]);

           $agent->contacts = $request->contacts;
           $agent->phone = $request->phone;
           $agent->address = $request->address;
           $agent->pic = $request->pic;
           $agent->status = $request->status;
           if($agent->save()){
               return redirect("/admin/agent/index");
           }
        }
        return view("admin.agent.edit",['model'=>$agent]);
    }

}
