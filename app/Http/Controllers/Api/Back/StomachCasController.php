<?php

namespace App\Http\Controllers\Api\Back;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use App\Models\StomachCa;
use App\Models\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StomachCasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $current_user = auth('api')->user();

        $root = $current_user->hasRole(Role::ROOT, app(Admin::class)->guardName());
        
        $where = [];

        if (!$root) {
            $where[] = [
                'admin_id', '=', $current_user['id']
            ];
        }

        $stomach_ca = StomachCa::where($where)->orderBy('id', 'desc')->paginate(20);

        return response()->json($this->response_page($stomach_ca));
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

        if (isset($params['laparoscopic_exploration_time']) && $params['laparoscopic_exploration_time']) {
            $params['laparoscopic_exploration_time'] = strtotime($params['laparoscopic_exploration_time']);
        }
        if (isset($params['first_period_chemotherapy_time']) && $params['first_period_chemotherapy_time']) {
            $params['first_period_chemotherapy_time'] = strtotime($params['first_period_chemotherapy_time']);
        }
        if (isset($params['second_period_chemotherapy_time']) && $params['second_period_chemotherapy_time']) {
            $params['second_period_chemotherapy_time'] = strtotime($params['second_period_chemotherapy_time']);
        }
        if (isset($params['third_period_chemotherapy_time']) && $params['third_period_chemotherapy_time']) {
            $params['third_period_chemotherapy_time'] = strtotime($params['third_period_chemotherapy_time']);
        }
        if (isset($params['fourth_period_chemotherapy_time']) && $params['fourth_period_chemotherapy_time']) {
            $params['fourth_period_chemotherapy_time'] = strtotime($params['fourth_period_chemotherapy_time']);
        }
        if (isset($params['admission_time']) && $params['admission_time']) {
            $params['admission_time'] = strtotime($params['admission_time']);
        }
        if (isset($params['operative_time']) && $params['operative_time']) {
            $params['operative_time'] = strtotime($params['operative_time']);
        }
        if (isset($params['discharge_time']) && $params['discharge_time']) {
            $params['discharge_time'] = strtotime($params['discharge_time']);
        }
        if (isset($params['set_time']) && $params['set_time']) {
            $params['set_time'] = strtotime($params['set_time']);
        }
        if (isset($params['extraction_date_of_gastric_tube_time']) && $params['extraction_date_of_gastric_tube_time']) {
            $params['extraction_date_of_gastric_tube_time'] = strtotime($params['extraction_date_of_gastric_tube_time']);
        }
        if (isset($params['catheter_removal_time']) && $params['catheter_removal_time']) {
            $params['catheter_removal_time'] = strtotime($params['catheter_removal_time']);
        }
        if (isset($params['abdominal_drainage_tube_removal_date_time']) && $params['abdominal_drainage_tube_removal_date_time']) {
            $params['abdominal_drainage_tube_removal_date_time'] = strtotime($params['abdominal_drainage_tube_removal_date_time']);
        }
        if (isset($params['anal_exhaust_day_time']) && $params['anal_exhaust_day_time']) {
            $params['anal_exhaust_day_time'] = strtotime($params['anal_exhaust_day_time']);
        }
        if (isset($params['start_an_out_of_bed_day_time']) && $params['start_an_out_of_bed_day_time']) {
            $params['start_an_out_of_bed_day_time'] = strtotime($params['start_an_out_of_bed_day_time']);
        }
        if (isset($params['start_a_fluid_day_time']) && $params['start_a_fluid_day_time']) {
            $params['start_a_fluid_day_time'] = strtotime($params['start_a_fluid_day_time']);
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

        StomachCa::create($params);

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
        $stomach_ca = StomachCa::find($id);

        $img_ids = explode(',', $stomach_ca['img_id']);
        $img_arr = [];
        foreach ($img_ids as $key => $value) {
            $file = UploadFile::find($value);
            if ($file) {
                $img_arr[] = [
                    'name' => Storage::disk('public')->url($file['file_url']),
                    'url' => Storage::disk('public')->url($file['file_url'])
                ];
            }
            // $img_url = Storage::disk('public')->url($img['file_url']);
            // $img_arr[] = [
            //     'name' => $img_url,
            //     'url' => $img_url,
            // ];
        }
        $stomach_ca['img'] = $img_arr;

        $video = UploadFile::find($stomach_ca['video_id']);
        $video_arr = [];
        if ($video) {
            $video_url = Storage::disk('public')->url($video['file_url']);
            $video_arr[] = [
                'name' => $video_url,
                'url' => $video_url,
            ];
        }

        $stomach_ca['video'] = $video_arr;

        $attachment_ids = explode(',', $stomach_ca['attachment_id']);
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
        $stomach_ca['attachment'] = $attachment;   

        return response()->json($this->response_data($stomach_ca));
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

        if (isset($params['laparoscopic_exploration_time']) && $params['laparoscopic_exploration_time']) {
            $params['laparoscopic_exploration_time'] = strtotime($params['laparoscopic_exploration_time']);
        }
        if (isset($params['first_period_chemotherapy_time']) && $params['first_period_chemotherapy_time']) {
            $params['first_period_chemotherapy_time'] = strtotime($params['first_period_chemotherapy_time']);
        }
        if (isset($params['second_period_chemotherapy_time']) && $params['second_period_chemotherapy_time']) {
            $params['second_period_chemotherapy_time'] = strtotime($params['second_period_chemotherapy_time']);
        }
        if (isset($params['third_period_chemotherapy_time']) && $params['third_period_chemotherapy_time']) {
            $params['third_period_chemotherapy_time'] = strtotime($params['third_period_chemotherapy_time']);
        }
        if (isset($params['fourth_period_chemotherapy_time']) && $params['fourth_period_chemotherapy_time']) {
            $params['fourth_period_chemotherapy_time'] = strtotime($params['fourth_period_chemotherapy_time']);
        }
        if (isset($params['admission_time']) && $params['admission_time']) {
            $params['admission_time'] = strtotime($params['admission_time']);
        }
        if (isset($params['operative_time']) && $params['operative_time']) {
            $params['operative_time'] = strtotime($params['operative_time']);
        }
        if (isset($params['discharge_time']) && $params['discharge_time']) {
            $params['discharge_time'] = strtotime($params['discharge_time']);
        }
        if (isset($params['set_time']) && $params['set_time']) {
            $params['set_time'] = strtotime($params['set_time']);
        }
        if (isset($params['extraction_date_of_gastric_tube_time']) && $params['extraction_date_of_gastric_tube_time']) {
            $params['extraction_date_of_gastric_tube_time'] = strtotime($params['extraction_date_of_gastric_tube_time']);
        }
        if (isset($params['catheter_removal_time']) && $params['catheter_removal_time']) {
            $params['catheter_removal_time'] = strtotime($params['catheter_removal_time']);
        }
        if (isset($params['abdominal_drainage_tube_removal_date_time']) && $params['abdominal_drainage_tube_removal_date_time']) {
            $params['abdominal_drainage_tube_removal_date_time'] = strtotime($params['abdominal_drainage_tube_removal_date_time']);
        }
        if (isset($params['anal_exhaust_day_time']) && $params['anal_exhaust_day_time']) {
            $params['anal_exhaust_day_time'] = strtotime($params['anal_exhaust_day_time']);
        }
        if (isset($params['start_an_out_of_bed_day_time']) && $params['start_an_out_of_bed_day_time']) {
            $params['start_an_out_of_bed_day_time'] = strtotime($params['start_an_out_of_bed_day_time']);
        }
        if (isset($params['start_a_fluid_day_time']) && $params['start_a_fluid_day_time']) {
            $params['start_a_fluid_day_time'] = strtotime($params['start_a_fluid_day_time']);
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

        StomachCa::updateOrCreate(['id' => $id], $params);

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
