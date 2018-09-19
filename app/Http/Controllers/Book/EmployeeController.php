<?php

namespace App\Http\Controllers\Book;

use App\Entity\Company;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Entity\Employee as EmployeeEntity;

/**
 * 代账公司客户公司 员工管理 控制器
 * Class DepartmentController
 * @package App\Http\Controllers\Book
 */
class EmployeeController extends Controller
{

    /**
     * 人员列表json数据 及分页处理
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $pageSize = Employee::pageSize;
        $data['items'] = Employee::EmployeeJsonList($request, $pageSize);

        $data['employee_num'] = Employee::EmployeeNum();

        if ($data['items']) {
            return response()->json(['status' => 'success', 'msg' => 'data success！', 'data' => $data]);
        } else {
            return response()->json(['status' => 'err', 'msg' => 'data err！', 'data' => $data]);
        }
    }

    /**
     * 添加人员
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        new Company();
        $company_name = Company::$company->company_name;
        return view('book.employee.create', compact('request','company_name'));
    }

    /**
     * 编辑员工
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $info = Employee::find($id);
        return view('book.employee.edit', compact('id', 'info'));
    }

    /**
     * AJAX 添加、编辑员工
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function api_save_add(Request $request)
    {
        $result = EmployeeEntity::SaveEmployee($request);
        //'status' => !$result ? 'error' : 'success'

        if ($result['status']) {
            return response()->json(['status' => 'success', 'code' => 200, 'msg' => $result['msg']]);
        } else {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => $result['msg']]);
        }
    }

    /**
     * 导入人员页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function import(Request $request)
    {
        return view('book.employee.import', compact('request'));
    }

    /**
     * 执行导出
     * @param Request $request
     */
    public function export(Request $request)
    {
        Employee::ExportEmployee($request);
    }

    /**
     * AJAX 批量导入员工
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_import(Request $request)
    {
        $result = Employee::ImportEmployee($request);
        if ($result['status']) {
            return response()->json(['status' => 'success', 'code' => 200, 'msg' => $result['msg']]);
        } else {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => $result['msg']]);
        }
    }

    /**
     * AJAX 批量导出人员
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_export(Request $request)
    {
        if (empty($request->ids)) {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => '导出员工信息失败。']);
        }

        return response()->json(['status' => 'success', 'code' => 200, 'msg' => '操作成功。']);
    }

    /**
     * AJAX 删除单个人员
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_del(Request $request)
    {
        if (empty($request->id)) {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => '删除失败。']);
        }

        $result = Employee::where('id', $request->id)->delete();
        return response()->json(['status' => !$result ? 'error' : 'success', 'code' => 200, 'msg' => '删除成功。']);
    }

    /**
     * AJAX 批量删除多个人员
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_del_ids(Request $request)
    {
        if (empty($request->ids)) {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => '批量删除失败。']);
        }

        $result = Employee::whereIn('id', $request->ids)->delete();
        return response()->json(['status' => !$result ? 'error' : 'success', 'code' => 200, 'msg' => '操作成功。']);
    }

    /**
     * AJAX 更改人员状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function api_change_status(Request $request)
    {
        if (empty($request->id)) {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => '操作失败。']);
        }

        $result = Employee::ChangeStatus($request);

        // 用于成功后给前端页面更换输出文本
        if ($request->status == 0) {
            $status = 1;
        } else {
            $status = 0;
        }
        $status_val = $status;

        return response()->json(['status' => !$result ? 'error' : 'success', 'code' => 200, 'msg' => '更改人员状态成功。', 'sid' => $status_val]);
    }

    /**
     * 批量调整部门
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function api_change_department(Request $request)
    {
        if (empty($request->ids)) {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => '批量调整部门失败。']);
        }

        if (empty($request->dep_id)) {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => '您还没有新建部门，请先新建部门!']);
        }

        $result = Employee::ChangeDepartment($request);

        return response()->json(['status' => !$result ? 'error' : 'success', 'code' => 200, 'msg' => '操作成功。']);
    }

    /**
     * 批量调整部门 之  获取部门下拉options
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function api_get_department(Request $request)
    {
        $result = Employee::GetDepartment($request);

        if ($result['status']) {
            return response()->json(['status' => 'success', 'code' => 200, 'msg' => $result['msg'], 'options' => $result['options']]);
        } else {
            return response()->json(['status' => 'error', 'code' => 400, 'msg' => $result['msg'], 'options' => $result['options']]);
        }
    }

}
