<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    /**
     * 图片上传
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function img(Request $request)
    {
        $imgbase64 = $request->imgbase64;
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $imgbase64, $result)) {
            $type = $result[2];
            $type = ($type == 'jpeg') ? 'jpg' : $type;

            $img_name = date("Ymd") . "/" . uniqid() . '.jpg';

            $img = base64_decode(str_replace($result[1], '', $imgbase64));

            $disk = \Storage::disk('qiniu'); //使用七牛云上传
            $return = $disk->put($img_name, $img);//上传
            $img_url = "http://" . env("QINIU_DOMAIN") . "/" . $img_name;
            if ($return) {
                return response()->json(['result' => true, 'url' => $img_url]);
            }

        }
        return response()->json(['result' => false, 'message' => '上传失败！']);
    }


    /**
     * 上传
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function file(Request $request)
    {
        if (Common::isPost($request) && $request->hasFile('file')) {
            $allow = ['jpg', 'png', 'jpeg', 'gif', 'doc', 'docx', 'pdf', 'xls', 'xlsx', 'csv', 'ttf'];
            $file = $request->file('file');

            //dd($file);

            $file_path = $file->path();
            $origin_file_name = explode('.', $file->getClientOriginalName())[0];
            $ext = $file->extension() ? $file->extension() : explode('.', $file->getClientOriginalName())[1];

            //$file_name = $origin_file_name . '_' . $file->hashName();
            $hash = explode('.', $file->hashName())[0];
            $file_name = $origin_file_name . '_' . $hash . '.' . $ext;
            //$file_name = $origin_file_name . '.' . $ext;

            //dd($ext);
            //dd($file->hashName());

            if (!in_array($ext, $allow)) {
                echo '<font color="red">文件类型不支持</font>';
            }

            $disk = \Storage::disk('qiniu'); //使用七牛云上传
            $return = $disk->putFileAs('/common/', $file, $file_name);//上传
            $qiniu_file_url = "http://" . env("QINIU_DOMAIN") . "/common/" . $file_name;

            return Common::apiSuccess([$qiniu_file_url]);

        }
        return view('admin.upload.file');
    }


}
