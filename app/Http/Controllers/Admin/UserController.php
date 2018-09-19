<?php

namespace App\Http\Controllers\Admin;

use App\Models\Common;
use App\Models\ModelUser;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function index(Request $request){
        $data = User::search($request);
        return view("admin.user.index",[
            'data' => $data,
            'request' => $request
        ]);
    }


    public function create(Request $request){

        if (Common::isPost($request)){

            $this->validate($request, [
                "name" => 'required|string|unique:users',
                "job_number" => 'required|string|unique:users',
                "phone" => 'required|unique:users|alpha_num|digits_between:10,12',
                'password' => 'required|string|min:6|confirmed',
            ],
            [
                "name.required" => '用户名必填！',
                "name.unique:users" => '用户名已经存在！',
                'password.required' => '密码必填！',
                'password.min' => '密码至少需要6位！',
                "job_number.required" => '工号必填！',
                "job_number.unique" => '工号已经存在！',
                'phone.required' => '手机号必填！',
                'phone.unique' => '手机号已经存在！',
                'phone.digits_between' => '手机号需要11位！',
                'password.confirmed' => '两次密码输入不一致！',
            ]);

            $model = new User();
            $model->name = $request->name;
            $model->job_number = $request->job_number;
            $model->phone = $request->phone;
            $model->password = bcrypt($request->password);
            $model->status = User::STATUS_1;

            if($model->save()){
                $modelUser = new ModelUser();
                $modelUser->user_id = $model->id;
                $modelUser->qa = !empty($request->qa)?1:0;
                $modelUser->business = !empty($request->business)?1:0;
                $modelUser->save();
                return redirect("admin/user/index");
            }
        }

        return view("admin.user.create");
    }


    public function edit(Request $request){
        $id = $request->id;
        $model = User::find($id);
        $modelUser = ModelUser::where("user_id",$model->id)->first();

        if (Common::isPost($request)){
            $this->validate($request, [
                "name" => 'required|string|unique:users,name,'.$model->id,
                "job_number" => 'required|string|unique:users,job_number,'.$model->id,
                "phone" => 'required|digits_between:10,12|alpha_num|unique:users,phone,'.$model->id,
            ],
            [
                "name.required" => '用户名必填！',
                "name.unique:users" => '用户名已经存在！',

                "job_number.required" => '工号必填！',
                "job_number.unique" => '工号已经存在！',

                'phone.required' => '手机号必填！',
                'phone.unique' => '手机号已经存在！',
                'phone.digits_between' => '手机号需要11位！',
                'phone.alpha_num' => '手机号格式不正确！',
            ]);
            $model->name = $request->name;
            $model->job_number = $request->job_number;
            $model->phone = $request->phone;

            if(!empty($request->password)){
                $this->validate($request, [
                    'password' => 'required|string|min:6|confirmed',
                ],
                [
                    'password.required' => '密码必填！',
                    'password.min' => '密码至少需要6位！',
                    'password.confirmed' => '两次密码输入不一致！',
                ]);
                $model->password = bcrypt($request->password);
            }

            $model->status = User::STATUS_1;
            if($model->save()){
                $modelUser->qa = !empty($request->qa)?1:0;
                $modelUser->business = !empty($request->business)?1:0;
                $modelUser->save();
                return redirect("admin/user/index");
            }
        }

        $id = $request->id;
        $model = User::find($id);

        return view("admin.user.edit",['model'=>$model,'modelUser'=>$modelUser]);


    }


    public function freeze(Request $request){
        $id = $request->id;
        $model = User::find($id);
        if($request->status == User::STATUS_1){
            $model->status = User::STATUS_1;
        }else{
            $model->status = User::STATUS_5;
        }

        if($model->save()){
            return redirect("admin/user/index");
        }
    }

}
