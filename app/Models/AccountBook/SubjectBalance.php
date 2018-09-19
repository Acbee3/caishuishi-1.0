<?php

namespace App\Models\AccountBook;

use App\Models\AccountSubject;
use Illuminate\Database\Eloquent\Model;

/**
 * 科目余额表
 * Class SubjectBalance
 * @package App\Models\AccountBook
 */
class SubjectBalance extends Model
{

    const ACCOUNT_CLOSED_YES = 1; //已结账
    const ACCOUNT_CLOSED_NO = 0; //未结账

    public $table = 'subject_balances';
    protected $guarded = [];

    //relation
    public function AccountSubject()
    {
        return $this->hasOne(AccountSubject::class, 'id', 'account_subject_id');
    }

    //关联子科目余额
    public function subjectBalanceItem()
    {
        return $this->hasMany(SubjectBalance::class, 'subject_pid', 'account_subject_id');
    }

    //关联父科目余额
    public function subjectBalanceParent()
    {
        return $this->belongsTo(SubjectBalance::class, 'subject_pid', 'account_subject_id');
    }
}
