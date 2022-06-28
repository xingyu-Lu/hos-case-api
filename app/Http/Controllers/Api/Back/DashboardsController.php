<?php

namespace App\Http\Controllers\Api\Back;

use App\Http\Controllers\Controller;
use App\Models\ColorectalCancer;
use App\Models\ColorectalCancerFollowUp;
use App\Models\StomachCa;
use App\Models\StomachFollowUp;
use App\Models\StromalTumor;
use App\Models\StromalTumorFollowUp;
use App\Models\WeightLoss;
use App\Models\WeightLossFollowUp;
use Illuminate\Http\Request;

class DashboardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $res_data = $res_stomach_ca_data = $res_stromal_tumor_data = $res_colorectal_cancer_data = $res_weight_loss_data = [];

        $params = $request->all();

        // 病例
        $case_head = '病例统计';

        // 病例legend 数组
        $case_legend_data = ['胃ca', '间质瘤', '直肠癌', '减重'];

        // 胃ca
        $stomach_ca = StomachCa::get()->toArray();

        foreach ($stomach_ca as $key => $value) {
            $select_key = $value['date'];

            if (isset($res_stomach_ca_data[$select_key])) {
                $res_stomach_ca_data[$select_key]['num'] += 1;
            } else {
                $res_stomach_ca_data[$select_key] = [
                    'date' => date('Y-m', $value['date']),
                    'num' => 1,
                ];
            }
        }

        $res_stomach_ca_data = array_values($res_stomach_ca_data);

        $case_date = array_values(array_unique(array_column($res_stomach_ca_data, 'date')));

        $stomach_ca_num_arr = array_column($res_stomach_ca_data, 'num');

        // 胃ca折线图
        $stomach_ca_line_chart = [
            'series_data' => $stomach_ca_num_arr,
            'series_name' => '胃ca',
        ];

        // 间质瘤
        $stromal_tumor = StromalTumor::get()->toArray();
        foreach ($stromal_tumor as $key => $value) {
            $select_key = $value['date'];

            if (isset($res_stromal_tumor_data[$select_key])) {
                $res_stromal_tumor_data[$select_key]['num'] += 1;
            } else {
                $res_stromal_tumor_data[$select_key] = [
                    'date' => date('Y-m', $value['date']),
                    'num' => 1,
                ];
            }
        }

        $res_stromal_tumor_data = array_values($res_stromal_tumor_data);

        $stromal_tumor_num_arr = array_column($res_stromal_tumor_data, 'num');

        // 间质瘤折线图
        $stromal_tumor_line_chart = [
            'series_data' => $stromal_tumor_num_arr,
            'series_name' => '间质瘤',
        ];

        // 直肠癌
        $colorectal_cancer = ColorectalCancer::get()->toArray();
        foreach ($colorectal_cancer as $key => $value) {
            $select_key = $value['date'];

            if (isset($res_colorectal_cancer_data[$select_key])) {
                $res_colorectal_cancer_data[$select_key]['num'] += 1;
            } else {
                $res_colorectal_cancer_data[$select_key] = [
                    'date' => date('Y-m', $value['date']),
                    'num' => 1,
                ];
            }
        }

        $res_colorectal_cancer_data = array_values($res_colorectal_cancer_data);

        $colorectal_cancer_num_arr = array_column($res_colorectal_cancer_data, 'num');

        // 直肠癌折线图
        $colorectal_cancer_line_chart = [
            'series_data' => $colorectal_cancer_num_arr,
            'series_name' => '直肠癌',
        ];

        // 减重
        $weight_loss = WeightLoss::get()->toArray();
        foreach ($weight_loss as $key => $value) {
            $select_key = $value['date'];

            if (isset($res_weight_loss_data[$select_key])) {
                $res_weight_loss_data[$select_key]['num'] += 1;
            } else {
                $res_weight_loss_data[$select_key] = [
                    'date' => date('Y-m', $value['date']),
                    'num' => 1,
                ];
            }
        }

        $res_weight_loss_data = array_values($res_weight_loss_data);

        $weight_loss_num_arr = array_column($res_weight_loss_data, 'num');

        // 直肠癌折线图
        $weight_loss_line_chart = [
            'series_data' => $weight_loss_num_arr,
            'series_name' => '减重',
        ];

        // 随访
        $res_stomach_ca_follow_data = $res_stromal_tumor_follow_data = $res_colorectal_cancer_follow_data = $res_weight_loss_follow_data = [];

        $follow_head = '随访统计';

        // 病例legend 数组
        $follow_legend_data = ['胃ca', '间质瘤', '直肠癌', '减重'];

        // 胃ca随访
        $stomach_ca_follow = StomachFollowUp::get()->toArray();

        foreach ($stomach_ca_follow as $key => $value) {
            $select_key = $value['date'];

            if (isset($res_stomach_ca_follow_data[$select_key])) {
                $res_stomach_ca_follow_data[$select_key]['num'] += 1;
            } else {
                $res_stomach_ca_follow_data[$select_key] = [
                    'date' => date('Y-m', $value['date']),
                    'num' => 1,
                ];
            }
        }

        $res_stomach_ca_follow_data = array_values($res_stomach_ca_follow_data);

        $follow_date = array_values(array_unique(array_column($res_stomach_ca_follow_data, 'date')));

        $stomach_ca_follow_num_arr = array_column($res_stomach_ca_follow_data, 'num');

        // 胃ca折线图
        $stomach_ca_follow_line_chart = [
            'series_data' => $stomach_ca_follow_num_arr,
            'series_name' => '胃ca',
        ];

        // 间质瘤
        $stromal_tumor_follow = StromalTumorFollowUp::get()->toArray();
        foreach ($stromal_tumor_follow as $key => $value) {
            $select_key = $value['date'];

            if (isset($res_stromal_tumor_follow_data[$select_key])) {
                $res_stromal_tumor_follow_data[$select_key]['num'] += 1;
            } else {
                $res_stromal_tumor_follow_data[$select_key] = [
                    'date' => date('Y-m', $value['date']),
                    'num' => 1,
                ];
            }
        }

        $res_stromal_tumor_follow_data = array_values($res_stromal_tumor_follow_data);

        $stromal_tumor_follow_num_arr = array_column($res_stromal_tumor_follow_data, 'num');

        // 纸擦换个奶折线图
        $stromal_tumor_follow_line_chart = [
            'series_data' => $stromal_tumor_follow_num_arr,
            'series_name' => '间质瘤',
        ];

        // 直肠癌
        $colorectal_cancer_follow = ColorectalCancerFollowUp::get()->toArray();
        foreach ($colorectal_cancer_follow as $key => $value) {
            $select_key = $value['date'];

            if (isset($res_colorectal_cancer_follow_data[$select_key])) {
                $res_colorectal_cancer_follow_data[$select_key]['num'] += 1;
            } else {
                $res_colorectal_cancer_follow_data[$select_key] = [
                    'date' => date('Y-m', $value['date']),
                    'num' => 1,
                ];
            }
        }

        $res_colorectal_cancer_follow_data = array_values($res_colorectal_cancer_follow_data);

        $colorectal_cancer_follow_num_arr = array_column($res_colorectal_cancer_follow_data, 'num');

        // 直肠癌折线图
        $colorectal_cancer_follow_line_chart = [
            'series_data' => $colorectal_cancer_follow_num_arr,
            'series_name' => '直肠癌',
        ];

        // 减重
        $weight_loss_follow = WeightLossFollowUp::get()->toArray();
        foreach ($weight_loss_follow as $key => $value) {
            $select_key = $value['date'];

            if (isset($res_weight_loss_follow_data[$select_key])) {
                $res_weight_loss_follow_data[$select_key]['num'] += 1;
            } else {
                $res_weight_loss_follow_data[$select_key] = [
                    'date' => date('Y-m', $value['date']),
                    'num' => 1,
                ];
            }
        }

        $res_weight_loss_follow_data = array_values($res_weight_loss_follow_data);

        $weight_loss_follow_num_arr = array_column($res_weight_loss_follow_data, 'num');

        // 直肠癌折线图
        $weight_loss_follow_line_chart = [
            'series_data' => $weight_loss_follow_num_arr,
            'series_name' => '减重',
        ];

        $res_data = [
            'case_line_chart' => [
                'case_head' => $case_head,
                'case_date' => $case_date,
                'case_legend_data' => $case_legend_data,
                'stomach_ca' => $stomach_ca_line_chart,
                'stromal_tumor_line_chart' => $stromal_tumor_line_chart,
                'colorectal_cancer_line_chart' => $colorectal_cancer_line_chart,
                'weight_loss_line_chart' => $weight_loss_line_chart,
            ],
            'follow_line_chart' => [
                'follow_head' => $follow_head,
                'follow_date' => $follow_date,
                'follow_legend_data' => $follow_legend_data,
                'stomach_ca_follow_line_chart' => $stomach_ca_follow_line_chart,
                'stromal_tumor_follow_line_chart' => $stromal_tumor_follow_line_chart,
                'colorectal_cancer_follow_line_chart' => $colorectal_cancer_follow_line_chart,
                'weight_loss_follow_line_chart' => $weight_loss_follow_line_chart,
            ],
        ];

        return response()->json($this->response_data($res_data));
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
