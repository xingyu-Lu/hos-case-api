<?php

namespace App\Http\Controllers\Api\Back;

use App\Exceptions\BaseException;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ColorectalCancer;
use App\Models\ColorectalCancerFollowUp;
use App\Models\Role;
use Illuminate\Http\Request;

class ColorectalCancerFollowUpsController extends Controller
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

        $colorectal_cancer = ColorectalCancer::where($where)->find($params['colorectal_cancer_id']);

        if (!$colorectal_cancer) {
            throw new BaseException(['msg' => '非法操作']);
        }

        $colorectal_cancer_follow_up = ColorectalCancerFollowUp::where('colorectal_cancer_id', $params['colorectal_cancer_id'])->orderBy('id', 'desc')->paginate(20);

        return response()->json($this->response_page($colorectal_cancer_follow_up));
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

        $colorectal_cancer = ColorectalCancer::where($where)->find($params['colorectal_cancer_id']);

        if (!$colorectal_cancer) {
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

        ColorectalCancerFollowUp::create($params);

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
        $colorectal_cancer_follow_up = ColorectalCancerFollowUp::find($id);

        return response()->json($this->response_data($colorectal_cancer_follow_up));
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
        $colorectal_cancer_follow_up = ColorectalCancerFollowUp::find($id);

        if (!$colorectal_cancer_follow_up) {
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

        $colorectal_cancer = ColorectalCancer::where($where)->find($colorectal_cancer_follow_up['colorectal_cancer_id']);

        if (!$colorectal_cancer) {
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

        ColorectalCancerFollowUp::updateOrCreate(['id' => $id], $params);

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
