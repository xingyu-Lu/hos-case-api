<?php

namespace App\Http\Controllers\Api\Back;

use App\Exceptions\BaseException;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ColorectalCancer;
use App\Models\Role;
use App\Models\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ColorectalCancersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();

        $current_user = auth('api')->user();

        // $root = $current_user->hasRole(Role::ROOT, app(Admin::class)->guardName());
        
        $where = [];

        // if (!$root) {
        //     $where[] = [
        //         'admin_id', '=', $current_user['id']
        //     ];
        // }

        if (isset($params['name'])) {
            $where[] = ['name', 'like', '%' . $params['name'] . '%'];
        }
        if (isset($params['id_card'])) {
            $where[] = ['id_card', '=', $params['id_card']];
        }
        if (isset($params['hospital_number'])) {
            $where[] = ['hospital_number', '=' . $params['hospital_number']];
        }

        $colorectal_cancer = ColorectalCancer::where($where)->orderBy('is_follow', 'desc')->orderBy('follow_time', 'asc')->orderBy('id', 'desc')->paginate(20);

        foreach ($colorectal_cancer as $key => $value) {
            $today_time = strtotime(date('Y-m-d'));
            $value_time = strtotime(date('Y-m-d', strtotime($value['follow_time'])));

            if ($value['is_follow']) {
                $value['follow_day_num'] = ($value_time-$today_time)/86400;
            } else {
                $value['follow_day_num'] = '';
            }
        }

        return response()->json($this->response_page($colorectal_cancer));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth('api')->user();

        $params = $request->all();

        $params['admin_id'] = $user['id'];

        $params['img_id'] = $params['img'] ?? '';
        unset($params['img']);

        $params['video_id'] = $params['video'] ?? 0;
        unset($params['video']);

        $params['attachment_id'] = $params['attachment'] ?? '';
        unset($params['attachment']);

        if (isset($params['admission_time']) && $params['admission_time']) {
            $params['date'] = strtotime(date('Y-m', strtotime($params['admission_time'])));
            $params['admission_time'] = strtotime($params['admission_time']);
        }
        if (isset($params['operative_time']) && $params['operative_time']) {
            $params['operative_time'] = strtotime($params['operative_time']);
        }
        if (isset($params['discharge_time']) && $params['discharge_time']) {
            $params['discharge_time'] = strtotime($params['discharge_time']);
        }
        if (isset($params['follow_time']) && $params['follow_time']) {
            $params['follow_time'] = strtotime($params['follow_time']);
        }

        // 总住院天数
        if (isset($params['admission_time']) && isset($params['discharge_time'])) {
            $admission_time = strtotime(date('Y-m-d', $params['admission_time']));
            $discharge_time = strtotime(date('Y-m-d', $params['discharge_time']));

            $params['total_hospital_days'] = ($discharge_time - $admission_time) / 86400;
        }

        // 术后住院天数
        if (isset($params['operative_time']) && isset($params['discharge_time'])) {
            $operative_time = strtotime(date('Y-m-d', $params['operative_time']));
            $discharge_time = strtotime(date('Y-m-d', $params['discharge_time']));

            $params['postoperation_hospital_days'] = ($discharge_time - $operative_time) / 86400;
        }

        // 多选
        if (isset($params['postoperative_complication_specific'])) {
            $params['postoperative_complication_specific'] = implode(',', $params['postoperative_complication_specific']);
        }

        ColorectalCancer::create($params);

        return response()->json($this->response_data());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $colorectal_cancer = ColorectalCancer::find($id);

        $img_ids = explode(',', $colorectal_cancer['img_id']);
        $img_arr = [];
        foreach ($img_ids as $key => $value) {
            $file = UploadFile::find($value);
            if ($file) {
                $img_arr[] = [
                    'name' => Storage::disk('public')->url($file['file_url']),
                    'url' => Storage::disk('public')->url($file['file_url'])
                ];
            }
        }
        $colorectal_cancer['img'] = $img_arr;

        $video = UploadFile::find($colorectal_cancer['video_id']);
        $video_arr = [];
        if ($video) {
            $video_url = Storage::disk('public')->url($video['file_url']);
            $video_arr[] = [
                'name' => $video_url,
                'url' => $video_url,
            ];
        }

        $colorectal_cancer['video'] = $video_arr;

        $attachment_ids = explode(',', $colorectal_cancer['attachment_id']);
        $attachment = [];
        foreach ($attachment_ids as $key => $value) {
            $file = UploadFile::find($value);
            if ($file) {
                $attachment[] = [
                    'name' => Storage::disk('public')->url($file['file_url']),
                    'url' => Storage::disk('public')->url($file['file_url'])
                ];
            }
        }
        $colorectal_cancer['attachment'] = $attachment;  

        // 多选
        $colorectal_cancer['postoperative_complication_specific'] = explode(',', $colorectal_cancer['postoperative_complication_specific']); 

        return response()->json($this->response_data($colorectal_cancer));
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
        $current_user = auth('api')->user();

        $colorectal_cancer = ColorectalCancer::find($id);

        if ($colorectal_cancer['admin_id'] != $current_user['id']) {
            throw new BaseException(['msg' => '非病例创建人，不能修改']);
        }

        $params = $request->all();

        $params['img_id'] = $params['img'] ?? '';
        unset($params['img']);

        $params['video_id'] = $params['video'] ?? 0;
        unset($params['video']);

        $params['attachment_id'] = $params['attachment'] ?? '';
        unset($params['attachment']);

        if (isset($params['admission_time']) && $params['admission_time']) {
            $params['date'] = strtotime(date('Y-m', strtotime($params['admission_time'])));
            $params['admission_time'] = strtotime($params['admission_time']);
        }
        if (isset($params['operative_time']) && $params['operative_time']) {
            $params['operative_time'] = strtotime($params['operative_time']);
        }
        if (isset($params['discharge_time']) && $params['discharge_time']) {
            $params['discharge_time'] = strtotime($params['discharge_time']);
        }
        if (isset($params['follow_time']) && $params['follow_time']) {
            $params['follow_time'] = strtotime($params['follow_time']);
        }

        // 总住院天数
        if (isset($params['admission_time']) && isset($params['discharge_time'])) {
            $admission_time = strtotime(date('Y-m-d', $params['admission_time']));
            $discharge_time = strtotime(date('Y-m-d', $params['discharge_time']));

            $params['total_hospital_days'] = ($discharge_time - $admission_time) / 86400;
        }

        // 术后住院天数
        if (isset($params['operative_time']) && isset($params['discharge_time'])) {
            $operative_time = strtotime(date('Y-m-d', $params['operative_time']));
            $discharge_time = strtotime(date('Y-m-d', $params['discharge_time']));

            $params['postoperation_hospital_days'] = ($discharge_time - $operative_time) / 86400;
        }

        // 多选
        if (isset($params['postoperative_complication_specific'])) {
            $params['postoperative_complication_specific'] = implode(',', $params['postoperative_complication_specific']);
        }

        ColorectalCancer::updateOrCreate(['id' => $id], $params);

        return response()->json($this->response_data());
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

    // 当天待随访人数
    public function followDayNum()
    {
        $res_data = [];

        $num = ColorectalCancer::where('is_follow', 1)->where('follow_time', '<=', strtotime(date('Y-m-d')))->count();

        $res_data = [
            'follow_day_num' => $num,
        ];

        return response()->json($this->response_data($res_data));
    }
}
