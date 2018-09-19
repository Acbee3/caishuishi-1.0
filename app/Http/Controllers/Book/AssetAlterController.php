<?php

namespace App\Http\Controllers\Book;

use App\Entity\Asset;
use App\Models\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssetAlterController extends Controller
{
    /**
     * 资产 资产变动列表
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function assetAlterList(Request $request)
    {
        $list = Asset::getAssetAlterList($request);
        if ($request->ajax()) return $list;
        return view('book.asset.assetalter');
    }

    /**
     * 获取变动类型
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAssetBdlx()
    {
        $result = config('asset.assetalter_type');
        return !empty($result) ? Common::apiSuccess($result) : Common::apiFail();
    }
    
}
