<?php

namespace App\Http\Controllers\Agent;

use App\Models\AgentDepartment;
use App\Models\Common;
use App\Models\Roles;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //

    public function agentIndex(Request $request){
        $pageSize = $request->pageSize ? $request->pageSize : Common::PAGE_SIZE;

        $userList = User::search($request,$pageSize);
        $roleArr = Roles::Where("status",'yes')->pluck('role_name','id')->toArray();

        $request->status = AgentDepartment::STATUS_1;
        $departmentArr = AgentDepartment::where('status',AgentDepartment::STATUS_1)->pluck('department_name','id')->toArray();

        foreach ($userList as &$item){
            $item->department = array_key_exists($item->department_id,$departmentArr)?$departmentArr[$item->department_id]:'';
            $item->role = array_key_exists($item->role_id,$roleArr)?$roleArr[$item->role_id]:'';
            $item->status = array_key_exists($item->status,User::$statusLabels)?User::$statusLabels[$item->status]:'';
            $item->update_url = "/agent/user/agent-edit?id=".$item->id;
        }

        return response()->json($userList);
    }

    public function agentCreate(Request $request){
        if (Common::isPost($request)){
            //$user = Auth::user();
            $user = Common::loginUser();

            $this->validate($request, [
                "name" => 'required|string|unique:users',
                "true_name" => 'required|string|unique:users',
                "phone" => 'required|unique:users|digits_between:11,11',
                'password' => 'required|string|min:6',
                'department' => 'required',
                'role_id' => 'required',
            ],
            [
                "name.required" => '用户名必填！',
                "department.required" => '部门必填！',
                "role_id.required" => '角色必填！',
                "name.unique:users" => '用户名已经存在！',
                'password.required' => '密码必填！',
                'password.min' => '密码至少需要6位！',
                'phone.required' => '手机号必填！',
                'phone.unique' => '手机号已经存在！',
                'phone.digits_between' => '手机号需要11位！',
                //'password.confirmed' => '两次密码输入不一致！',
            ]);
            try {
                $userArr = [
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'password' => $request->password,
                    'level' => User::LEVEL_2,
                    'agent_id' => $user->agent_id,
                    'department_id' => $request->department,
                    'role_id' => $request->role_id,
                    'true_name' => $request->true_name,
                ];
                $return = User::apiRegister($userArr);
                $userArr['id'] =$return['data']['id'];
                $return = User::createUser($userArr);

                if(!$return){
                    throw new \Exception("用户创建失败！");
                }

                return redirect('agent/department/index');

            }catch(\Exception $e){
                return redirect()->refresh()->withErrors($e->getMessage())->withInput();
            }

        }
        // 角色、用户组列表
        $roles = \DB::table("roles")->whereRaw('status = "yes"' )->orderBy('id', 'Asc')->get();

        $request->agent_id = Common::loginUser()->agent_id;
        //$request->agent_id = Auth::user()->agent_id;
        $request->level = AgentDepartment::LEVEL_2;
        $department = AgentDepartment::search($request);
        return view('agent.user.agent-create',['roles' => $roles, 'department' => $department]);
    }



    public function agentEdit(Request $request){
        if (Common::isPost($request)){
            //$user = Auth::user();
            $user = Common::loginUser();

            $this->validate($request, [
                "true_name" => 'required|string',
                "phone" => 'required|digits_between:11,11|unique:users,phone,'.$request->id,
                'department' => 'required',
                'role_id' => 'required',
            ],
            [
                "true_name.required" => '姓名必填！',
                "department.required" => '部门必填！',
                "role_id.required" => '角色必填！',
                "name.unique:users" => '用户名已经存在！',
                'phone.required' => '手机号必填！',
                'phone.unique' => '手机号已经存在！',
                'phone.digits_between' => '手机号需要11位！',
            ]);

            try {

                $userArr = [
                    'id' => $request->id,
                    'name' => $request->name,
                    'true_name' => $request->true_name,
                    'phone' => $request->phone,
                    'password' => $request->password,
                    'level' => User::LEVEL_2,
                    'agent_id' => $user->id,
                    'department_id' => $request->department,
                    'role_id' => $request->role_id,
                ];

                $return = User::apiEditUser($userArr);
                if($return['result'] == 'error'){
                    throw new \Exception("用户中心数据修改失败！");
                }

                $userArr['id'] =$return['data']['id'];
                $return = User::createUser($userArr);

                if(!$return){
                    throw new \Exception("用户编辑失败！");
                }

                return redirect('agent/department/index');

            }catch(\Exception $e){
                return redirect()->refresh()->withErrors($e->getMessage())->withInput();
            }

        }
        // 角色、用户组列表
        $roles = \DB::table("roles")->whereRaw('status = "yes"' )->orderBy('id', 'Asc')->get();

        //$request->agent_id = Auth::user()->agent_id;
        $request->agent_id = Common::loginUser()->agent_id;
        $request->level = AgentDepartment::LEVEL_2;
        $department = AgentDepartment::search($request);
        $model = User::find($request->id);
        return view('agent.user.agent-edit',['roles' => $roles, 'department' => $department, 'model' => $model]);
    }


    public function agentDel(){

    }


    public function changeStatus(Request $request){
        $user = User::find($request->id);
        if(!$user){
            return response()->json(['result'=>'error','message'=>'没有查询到用户！']);
        }

        if($user->status == "yes"){
            $user->status = 'no';
        }else{
            $user->status = 'yes';
        }

        if(!$user->save()){
            return response()->json(['result'=>'error','message'=>'用户状态修改失败！']);
        }
        return response()->json(['result'=>'success','data'=>'']);
    }
}
