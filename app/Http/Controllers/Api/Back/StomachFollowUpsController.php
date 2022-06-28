<?php

namespace App\Http\Controllers\Api\Back;

use App\Exceptions\BaseException;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use App\Models\StomachCa;
use App\Models\StomachFollowUp;
use Illuminate\Http\Request;

class StomachFollowUpsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $current_user = auth('api')->user();

        $root = $current_user->hasRole(Role::ROOT, app(Admin::class)->guardName());
        
        $where = [];

        if (!$root) {
            $where[] = [
                'admin_id', '=', $current_user['id']
            ];
        }

        $params = $request->all();

        $stomach_ca = StomachCa::where($where)->find($params['stomach_ca_id']);

        if (!$stomach_ca) {
            throw new BaseException(['msg' => '非法操作']);
        }

        $stomach_follow_up = StomachFollowUp::where('stomach_ca_id', $params['stomach_ca_id'])->orderBy('id', 'desc')->paginate(20);

        return response()->json($this->response_page($stomach_follow_up));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $current_user = auth('api')->user();

        $root = $current_user->hasRole(Role::ROOT, app(Admin::class)->guardName());
        
        $where = [];

        if (!$root) {
            $where[] = [
                'admin_id', '=', $current_user['id']
            ];
        }

        $params = $request->all();

        $stomach_ca = StomachCa::where($where)->find($params['stomach_ca_id']);

        if (!$stomach_ca) {
            throw new BaseException(['msg' => '非法操作']);
        }

        if (isset($params['dead_time']) && $params['dead_time']) {
            $params['dead_time'] = strtotime($params['dead_time']);
        }
        if (isset($params['followed_up_after_operation_date_time']) && $params['followed_up_after_operation_date_time']) {
            $params['date'] = strtotime(date('Y-m', strtotime($params['followed_up_after_operation_date_time'])));
            $params['followed_up_after_operation_date_time'] = strtotime($params['followed_up_after_operation_date_time']);
        }
        if (isset($params['gallstone_discovery_time']) && $params['gallstone_discovery_time']) {
            $params['gallstone_discovery_time'] = strtotime($params['gallstone_discovery_time']);
        }
        if (isset($params['local_recurrence_time']) && $params['local_recurrence_time']) {
            $params['local_recurrence_time'] = strtotime($params['local_recurrence_time']);
        }
        if (isset($params['distant_transfer_time']) && $params['distant_transfer_time']) {
            $params['distant_transfer_time'] = strtotime($params['distant_transfer_time']);
        }

        StomachFollowUp::create($params);

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
        $stomach_follow_up = StomachFollowUp::find($id);

        return response()->json($this->response_data($stomach_follow_up));
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
        $stomach_follow_up = StomachFollowUp::find($id);

        if (!$stomach_follow_up) {
            throw new BaseException(['msg' => '记录不存在']);
        }

        $current_user = auth('api')->user();

        $root = $current_user->hasRole(Role::ROOT, app(Admin::class)->guardName());
        
        $where = [];

        if (!$root) {
            $where[] = [
                'admin_id', '=', $current_user['id']
            ];
        }

        $params = $request->all();

        $stomach_ca = StomachCa::where($where)->find($stomach_follow_up['stomach_ca_id']);

        if (!$stomach_ca) {
            throw new BaseException(['msg' => '非法操作']);
        }

        if (isset($params['dead_time']) && $params['dead_time']) {
            $params['dead_time'] = strtotime($params['dead_time']);
        }
        if (isset($params['followed_up_after_operation_date_time']) && $params['followed_up_after_operation_date_time']) {
            $params['date'] = strtotime(date('Y-m', strtotime($params['followed_up_after_operation_date_time'])));
            $params['followed_up_after_operation_date_time'] = strtotime($params['followed_up_after_operation_date_time']);
        }
        if (isset($params['gallstone_discovery_time']) && $params['gallstone_discovery_time']) {
            $params['gallstone_discovery_time'] = strtotime($params['gallstone_discovery_time']);
        }
        if (isset($params['local_recurrence_time']) && $params['local_recurrence_time']) {
            $params['local_recurrence_time'] = strtotime($params['local_recurrence_time']);
        }
        if (isset($params['distant_transfer_time']) && $params['distant_transfer_time']) {
            $params['distant_transfer_time'] = strtotime($params['distant_transfer_time']);
        }

        StomachFollowUp::updateOrCreate(['id' => $id], $params);

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
