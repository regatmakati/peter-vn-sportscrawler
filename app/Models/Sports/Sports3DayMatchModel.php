<?php

namespace App\Models\Sports;

use App\Helpers\Helper;
use App\Helpers\RedisKeyMap;
use App\Models\BaseModel;
use App\Models\CmfAnchorAuth;
use App\Models\CmfLiveModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class Sports3DayMatchModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_3day_match';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

}
