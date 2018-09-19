<?php

namespace App\Entity;

use App\Models\Accounting\Asset as AssetModel;
use App\Models\Accounting\AssetAlter;

/**
 * 资产类
 * Class Asset
 * @package App\Entity
 */
class Asset
{
    /**
     * 资产变动列表
     * @param $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getAssetAlterList($request)
    {
        new Company();
        $data = $request->all();
        $query = AssetAlter::with('voucher')->where('company_id', Company::sessionCompany()->id);
        foreach ($data as $k => $v) {
            if ($v) $query->where("$k", $v);
        }
        $list = $query->paginate(30);
        return $list;
    }

    /**
     * 资产 折旧摊销列表
     * @param $request
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getAssetList($request)
    {
        new Company();
        $query = AssetModel::with('voucher')->where('company_id', Company::sessionCompany()->id)->where('zclx', $request->zclx);
        if ($request->zclb) $query->where('zclb', $request->zclb);
        $list = $query->get();
        return $list;
    }

    /**
     * 保存 资产
     * @param $param
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function storeAsset($param)
    {
        !isset($param['id']) && $param['id'] = null;
        $model = \App\Models\Accounting\Asset::updateOrCreate(['id' => $param['id']], $param);
        $model = self::setStatus($model);
        $model->save();
        return $model;
    }

    /**
     * 更新折旧状态
     * @param $model
     * @return mixed
     */
    public static function setStatus($model)
    {
        if (!empty($model['yz']) && !empty($model['zcl']) && !empty($model['zc'])) {
            $model->status = intval($model['yz'] * $model['zcl'] / 100 == $model['zc']);
        }
        return $model;
    }

    public static function del($request)
    {
        $id = $request->id;
        $result = AssetModel::destroy($id);
        return $result ? true : '操作失败';
    }

}