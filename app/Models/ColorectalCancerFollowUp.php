<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorectalCancerFollowUp extends Model
{
    use HasFactory;

    protected $table = 'colorectal_cancer_follow_ups';

    protected $dateFormat = 'U';

    //均可批量赋值
    protected $guarded = [];

    protected $casts = [
        'dead_time' => 'datetime:Y-m-d H:i:s',
        'followed_up_after_operation_date_time' => 'datetime:Y-m-d H:i:s',
        'gallstone_discovery_time' => 'datetime:Y-m-d H:i:s',
        'local_recurrence_time' => 'datetime:Y-m-d H:i:s',
        'distant_transfer_time' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
