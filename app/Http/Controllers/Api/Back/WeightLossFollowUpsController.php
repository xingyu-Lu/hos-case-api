<?php

namespace App\Http\Controllers\Api\Back;

use App\Exceptions\BaseException;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use App\Models\WeightLoss;
use App\Models\WeightLossFollowUp;
use Illuminate\Http\Request;

class WeightLossFollowUpsController extends Controller
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

        $weight_loss = WeightLoss::where($where)->find($params['weight_loss_id']);

        if (!$weight_loss) {
            throw new BaseException(['msg' => '非法操作']);
        }

        $weight_loss_follow_up = WeightLossFollowUp::where('weight_loss_id', $params['weight_loss_id'])->orderBy('id', 'desc')->paginate(20);

        return response()->json($this->response_page($weight_loss_follow_up));
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

        $weight_loss = WeightLoss::where($where)->find($params['weight_loss_id']);

        if (!$weight_loss) {
            throw new BaseException(['msg' => '非法操作']);
        }

        if (isset($params['followed_up_after_operation_date_time']) && $params['followed_up_after_operation_date_time']) {
            $params['date'] = strtotime(date('Y-m', strtotime($params['followed_up_after_operation_date_time'])));
            $params['followed_up_after_operation_date_time'] = strtotime($params['followed_up_after_operation_date_time']);
        }

        WeightLossFollowUp::create($params);

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
        $weight_loss_follow_up = WeightLossFollowUp::find($id);

        return response()->json($this->response_data($weight_loss_follow_up));
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
        $weight_loss_follow_up = WeightLossFollowUp::find($id);

        if (!$weight_loss_follow_up) {
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

        $weight_loss = WeightLoss::where($where)->find($weight_loss_follow_up['weight_loss_id']);

        if (!$weight_loss) {
            throw new BaseException(['msg' => '非法操作']);
        }

        if (isset($params['followed_up_after_operation_date_time']) && $params['followed_up_after_operation_date_time']) {
            $params['date'] = strtotime(date('Y-m', strtotime($params['followed_up_after_operation_date_time'])));
            $params['followed_up_after_operation_date_time'] = strtotime($params['followed_up_after_operation_date_time']);
        }

        WeightLossFollowUp::updateOrCreate(['id' => $id], $params);

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
