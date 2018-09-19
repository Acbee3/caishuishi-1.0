<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Accounting\Department
 *
 * @property int $id 11
 * @property int $company_id 代账公司id
 * @property string $dept_name 部门名称
 * @property int $status 状态（暂停、启用）
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Department whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Department whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Department whereDeptName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Department whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Department whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Accounting\Department whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Department extends Model
{
    protected $table = 'department';
}
