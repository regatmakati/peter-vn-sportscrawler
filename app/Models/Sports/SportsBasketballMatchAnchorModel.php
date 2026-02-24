<?php

namespace App\Models\Sports;

use App\Helpers\Helper;
use App\Helpers\RedisKeyMap;
use App\Models\BaseModel;
use App\Models\CmfLiveModel;
use Illuminate\Support\Facades\Redis;

class SportsBasketballMatchAnchorModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_basketball_match_anchor';
    protected $primaryKey = 'id';


}
