<?php

namespace App\Models;

use App\Entity\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Validator;

class BussinessData extends Model
{
    protected $guarded = [];

    const CUSTOMER = 1;
    const SUPPLIER = 2;
    const OTHERCONTACT = 3;
    const INVESTOR = 4;

    const FREEZE = 0;
    const USED = 1;

    public static function getStatus()
    {
        return [
            self::FREEZE => '冻结',
            self::USED => '正常',
        ];
    }

    public static function getType()
    {
        return [
            self::CUSTOMER => '客户',
            self::SUPPLIER => '供应商',
            self::OTHERCONTACT => '其他往来',
            self::INVESTOR => '投资方',
        ];
    }

    //relation
    public function account_subjects()
    {
        return $this->belongsToMany(AccountSubject::class, 'bussiness_datas_account_subjects', 'bussiness_datas_id', 'account_subjects_id');
    }

    /**
     * 列表
     * @param $request
     * @return mixed
     */
    public static function bussinessdataList($request)
    {
        new Company();
        $query = BussinessData::where('company_id', Company::$company->id);
        isset($request->type) && $query->where('type', $request->type);
        isset($request['company_id']) && $query->where('company_id', $request['company_id']);
        isset($request->keyword) && $query->where('name', 'like', '%' . $request->keyword . '%');
        $list = $query->with('account_subjects')->get();
        return $list;
    }

    /**
     * 新增业务数据
     * @param $data
     * @return bool|string
     */
    public static function createBussinessdata($data)
    {
        new Company();
        $rules = [
            'name' => 'required',
            'type' => 'required',
            'subject1' => 'required',
        ];
        $messages = [
            'name.required' => '请输入' . self::getType()[$data['type']] . '名称',
            'subject1.required' => '请从对应科目1开始选择',
        ];
        $validator = Validator::make($data, $rules, $messages, []);
        if ($validator->fails()) {
            return $validator->messages()->first();
        }
        $unique = BussinessData::where('company_id', Company::$company->id)->where('name', $data['name'])->where('type', $data['type'])->first();
        if ($unique) return self::getType()[$data['type']] . '名称已存在';
        DB::beginTransaction();
        try {
            $bussinessdata = [];
            $bussinessdata['name'] = $data['name'];
            $bussinessdata['company_id'] = Company::$company->id;
            $bussinessdata['short_name'] = $data['short_name'];
            $bussinessdata['type'] = $data['type'];
            $subject[] = $data['subject1'];
            $subject[] = $data['subject2'];
            $subject[] = $data['subject3'];
            $subject[] = $data['subject4'];
            $subject[] = $data['subject5'];
            $subject = array_unique(array_filter($subject));
            $new = BussinessData::create($bussinessdata);
            $new->account_subjects()->sync($subject);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return '操作失败';
        }
    }

    /**
     * 修改业务数据
     * @param BussinessData $bussinessData
     * @param $data
     * @return bool|string
     */
    public static function editBussinessdata(BussinessData $bussinessData, $data)
    {
        new Company();
        if (!$bussinessData) return '操作失败';
        $rules = [
            'name' => 'required',
            'type' => 'required',
            'subject1' => 'required',
        ];
        $messages = [
            'name.required' => '请输入' . self::getType()[$data['type']] . '名称',
            'subject1.required' => '请从对应科目1开始选择',
        ];
        $validator = Validator::make($data, $rules, $messages, []);
        if ($validator->fails()) {
            return $validator->messages()->first();
        }
        $unique = BussinessData::where('company_id', Company::$company->id)->where('name', $data['name'])->where('type', $data['type'])->where('id', '<>', $bussinessData->id)->first();
        if ($unique) return self::getType()[$data['type']] . '名称已存在';
        DB::beginTransaction();
        try {
            $bussinessdatas = [];
            $bussinessdatas['name'] = $data['name'];
            $bussinessdatas['company_id'] = Company::$company->id;
            $bussinessdatas['short_name'] = $data['short_name'];
            $bussinessdatas['type'] = $data['type'];
            $subject[] = $data['subject1'];
            $subject[] = $data['subject2'];
            $subject[] = $data['subject3'];
            $subject[] = $data['subject4'];
            $subject[] = $data['subject5'];
            $subject = array_filter($subject);
            $bussinessData->update($bussinessdatas);
            $bussinessData->account_subjects()->sync($subject);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return '操作失败';
        }
    }

    /**
     * 业务数据冻结
     * @param $request
     * @return bool|string
     */
    public static function freeze($request)
    {
        $bussinessdata = BussinessData::find($request->id);
        if (!$bussinessdata) return '操作失败';
        if ($request->status == $bussinessdata->status) {
            return true;
        } else {
            $bussinessdata->status = $request->status;
            $bussinessdata->save();
            return true;
        }
    }

    /**
     * 删除业务数据
     * @param $request
     * @return bool|string
     */
    public static function del($request)
    {
        $data = $request->data;
        if (!$data) return '请选择要删除的记录';
        DB::beginTransaction();
        try {
            $result = BussinessData::whereIn('id', $data)->get();
            foreach ($result as $v) {
                $v->account_subjects()->sync([]);
                $v->delete();
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return '操作失败';
        }
    }

    /**
     * 获取单位信息
     * @param $name
     */
    public static function getCompanyByName($name)
    {
        return $company = BussinessData::query()->where('name', $name)->firstOrCreate([
            'company_id' => Company::sessionCompany()->id,
            'name' => $name,
            'short_name' => $name,
            'status' => BussinessData::USED,
            'type' => BussinessData::CUSTOMER,
        ]);
    }

}
