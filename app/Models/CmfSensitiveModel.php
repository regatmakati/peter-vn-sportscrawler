<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Helpers\RedisKeyMap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CmfSensitiveModel extends BaseModel
{
    protected $table = 'cmf_sensitive';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public static function getSensitive(){
        return self::select(['name'])
            ->pluck('name')->toArray();
    }


}
