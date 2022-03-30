<?php

namespace App\Http\Controllers\Api\Back;

use App\Exceptions\BaseException;
use App\Http\Controllers\Controller;
use App\Models\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    public function down(Request $request)
    {
        $params = $request->all();

        // $s = Storage::url($params['file_path']);
        // var_dump($s);exit;

        $file_exists = Storage::disk('public')->exists($params['file_path']);


        if (!$file_exists) {
            throw new BaseException(['msg' => '文件不存在']);
        }

        return Storage::disk('public')->download($params['file_path']);
    }

    public function upload(Request $request)
    {
        $params = $request->all();

        $path = [];

        $basket = $params['basket'];

        $file = $request->file('file');

        // 存储文件
        $path = $file->storeAs($basket . '/' . date('Ym'), time() . '-' . $file->getClientOriginalName(), 'public');

        // 写入数据库
        $insert_data = [
            'storage' => 0,
            'file_url' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'file_size_m' => round($file->getSize()/1024/1024, 2) . 'M',
            'file_type' => $file->getMimeType(),
            'real_name' => $file->getClientOriginalName(),
            'extension' => $file->extension(),
        ];

        $res = UploadFile::create($insert_data);

        $pre_path = $request->server('REQUEST_SCHEME') . '://' .$request->server('HTTP_HOST');

        $url = $pre_path . Storage::url($res['file_url']);

        $res->src = $url;

        return response()->json($this->response_data($res));
    }

    public function uploads(Request $request)
    {
        $params = $request->all();

        $path = [];

        $res_arr = [];

        $basket = $params['basket'];

        $files = $request->file('file');

        foreach ($files as $key => $value) {
            // 存储文件
            $path = $file->storeAs($basket . '/' . date('Ym'), time() . '-' . $file->getClientOriginalName(), 'public');

            // 写入数据库
            $insert_data = [
                'storage' => 0,
                'file_url' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'file_type' => $file->getMimeType(),
                'real_name' => $file->getClientOriginalName(),
                'extension' => $file->extension(),
            ];

            $res = UploadFile::create($insert_data);

            $res_arr[] = $res;   
        }

        return response()->json($this->response_data($res_arr));
    }    
}
