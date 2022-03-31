<?php

namespace App\Http\Controllers\Api\Back;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\CaseReport;
use App\Models\CaseType;
use App\Models\Role;
use App\Models\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CasesController extends Controller
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

        $cases = CaseReport::where($where)->orderBy('id', 'desc')->paginate(20);

        foreach ($cases as $key => $value) {
            $value['abstract'] = mb_substr($value['abstract'], 0, 30) . '...';
            $value['diagnosis'] = mb_substr($value['diagnosis'], 0, 30) . '...';
            $value['diagnosis_result'] = mb_substr($value['diagnosis_result'], 0, 30) . '...';
            $value['general_seen'] = mb_substr($value['general_seen'], 0, 30) . '...';

            $case_type = CaseType::find($value['type_id']);
            $value['type_name'] = $case_type['name'];

            $img = UploadFile::find($value['img_id']);
            $url = '';
            if ($img) {
                $url = Storage::disk('public')->url($img['file_url']);
            }
            $value['img_url'] = $url;
        }

        return response()->json($this->response_page($cases));
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

        $insert_data = [
            'admin_id' => $user['id'],
            'name' => $params['name'],
            'age' => $params['age'],
            'sex' => $params['sex'],
            'abstract' => $params['abstract'],
            'type_id' => $params['type_id'],
            'part' => $params['part'],
            'diagnosis' => $params['diagnosis'] ?? '',
            'diagnosis_result' => $params['diagnosis_result'] ?? '',
            'general_seen' => $params['general_seen'] ?? '',
            'img_id' => $params['img'] ?? 0,
            'video_id' => $params['video'] ?? 0,
            'attachment_id' => $params['attachment'] ?? ''
        ];

        CaseReport::create($insert_data);

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
        $case = CaseReport::find($id);

        $case_type = CaseType::find($case['type_id']);

        $case['type_name'] = $case_type['name'];

        $img = UploadFile::find($case['img_id']);
        $img_url = '';
        if ($img) {
            $img_url = Storage::disk('public')->url($img['file_url']);
        }
        $case['img_url'] = $img_url;

        $video = UploadFile::find($case['video_id']);
        $video_arr = [];
        if ($video) {
            $video_url = Storage::disk('public')->url($video['file_url']);
            $video_arr[] = [
                'name' => $video_url,
                'url' => $video_url,
            ];
        }

        $case['video'] = $video_arr;

        $attachment_ids = explode(',', $case['attachment_id']);
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
        $case['attachment'] = $attachment;

        return response()->json($this->response_data($case));
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

        $update_data = [
            'name' => $params['name'],
            'age' => $params['age'],
            'sex' => $params['sex'],
            'abstract' => $params['abstract'],
            'type_id' => $params['type_id'],
            'part' => $params['part'],
            'diagnosis' => $params['diagnosis'] ?? '',
            'diagnosis_result' => $params['diagnosis_result'] ?? '',
            'general_seen' => $params['general_seen'] ?? '',
            'img_id' => $params['img'] ?? 0,
            'video_id' => $params['video'] ?? 0,
            'attachment_id' => $params['attachment'] ?? '',
        ];

        CaseReport::updateOrCreate(['id' => $id], $update_data);

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

    public function status(Request $request)
    {
        $params = $request->all();

        $id = $params['id'];
        $status = $params['status'];

        CaseReport::updateOrCreate(['id' => $id], ['status' => $status]);

        return response()->json($this->response_data());
    }
}
