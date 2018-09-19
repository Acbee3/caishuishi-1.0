<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Entity\Company;
use Validator;


class BankAccount extends Model
{
    protected $guarded = [];
    const FREEZR = 0;
    const USED = 1;

    public static function getStatus()
    {
        return [
            self::FREEZR => '冻结',
            self::USED   => '正常',
        ];
    }

    //relation
    public function account_subject()
    {
        return $this->hasOne(AccountSubject::class, 'id', 'subject_id');
    }

    /**
     * 银行账户列表
     * @param $request
     * @return mixed
     */
    public static function bankaccountList($request)
    {
        new Company();
        $query = BankAccount::where('company_id', Company::$company->id)->orderBy('created_at', 'desc');
        if ($request->keyword) $query->where('name', 'like', '%'.$request->keyword.'%');
        $list = $query->with('account_subject')->get();
        return $list;
    }

    /**
     * 新增银行账户
     * @param $data
     * @return bool|string
     */
    public static function createBankaccount($data)
    {
        new Company();
        $rules = [
            'name'          => 'required',
            'subject_id'    => 'required',
        ];
        $messages = [
            'name.required'         => '请输入账户简称',
            'subject_id.required'   => '请选择对应科目',
        ];
        $validator = Validator::make($data, $rules, $messages, []);
        if ($validator->fails()) {
            return $validator->messages()->first();
        }
        $unique = BankAccount::where('company_id', Company::$company->id)->where('name', $data['name'])->first();
        if ($unique) return '名称已存在';
        $bankaccount = [];
        $bankaccount['name']          = $data['name'];
        $bankaccount['company_id']    = Company::$company->id;
        $bankaccount['subject_id']    = $data['subject_id'];
        $new = BankAccount::create($bankaccount);
        return $new ? true : '操作失败' ;
    }

    /**
     * 修改银行账户
     * @param BankAccount $bankAccount
     * @param $data
     * @return bool|string
     */
    public static function editBankaccount(BankAccount $bankAccount, $data)
    {
        new Company();
        if (!$bankAccount) return '操作失败';
        $rules = [
            'name'          => 'required',
            'subject_id'    => 'required',
        ];
        $messages = [
            'name.required'         => '请输入账户简称',
            'subject_id.required'   => '请选择对应科目',
        ];
        $validator = Validator::make($data, $rules, $messages, []);
        if ($validator->fails()) {
            return $validator->messages()->first();
        }
        $unique = BankAccount::where('company_id', Company::$company->id)->where('name', $data['name'])->where('id', '<>', $bankAccount->id)->first();
        if ($unique) return self::getType()[$data['type']].'名称已存在';
        $bankaccounts = [];
        $bankaccounts['name']      = $data['name'];
        $bankaccounts['company_id']= Company::$company->id;
        $bankaccounts['subject_id']= $data['subject_id'];
        $result = $bankAccount->update($bankaccounts);
        return $result ? true : '操作失败' ;
    }

    /**
     * 银行账户冻结
     * @param $request
     * @return bool|string
     */
    public static function freeze($request)
    {
        $bankaccount = BankAccount::find($request->id);
        if (!$bankaccount) return '操作失败';
        if ($request->status == $bankaccount->status){
            return true;
        } else {
            $bankaccount->status = $request->status;
            $bankaccount->save();
            return true;
        }
    }

    /**
     * 删除银行账户
     * @param $request
     * @return bool|string
     */
    public static function del($request)
    {
        $data = $request->data;
        if (!$data) return '请选择要删除的记录';
            $result = BankAccount::destroy($data);
        return $result ? true : '操作失败' ;
    }
}