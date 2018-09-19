<?php
/**
 * Created by PhpStorm V.2018.
 * User: Administrator - Newsboy9248@163.com
 * Date: 2018/6/15 - 10:23
 */

namespace App\Http\Controllers\Agent;

use App\Models\Common;
use App\Models\Authorization;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class AuthorizationsController extends Controller
{
    /**
     * 客户授权中心页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view("agent.authorizations.index", ['request' => $request]);
    }

    /**
     * 客户授权列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lists(Request $request)
    {
        $pageSize = 15;

        // 取当前代账公司名下的客户公司列表
        $company_data = Authorization::Get_Authorization_Company_Lists($request, $pageSize);
        $company_data = Common::cfs_array_to_object($company_data);

        // 取当前代账公司的角色组
        $role_list = Authorization::Get_Auth_Roles_list();

        // 分页信息
        //$agent_id = Auth::user()->agent_id;
        $agent_id = Common::loginUser()->agent_id;
        $links_data = Company::where('agent_id', '=', $agent_id)->where('status', 'yes')->paginate($pageSize);

        return view("agent.authorizations.lists", ['data' => $company_data, 'role_lists' => $role_list, 'links_data' => $links_data, 'request' => $request]);
    }

    /**
     * 取授权用户信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getagentusers(Request $request)
    {
        $role_id = $request->rid;
        $users = Authorization::Get_Auth_Agent_Users($role_id);

        return response()->json(['status' => 'success', 'code' => 200, 'msg' => '成功。', 'data' => $users]);
    }

    /**
     * 授权用户操作
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authusers(Request $request)
    {
        $role_id = $request->rid;
        $company_id = $request->cid;
        $checked_user_ids = @join(",", $request->ids);// 已选中用户ids

        if (!empty($checked_user_ids)) {
            $checked_users_name = Authorization::Get_Users_NameByIds_SaveAuth($checked_user_ids, $role_id, $company_id);
            $names = $checked_users_name;

            return response()->json(['status' => 'success', 'code' => 200, 'msg' => '授权成功。', 'names' => $names]);
        } else {
            return response()->json(['status' => 'err', 'code' => 400, 'msg' => '未选择授权人员，授权失败。', 'names' => '']);
        }
    }
}