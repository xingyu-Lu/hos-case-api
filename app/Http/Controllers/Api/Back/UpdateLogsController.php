<?php

namespace App\Http\Controllers\Api\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UpdateLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            [
                'content' => '直肠癌建表，部分后端接口等等',
                'timestamp' => '2022-05-09',
            ],
            [
                'content' => '直肠癌建表等等',
                'timestamp' => '2022-05-08',
            ],
            [
                'content' => '直肠癌开始建表等等',
                'timestamp' => '2022-05-07',
            ],
            [
                'content' => '胃ca加搜索，间质瘤等等',
                'timestamp' => '2022-04-14',
            ],
            [
                'content' => '胃ca加随访，部分单选框字段改为复选框等等',
                'timestamp' => '2022-04-13',
            ],
            [
                'content' => '胃ca前后联调，问题处理等等',
                'timestamp' => '2022-04-12',
            ],
            [
                'content' => '胃ca页面编写，后端接口编写等等',
                'timestamp' => '2022-04-11',
            ],
            [
                'content' => '胃ca建表，后端接口开始等等',
                'timestamp' => '2022-04-08',
            ],
            [
                'content' => '需求分析，胃ca建表等等',
                'timestamp' => '2022-04-07',
            ],
            [
                'content' => '病例需求了解等等',
                'timestamp' => '2022-04-06',
            ],
            [
                'content' => '新增病例类型，添加修改预览病例修改等等',
                'timestamp' => '2022-03-31',
            ],
            [
                'content' => '部署阿里云服务器，病例模块等等',
                'timestamp' => '2022-03-30',
            ],
            [
                'content' => '前端建代码仓库，系统基础搭建等等',
                'timestamp' => '2022-03-29',
            ],
            [
                'content' => '后端建代码仓库，后端基础搭建等等',
                'timestamp' => '2022-03-28',
            ],
        ];

        return response()->json($this->response_data($data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
