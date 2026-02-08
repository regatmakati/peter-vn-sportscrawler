<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Helpers\RedisKeyMap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CmfNicknameModel extends BaseModel
{
    protected $table = 'cmf_nickname';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public static function getRandNickname(){
        $nickname = self::inRandomOrder()->first();
        return $nickname->name;
    }


}
