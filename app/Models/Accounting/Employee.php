<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Accounting\Employee
 *
 * @property int $id
 * @property int $company_id 代账公司id
 * @property string $employee_num 工号
 * @property string $employee_name 姓名
 * @property int|null $department_id 部门id
 * @property string $lxdh 联系电话
 * @property string $gender 性别
 * @property string $zjlx 证件类型
 * @property string $zjhm 证件号码
 * @property string $email 邮箱
 * @property string $address 地址
 * @property string $birthday 出生日期
 * @property string $remark 备注
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Employee whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Employee whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Employee whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Employee whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Employee whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Employee whereEmployeeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Employee whereEmployeeNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Employee whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Employee whereLxdh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Employee whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Employee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Employee whereZjhm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Employee whereZjlx($value)
 * @mixin \Eloquent
 */
class Employee extends Model
{
    protected $table = 'employee';
}
