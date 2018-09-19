<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;

/**
 * 代账公司客户公司 部门管理 控制器
 * Class DepartmentController
 * @package App\Http\Controllers\Book
 */
class DepartmentController extends Controller
{

    public function index(Request $request)
    {
        $CompanyName = Department::DepartmentCompanyName();
        $CompanyId = Department::DepartmentCompanyId();
        $DepartmentId = Department::DepartmentId($request);
        $DepartmentName = Department::DepartmentName($request);

        $tree_children = Department::DepartmentListJson($request);
        $tree_children = json_encode($tree_children);

        $pageSize = Employee::pageSize;
        $request->dep_id = $DepartmentId;

        $data = Employee::pager($request, $pageSize);

        return view('book.department.index', compact('CompanyName', 'CompanyId', 'DepartmentId', 'DepartmentName', 'tree_children', 'data', 'request'));
    }

    /**
     * 员工搜索显示列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $CompanyName = Department::DepartmentCompanyName();
        $CompanyId = Department::DepartmentCompanyId();
        $DepartmentId = Department::DepartmentId($request);
        $DepartmentName = Department::DepartmentName($request);

        $tree_children = Department::DepartmentListJson($request);
        $tree_children = json_encode($tree_children);

        $pageSize = Employee::pageSize;
        $request->dep_id = $DepartmentId;

        $data = Employee::pager($request, $pageSize);

        $sv = $request->sv;
        $tit = $request->tit;

        return view('book.department.search', compact('CompanyName', 'CompanyId', 'DepartmentId', 'DepartmentName', 'tree_children', 'data', 'request', 'sv', 'tit'));
    }

    /**
     * AJAX 添加部门
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_add(Request $request)
    {
        $model = new Department();
        $model->department_name = $request->name;
        $model->status = '1';

        $company_id = Department::DepartmentCompanyId();
        $model->company_id = $company_id;

        if (!empty($request->name) && !empty($company_id) && $model->save()) {
            return response()->json(['status' => 'success', 'code' => 200, 'msg' => '新增部门成功。']);
        } else {
            return response()->json(['status' => 'err', 'code' => 400, 'msg' => '新增部门失败。']);
        }
    }

    /**
     * AJAX 编辑部门
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_edit(Request $request)
    {
        $id = $request->id;
        $model = Department::find($id);

        $model->department_name = $request->name;
        $updated_at = now()->toDateTimeString();
        $model->updated_at = $updated_at;

        if (!empty($request->name) && !empty($id) && $model->save()) {
            return response()->json(['status' => 'success', 'code' => 200, 'msg' => '编辑部门成功。']);
        } else {
            return response()->json(['status' => 'err', 'code' => 400, 'msg' => '编辑部门失败。']);
        }
    }

    /**
     * AJAX 删除部门
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function api_del(Request $request)
    {
        if (empty($request->id)) {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => '删除部门失败。']);
        }

        $employee_num = Employee::where('department_id', $request->id)->count();
        if($employee_num >= 1){
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => '当前部门里还有员工，不可删除此部门~。']);
        }

        $result = Department::where('id', $request->id)->delete();
        return response()->json(['status' => !$result ? 'error' : 'success', 'code' => 200, 'msg' => '删除部门成功。']);

    }
}
