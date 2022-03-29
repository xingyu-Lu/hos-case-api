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
