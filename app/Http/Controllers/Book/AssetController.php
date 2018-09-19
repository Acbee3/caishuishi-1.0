<?php

namespace App\Http\Controllers\Book;

use App\Entity\Asset;
use App\Entity\Company;
use App\Http\Controllers\Controller;
use App\Models\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * 资产操作控制器
 * Class AssetControllerer
 * @package App\Http\Controllers\Book
 */
class AssetController extends Controller
{
    /**
     * 获取资产类型
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAssetType()
    {
        $result = config('asset.asset_type');
        return !empty($result) ? Common::apiSuccess($result) : Common::apiFail();
    }

    /**
     * 获取资产类别
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAssetZclb(Request $request)
    {
        $result = config('asset.' . $request->zclx . '_type');
        return Common::apiSuccess($result);
    }

    /**
     * 资产 折旧摊销列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAssetList(Request $request)
    {
        $list = Asset::getAssetList($request);
        if ($request->ajax()) return $list;
        return view('book.asset.asset');
    }


    /**
     * 保存资产
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAsset(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'zcmc' => 'required', 'zclx' => 'required', 'zclb' => 'required',
                'num' => 'required', 'rzrq' => 'required', 'zjff' => 'required',
                'zjqx' => 'required', 'yzkm' => 'required', 'yzkm_id' => 'required',
                'ljzjkm' => 'required', 'ljzjkm_id' => 'required', 'cbfykm' => 'required',
                'cbfykm_id' => 'required', 'yz' => 'required', 'cz' => 'required',
            ], [
                'zcmc.required' => '缺少参数：资产名称', 'zclx.required' => '缺少参数：资产类型',
                'zclb.required' => '缺少参数：资产类别', 'num.required' => '缺少参数：数量',
                'rzrq.required' => '缺少参数：认证日期', 'zjff.required' => '缺少参数：折旧方法',
                'zjqx.required' => '缺少参数：折旧期限', 'yzkm.required' => '缺少参数：原值科目',
                'yzkm_id.required' => '缺少参数：原值科目编码', 'ljzjkm.required' => '缺少参数：累计折旧科目',
                'ljzjkm_id.required' => '缺少参数：累计折旧科目编码', 'cbfykm.required' => '缺少参数：成本费用科目',
                'cbfykm_id.required' => '缺少参数：成本费用科目编码', 'yz.required' => '缺少参数：原值',
                'cz.required' => '缺少参数：残值率',
            ]);

            $company = Company::sessionCompany();
            $param = array_merge((array)($request->all()), [
                'company_id' => $company->id,
            ]);

            $data = Asset::storeAsset($param);

            return Common::apiSuccess($data);
        } catch (\Exception $e) {
            return Common::apiFail($e->getMessage());
        }
    }

    /**
     * 资产 折旧摊销删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delAsset(Request $request)
    {
        $result = Asset::del($request);
        return $result === true ? Common::apiSuccess() : Common::apiFail($result);
    }
}
