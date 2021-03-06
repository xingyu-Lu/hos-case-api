<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightLossFollowUp extends BaseModel
{
    use HasFactory;

    protected $table = 'weight_loss_follow_ups';

    protected $dateFormat = 'U';

    //均可批量赋值
    protected $guarded = [];

    protected $casts = [
        'followed_up_after_operation_date_time' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
