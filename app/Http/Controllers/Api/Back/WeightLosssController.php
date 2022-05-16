<?php

namespace App\Http\Controllers\Api\Back;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use App\Models\UploadFile;
use App\Models\WeightLoss;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WeightLosssController extends Controller
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

        $root = $current_user->hasRole(Role::ROOT, app(Admin::class)->guardName());
        
        $where = [];

        if (!$root) {
            $where[] = [
                'admin_id', '=', $current_user['id']
            ];
        }

        if (isset($params['name'])) {
            $where[] = ['name', 'like', '%' . $params['name'] . '%'];
        }
        if (isset($params['id_card'])) {
            $where[] = ['id_card', '=', $params['id_card']];
        }
        if (isset($params['hospital_number'])) {
            $where[] = ['hospital_number', '=' . $params['hospital_number']];
        }

        $weight_loss = WeightLoss::where($where)->orderBy('id', 'desc')->paginate(20);

        return response()->json($this->response_page($weight_loss));
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
            $params['admission_time'] = strtotime($params['admission_time']);
        }
        if (isset($params['operative_time']) && $params['operative_time']) {
            $params['operative_time'] = strtotime($params['operative_time']);
        }
        if (isset($params['discharge_time']) && $params['discharge_time']) {
            $params['discharge_time'] = strtotime($params['discharge_time']);
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
        if (isset($params['perioperative_complications'])) {
            $params['perioperative_complications'] = implode(',', $params['perioperative_complications']);
        }
        if (isset($params['nonoperative_complication'])) {
            $params['nonoperative_complication'] = implode(',', $params['nonoperative_complication']);
        }
        if (isset($params['outcome'])) {
            $params['outcome'] = implode(',', $params['outcome']);
        }

        WeightLoss::create($params);

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
        $weight_loss = WeightLoss::find($id);

        $img_ids = explode(',', $weight_loss['img_id']);
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
        $weight_loss['img'] = $img_arr;

        $video = UploadFile::find($weight_loss['video_id']);
        $video_arr = [];
        if ($video) {
            $video_url = Storage::disk('public')->url($video['file_url']);
            $video_arr[] = [
                'name' => $video_url,
                'url' => $video_url,
            ];
        }

        $weight_loss['video'] = $video_arr;

        $attachment_ids = explode(',', $weight_loss['attachment_id']);
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
        $weight_loss['attachment'] = $attachment;  

        // 多选
        $stromal_tumor['perioperative_complications'] = explode(',', $weight_loss['perioperative_complications']); 
        $stromal_tumor['nonoperative_complication'] = explode(',', $weight_loss['nonoperative_complication']); 
        $stromal_tumor['outcome'] = explode(',', $weight_loss['outcome']); 

        return response()->json($this->response_data($weight_loss));
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
        $params = $request->all();

        $params['img_id'] = $params['img'] ?? '';
        unset($params['img']);

        $params['video_id'] = $params['video'] ?? 0;
        unset($params['video']);

        $params['attachment_id'] = $params['attachment'] ?? '';
        unset($params['attachment']);

        if (isset($params['admission_time']) && $params['admission_time']) {
            $params['admission_time'] = strtotime($params['admission_time']);
        }
        if (isset($params['operative_time']) && $params['operative_time']) {
            $params['operative_time'] = strtotime($params['operative_time']);
        }
        if (isset($params['discharge_time']) && $params['discharge_time']) {
            $params['discharge_time'] = strtotime($params['discharge_time']);
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
        if (isset($params['perioperative_complications'])) {
            $params['perioperative_complications'] = implode(',', $params['perioperative_complications']);
        }
        if (isset($params['nonoperative_complication'])) {
            $params['nonoperative_complication'] = implode(',', $params['nonoperative_complication']);
        }
        if (isset($params['outcome'])) {
            $params['outcome'] = implode(',', $params['outcome']);
        }

        WeightLoss::updateOrCreate(['id' => $id], $params);

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
}
