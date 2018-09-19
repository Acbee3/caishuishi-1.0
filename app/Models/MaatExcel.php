<?php
/**
 * FileName: MaatExcel.php
 * Created by PhpStorm.
 * User: Administrator
 * DateTime: 2018/7/4-14:16
 * E_mail: newsboy9248@163.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
//use Storage;

class MaatExcel extends Model
{
    /**
     * 公共Excel文件导出
     * @param $table_name
     * @param $title
     * @param $cellData
     * @param $table_sort  文件后缀类型  xls  csv
     */
    public static function export($table_name, $st_title, $cellData, $table_sort)
    {
        $now_time = now()->toDateTimeString();
        $table_name = $table_name . '_' . $now_time;

        $table_name = iconv('UTF-8', 'GBK', $table_name);
        $st_title = iconv('UTF-8', 'GBK', $st_title);

        Excel::create($table_name, function ($excel) use ($cellData) {
            $excel->sheet('$st_title', function ($sheet) use ($cellData) {
                $sheet->rows($cellData);
            });
        })->export($table_sort);//export  download
    }


    /**
     * 导出员工信息 自定义样式（专用）
     * @param $cellData
     * @param $table_sort
     */
    public static function Export_Employee($cellData, $table_sort)
    {
        $now_time = now()->toDateTimeString();
        $table_name = "员工信息_" . $now_time;

        Excel::create($table_name, function ($excel) use ($cellData) {
            $excel->sheet('员工', function ($sheet) use ($cellData) {
                //$sheet->rows($cellData);

                // 主体数据记录数
                $tot = count($cellData);

                // 设置列宽 字体大小
                $sheet->setWidth(array(
                    'A' => 15,
                    'B' => 20,
                    'C' => 15,
                    'D' => 30,
                    'E' => 20,
                    'F' => 20,
                    'G' => 20,
                    'H' => 20,
                    'I' => 20,
                    'J' => 15,
                    'K' => 30,
                ))->rows($cellData)->setFontSize(11);

                //$sheet->prependRow('');

                // 合并单元格
                $sheet->mergeCells('A1:K1');

                // 主标题、副标题 样式
                $sheet->cells('A1:K2', function ($cells) {
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                    //$cells->setBackground('#f5f5f5');
                });

                // 证件号码会转成科学计数的问题处理
                /*$sheet->setColumnFormat(array(
                    'E3:E' . ($tot - 0) => '0000'
                ));*/

                // 数据内容主题 左对齐
                $sheet->cells('A3:K' . ($tot - 0), function ($cells) {
                    $cells->setAlignment('left');
                });
            });
        })->export($table_sort);
    }

    /**
     * CFS系统对象转数组
     * @param $obj
     * @return array|void
     *
     * laravel 自有方法 $reader->toArray();
     */
    public static function excel_object_to_array($obj)
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
     *
     * laravel 自有方法 $reader->toObject();
     */
    public static function excel_array_to_object($array)
    {
        if (gettype($array) != 'array') return;
        foreach ($array as $k => $v) {
            if (gettype($v) == 'array' || getType($v) == 'object')
                $array[$k] = (object)MaatExcel::excel_array_to_object($v);
        }
        return (object)$array;
    }

    // 导入员工信息
    public static function Import_Employee($excel_file_path, $cfs_file, $type_Name)
    {
        \Log::info('导入员工后台处理部分功能未完善(MaatExcel.php141行)。' . $excel_file_path);
        \Log::info('(MaatExcel.php142行)。' . $cfs_file);
        \Log::info('(MaatExcel.php143行)。' . $type_Name);

        /*$realPath = $excel_file_path;
        $tabl_name = date('YmdHis').mt_rand(100,999);//ceil(microtime(true) * 1000)

        $storage_path = storage_path('/upload/static/excel');
        if (!file_exists($storage_path)) {
            mkdir($storage_path, 0777, true);
        }

        $filename = $storage_path.'/'.$tabl_name.'.'.$type_Name;
        //$path = $realPath->move(base_path().'/'.$storage_path, $filename);
        Storage::copy($realPath, $filename);
        Log::info($path);*/

        //移动一个已上传的文件
        /*if ($realPath->move($storage_path, $filename) == true) {
            $create_path = base_path().'/upload/static/excel/'.$filename;

            Log::info($create_path);
        }*/


        /*$res = [];
        Excel::load($excel_file_path, function($reader) use( &$res ) {
            $reader = $reader->getSheet(0);
            $res = $reader->toArray();
        });
        for($i = 1;$i<count($res);$i++){

        }*/

    }

    /**
     * 导出员工薪酬信息  导出正常工资薪酬  自定义样式（专用）
     * @param $cellData
     * @param $table_sort
     * 表格相关设置 参考： https://blog.csdn.net/u011132987/article/details/52443559
     */
    public static function ExportSalaryEmployee_A($cellData, $table_sort)
    {
        $now_time = now()->toDateTimeString();
        $table_name = "工资表_" . $now_time;

        Excel::create($table_name, function ($excel) use ($cellData) {
            $excel->sheet('工资表', function ($sheet) use ($cellData) {

                // 主体数据记录数
                $tot = count($cellData);

                // 设置列宽 字体大小
                $sheet->setWidth(array(
                    'A' => 10,
                    'B' => 20,
                    'C' => 15,
                    'D' => 15,
                    'E' => 15,
                    'F' => 15,
                    'G' => 15,
                    'H' => 15,
                    'I' => 15,
                    'J' => 15,
                    'K' => 15,
                    'L' => 15,
                    'M' => 15,
                    'N' => 15,

                ))->rows($cellData)->setFontSize(11);

                //$sheet->prependRow('');

                // 合并  行单元格
                $sheet->mergeCells('A1:N1');
                $sheet->mergeCells('A2:N2');
                $sheet->mergeCells('E3:I3');

                // 合并 列单元格
                $sheet->mergeCells('A3:A4');
                $sheet->mergeCells('B3:B4');
                $sheet->mergeCells('C3:C4');
                $sheet->mergeCells('D3:D4');
                $sheet->mergeCells('J3:J4');
                $sheet->mergeCells('K3:K4');
                $sheet->mergeCells('L3:L4');
                $sheet->mergeCells('M3:M4');
                $sheet->mergeCells('N3:N4');

                // 设置行高
                $sheet->setHeight(1, 30);

                // 第一行 样式
                $sheet->cells('A1', function ($cells) {
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(14);
                    $cells->setValignment('middle');
                });

                // 第二行 样式
                $sheet->cells('A2', function ($cells) {
                    $cells->setAlignment('right');
                    $cells->setFontWeight('bold');
                    $cells->setValignment('middle');
                });

                // 第三、四行 样式
                $sheet->cells('A3:N4', function ($cells) {
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                    //$cells->setFontSize(12);
                    $cells->setValignment('middle');
                });

                // 数据内容主题 左对齐
                $sheet->cells('A5:C' . ($tot - 0), function ($cells) {
                    $cells->setAlignment('left');
                });

                // 设置右对齐
                $sheet->cells('D5:N' . ($tot - 0), function ($cells) {
                    $cells->setAlignment('right');
                });

                // 保留2位小数 ¥
                $sheet->setColumnFormat(array(
                    'D5:M' . ($tot - 0) => '0.00'
                ));

                // 设置边框
                $sheet->setBorder('A1:N' . ($tot), 'thin');
            });
        })->export($table_sort);

    }

    /**
     * 导出总账信息
     * @param $cellData
     * @param $table_sort
     */
    public static function Export_Ledger($cellData, $table_sort)
    {
        $now_time = now()->toDateTimeString();
        $table_name = "总账信息_" . $now_time;

        Excel::create($table_name, function ($excel) use ($cellData) {
            $excel->sheet('总账', function ($sheet) use ($cellData) {
                //$sheet->rows($cellData);

                // 主体数据记录数
                $tot = count($cellData);

                // 设置列宽 字体大小
                $sheet->setWidth(array(
                    'A' => 15,
                    'B' => 20,
                    'C' => 15,
                    'D' => 30,
                    'E' => 20,
                    'F' => 20,
                    'G' => 20,
                    'H' => 20,
                ))->rows($cellData)->setFontSize(11);

                // 合并单元格
                $sheet->mergeCells('A1:H1');
                $sheet->mergeCells('A2:H2');

                foreach ($cellData as $key => $v) {
                    if ($key > 0) {
                        $num = ($key + 1) / 3;
                        $num_s = $num * 3 + 1;
                        $num_e = $num * 3 + 3;
                        if (is_int($num) && $num_e <= $tot) {
                            $sheet->mergeCells('A' . $num_s . ':A' . $num_e);
                            $sheet->mergeCells('B' . $num_s . ':B' . $num_e);
                            $sheet->mergeCells('C' . $num_s . ':C' . $num_e);
                        }
                    }
                }


                // 设置行高
                $sheet->setHeight(1, 30);

                // 第一行 样式
                $sheet->cells('A1', function ($cells) {
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(14);
                    $cells->setValignment('middle');
                });

                // 第二行 样式
                $sheet->cells('A2', function ($cells) {
                    $cells->setAlignment('right');
                    $cells->setFontWeight('bold');
                    $cells->setValignment('middle');
                });

                $sheet->cells('A3:H3', function ($cells) {
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                    //$cells->setFontSize(12);
                    $cells->setValignment('middle');
                });
                $sheet->cells('A4:C' . ($tot - 0), function ($cells) {
                    $cells->setValignment('middle');
                });

                // 保留2位小数
                $sheet->setColumnFormat(array(
                    'E4:F' . ($tot - 0) => '0.00'
                ));
                $sheet->setColumnFormat(array(
                    'H4:H' . ($tot - 0) => '0.00'
                ));

                // 对齐
                $sheet->cells('A4:D' . ($tot - 0), function ($cells) {
                    $cells->setAlignment('left');
                });
                $sheet->cells('E4:F' . ($tot - 0), function ($cells) {
                    $cells->setAlignment('right');
                });
                $sheet->cells('G4:G' . ($tot - 0), function ($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->cells('H4:H' . ($tot - 0), function ($cells) {
                    $cells->setAlignment('right');
                });
            });
        })->export($table_sort);
    }
}