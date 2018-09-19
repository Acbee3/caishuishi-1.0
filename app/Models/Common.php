<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class Common
{
    const PAGE_SIZE = 20; //列表分页条数

    /**
     * 获取当前登录 auth
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard|null
     */
    public static function loginAuth()
    {
        $auth = null;
        $prifix = \request()->route()->action['prefix'];
        $prifix == 'admin' && !empty(\Auth::guard('admin')) && $auth = \Auth::guard('admin');
        $prifix == 'agent' && !empty(\Auth::guard('agent')) && $auth = \Auth::guard('agent');
        $prifix == 'book' && !empty(\Auth::guard('book')) && $auth = \Auth::guard('book');

        //以代账公司身份进入账簿
        $prifix == 'book' && $auth == null && !empty(\Auth::guard('agent')) && $user = \Auth::guard('agent');

        return $auth;
    }

    //获取当前登录用户
    public static function loginUser()
    {
        $user = null;
        $prifix = \request()->route()->action['prefix'];
        $prifix == 'admin' && !empty(\Auth::guard('admin')->user()) && $user = \Auth::guard('admin')->user();
        $prifix == 'agent' && !empty(\Auth::guard('agent')->user()) && $user = \Auth::guard('agent')->user();
        $prifix == 'book' && !empty(\Auth::guard('book')->user()) && $user = \Auth::guard('book')->user();

        //以代账公司身份进入账簿
        $prifix == 'book' && $user == null && !empty(\Auth::guard('agent')->user()) && $user = \Auth::guard('agent')->user();

        return $user;
    }

    //获取当前登录用户id
    public static function loginUserId()
    {
        $user = Common::loginUser();
        return !empty($user->id) ? $user->id : null;
    }

    /**
     * 判断请求方式
     * @param Request $request
     * @return bool
     */
    public static function isPost(Request $request)
    {
        if (strtolower($request->method()) == "post") {
            return true;
        }
        return false;
    }

    /**
     * 检查上传文件后缀名是否符合指定类型
     * @param UploadedFile $file
     * @param Mixed $type 类型
     * @return bool
     */
    public static function checkFile(UploadedFile $file, $type)
    {
        if (null == $file)
            return false;

        $origin_file_name = explode('.', $file->getClientOriginalName())[0];
        $ext = $file->extension() ? $file->extension() : explode('.', $file->getClientOriginalName())[1];

        if (is_string($type))
            return strtolower($ext) == strtolower($type);

        if (is_array($type)) {
            $type = array_map(function ($v) {
                return strtolower($v);
            }, $type);
            return in_array(strtolower($ext), $type);
        }

        return false;
    }

    /**
     * CFS系统对象转数组
     * @param $obj
     * @return array|void
     */
    public static function cfs_object_to_array($obj)
    {
        $obj = (array)$obj;
        foreach ($obj as $k => $v) {
            if (gettype($v) == 'resource') {
                return;
            }
            if (gettype($v) == 'object' || gettype($v) == 'array') {
                $obj[$k] = (array)($v);
            }
        }

        return $obj;
    }

    /**
     * CFS数组转对象
     * @param $array
     * @return StdClass
     */
    public static function cfs_array_to_object($array)
    {
        if (gettype($array) != 'array') return;
        foreach ($array as $k => $v) {
            if (gettype($v) == 'array' || getType($v) == 'object')
                $array[$k] = (object)Common::cfs_array_to_object($v);
        }
        return (object)$array;
    }

    /**
     * 处理权限列表 父类 数组
     * @param $id
     * @return mixed
     */
    public static function get_rolelistsArr($id)
    {
        $res = DB::table("role_lists")->whereRaw('status = "yes" and parent_id = "0"')->orderBy('sort_order', 'Asc')->get();

        //$res = $res->toArray();
        //$res = Common::cfs_object_to_array($res);

        // 取角色表 $id 对应的权限信息
        if ($id > 0) {
            $arr = DB::table('roles')->where('id', $id)->value('role_list');
        } else {
            $arr = '--';
        }

        foreach ($res as $key => $v) {
            $list_arr[$key]['id'] = $v->id;
            $list_arr[$key]['action_name'] = $v->action_name;
            $list_arr[$key]['action_code'] = $v->action_code;
            $list_arr[$key]['cat_arr'] = Common::get_rolelists_catarr($v->id, $arr);

            // 用于设置权限选中状态
            if ($arr == 'all') {
                $list_arr[$key]['action_checked'] = 'true';
            } else {
                if (strpos($arr, $v->action_code) !== false) {
                    $list_arr[$key]['action_checked'] = 'true';
                } else {
                    $list_arr[$key]['action_checked'] = 'false';
                }
            }
        }

        if (isset($list_arr)) {
            return $list_arr;
        }

    }

    /**
     * 处理权限列表 子类 数组
     * @param $parent_id
     * @param $arr
     * @return mixed
     */
    public static function get_rolelists_catarr($parent_id, $arr)
    {
        $res = DB::table("role_lists")->whereRaw('status = "yes" and parent_id = ' . $parent_id)->orderBy('sort_order', 'Asc')->get();

        //$res = $res->toArray();
        //$res = Common::cfs_object_to_array($res);

        foreach ($res as $key => $v) {
            $list_arr[$key]['id'] = $v->id;
            $list_arr[$key]['action_name'] = $v->action_name;
            $list_arr[$key]['action_code'] = $v->action_code;
            $list_arr[$key]['cat_arr'] = '';

            // 用于设置权限选中状态
            if ($arr == 'all') {
                $list_arr[$key]['action_checked'] = 'true';
            } else {
                if (strpos($arr, $v->action_code) !== false) {
                    $list_arr[$key]['action_checked'] = 'true';
                } else {
                    $list_arr[$key]['action_checked'] = 'false';
                }
            }
        }

        if (isset($list_arr)) {
            return $list_arr;
        }
    }


    /**
     *  检查权限代码表 session 是否存在  因权限列表包含选中状态（不适合用session）    暂未使用
     */
    public static function check_rolelists_session()
    {
        if (session()->has('Rolelists_sess')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * api返回成功
     * @param array $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function apiSuccess($data = [], $message = '操作成功')
    {
        return response()->json(['status' => 1, 'info' => $message, 'data' => $data]);
        exit();
    }

    /**
     * api返回失败
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function apiFail($message = '操作失败')
    {
        return response()->json(['status' => 0, 'info' => $message]);
        exit();
    }

    /**
     * 导出数据为excel
     * @param array $data 二维数组
     * @param string $table_name 后缀名
     * @param string $suffix 后缀名
     * @param string $sheet 后缀名
     */
    public static function exportExcel($data, $table_name, $suffix = 'xls', $sheet = 'sheet1')
    {
        $file_name = iconv('UTF-8', 'GBK', $table_name . '_' . now()->toDateTimeString());
        $sheet_title = iconv('UTF-8', 'GBK', $sheet);

        Excel::create($file_name, function ($excel) use ($data, $sheet_title) {
            $excel->sheet($sheet_title, function ($sheet) use ($data) {
                $sheet->rows($data);
            });
        })->export($suffix);//export  download
        exit();
    }

    /**
     * 导出数据为csv
     * 文件流方法导入
     * @param $data
     */
    public static function streamExportExcel($data)
    {

    }

    /**
     * 根据请求返回数据
     * @param $request
     * @return array|bool
     */
    public static function importDataByExcel(Request $request)
    {
        if (!$request->hasFile('file'))
            return false;

        $data = [];
        Excel::load($request->file('file')->path(), function ($reader) use (&$data) {
            $data = $reader->getSheet(0)->toArray();
        });

        foreach ($data as $key => $datum) {
            if (self::checkRowNull($datum))
                unset($data[$key]);
        }

        return $data;
    }

    /**
     * 检查每行数据是否完整
     * 如果数据列全部为 null 返回 true | 否则返回 false
     * @param $row
     */
    private static function checkRowNull($row)
    {
        foreach ($row as $item) {
            if (!empty($item))
                return false;
        }
        return true;
    }

    /**
     * 过滤不在数据库的字段
     * @param string $table
     * @param array $param
     * @return array
     */
    public static function filterColumn(string $table, array $param)
    {
        foreach ($param as $key => $item) {
            if (!Schema::hasColumn($table, $key)) {
                unset($param[$key]);
            } else {
                (Schema::getColumnType($table, $key) == 'integer' || Schema::getColumnType($table, $key) == 'bigint')
                && $param[$key] = intval($param[$key]);
                $param[$key] = strval($param[$key]);
            }
        }
        return $param;
    }

    /**
     * 数字金额转换成中文大写金额的函数
     * 小数位为两位
     * @param $num 要转换的小写数字或小写字符串
     * @return string 大写字母
     */
    public static function num_to_rmb($num)
    {
        $c1 = "零壹贰叁肆伍陆柒捌玖";
        $c2 = "分角元拾佰仟万拾佰仟亿";
        //精确到分后面就不要了，所以只留两个小数位
        $num = round($num, 2);
        //将数字转化为整数
        $num = $num * 100;
        if (strlen($num) > 10) {
            return "金额太大，请检查";
        }
        $i = 0;
        $c = "";
        while (1) {
            if ($i == 0) {
                //获取最后一位数字
                $n = substr($num, strlen($num) - 1, 1);
            } else {
                $n = $num % 10;
            }
            //每次将最后一位数字转化为中文
            $p1 = substr($c1, 3 * $n, 3);
            $p2 = substr($c2, 3 * $i, 3);
            if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
                $c = $p1 . $p2 . $c;
            } else {
                $c = $p1 . $c;
            }
            $i = $i + 1;
            //去掉数字最后一位了
            $num = $num / 10;
            $num = (int)$num;
            //结束循环
            if ($num == 0) {
                break;
            }
        }
        $j = 0;
        $slen = strlen($c);
        while ($j < $slen) {
            //utf8一个汉字相当3个字符
            $m = substr($c, $j, 6);
            //处理数字中很多0的情况,每次循环去掉一个汉字“零”
            if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
                $left = substr($c, 0, $j);
                $right = substr($c, $j + 3);
                $c = $left . $right;
                $j = $j - 3;
                $slen = $slen - 3;
            }
            $j = $j + 3;
        }
        //这个是为了去掉类似23.0中最后一个“零”字
        if (substr($c, strlen($c) - 3, 3) == '零') {
            $c = substr($c, 0, strlen($c) - 3);
        }
        //将处理的汉字加上“整”
        if (empty($c)) {
            return "零元整";
        } else {
            return $c . "整";
        }
    }

    /**
     * 汉字转化首字母
     * @param $string
     * @return string
     */
    public static function get_letter($string)
    {
        $charlist = self::mb_str_split($string);
        return implode(array_map([self::class, "getfirstchar"], $charlist));
    }

    /**
     * 汉字转化首字母 辅助方法
     * @param $string
     * @return array[]|false|string[]
     */
    private static function mb_str_split($string)
    {
        // Split at all position not after the start: ^
        // and not before the end: $
        return preg_split('/(?<!^)(?!$)/u', $string);
    }

    /**
     * 汉字转化首字母 辅助方法
     * @param $s0
     * @return null|string
     */
    private static function getfirstchar($s0)
    {
        $fchar = ord(substr($s0, 0, 1));
        if (($fchar >= ord("a") and $fchar <= ord("z")) or ($fchar >= ord("A") and $fchar <= ord("Z")))
            return strtoupper(chr($fchar));

        $s = iconv("UTF-8", "GBK", $s0);

        dd($s{0}, $s{1});

        if (empty($s{0}) || empty($s{1}))
            return null;

        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if ($asc >= -20319 and $asc <= -20284) return "A";
        if ($asc >= -20283 and $asc <= -19776) return "B";
        if ($asc >= -19775 and $asc <= -19219) return "C";
        if ($asc >= -19218 and $asc <= -18711) return "D";
        if ($asc >= -18710 and $asc <= -18527) return "E";
        if ($asc >= -18526 and $asc <= -18240) return "F";
        if ($asc >= -18239 and $asc <= -17923) return "G";
        if ($asc >= -17922 and $asc <= -17418) return "H";
        if ($asc >= -17417 and $asc <= -16475) return "J";
        if ($asc >= -16474 and $asc <= -16213) return "K";
        if ($asc >= -16212 and $asc <= -15641) return "L";
        if ($asc >= -15640 and $asc <= -15166) return "M";
        if ($asc >= -15165 and $asc <= -14923) return "N";
        if ($asc >= -14922 and $asc <= -14915) return "O";
        if ($asc >= -14914 and $asc <= -14631) return "P";
        if ($asc >= -14630 and $asc <= -14150) return "Q";
        if ($asc >= -14149 and $asc <= -14091) return "R";
        if ($asc >= -14090 and $asc <= -13319) return "S";
        if ($asc >= -13318 and $asc <= -12839) return "T";
        if ($asc >= -12838 and $asc <= -12557) return "W";
        if ($asc >= -12556 and $asc <= -11848) return "X";
        if ($asc >= -11847 and $asc <= -11056) return "Y";
        if ($asc >= -11055 and $asc <= -10247) return "Z";
        return null;
    }


}
