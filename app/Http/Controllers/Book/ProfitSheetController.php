<?php

namespace App\Http\Controllers\Book;

use App\Entity\Profit;
use App\Http\Controllers\Controller;
use App\Models\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * 利润表 控制器类
 * Class ProfitSheetController
 * @package App\Http\Controllers\Book
 */
class ProfitSheetController extends Controller
{

    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'company_id' => 'required',
                'fiscal_period' => 'required',
            ], [
                'company_id.required' => '缺少参数公司id:company_id',
                'fiscal_period.required' => '缺少参数会计区间:fiscal_period',
            ]);

            if ($validator->fails())
                throw new \Exception($validator->getMessageBag()->first());

            $data = Profit::makeTable($request->all());
            return Common::apiSuccess($data);
        } catch (\Exception $e) {
            return Common::apiFail($e->getMessage());
        }
    }

}
