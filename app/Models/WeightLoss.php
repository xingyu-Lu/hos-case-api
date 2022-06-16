<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightLoss extends BaseModel
{
    use HasFactory;

    protected $table = 'weight_loss';

    protected $dateFormat = 'U';

    //均可批量赋值
    protected $guarded = [];

    protected $casts = [
        'admission_time' => 'datetime:Y-m-d H:i:s',
        'operative_time' => 'datetime:Y-m-d H:i:s',
        'discharge_time' => 'datetime:Y-m-d H:i:s',
        'follow_time' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
