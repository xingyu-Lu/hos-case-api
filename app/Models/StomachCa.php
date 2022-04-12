<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StomachCa extends BaseModel
{
    use HasFactory;

    protected $table = 'stomach_cas';

    protected $dateFormat = 'U';

    //均可批量赋值
    protected $guarded = [];

    protected $casts = [
        'laparoscopic_exploration_time' => 'datetime:Y-m-d H:i:s',
        'first_period_chemotherapy_time' => 'datetime:Y-m-d H:i:s',
        'second_period_chemotherapy_time' => 'datetime:Y-m-d H:i:s',
        'third_period_chemotherapy_time' => 'datetime:Y-m-d H:i:s',
        'fourth_period_chemotherapy_time' => 'datetime:Y-m-d H:i:s',
        'admission_time' => 'datetime:Y-m-d H:i:s',
        'operative_time' => 'datetime:Y-m-d H:i:s',
        'discharge_time' => 'datetime:Y-m-d H:i:s',
        'set_time' => 'datetime:Y-m-d H:i:s',
        'extraction_date_of_gastric_tube_time' => 'datetime:Y-m-d H:i:s',
        'catheter_removal_time' => 'datetime:Y-m-d H:i:s',
        'abdominal_drainage_tube_removal_date_time' => 'datetime:Y-m-d H:i:s',
        'anal_exhaust_day_time' => 'datetime:Y-m-d H:i:s',
        'start_an_out_of_bed_day_time' => 'datetime:Y-m-d H:i:s',
        'start_a_fluid_day_time' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
