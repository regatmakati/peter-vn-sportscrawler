<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Helpers\RedisKeyMap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CmfAvatarModel extends BaseModel
{
    protected $table = 'cmf_avatar';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public static function getRandAvatar(){
        $avatar = self::inRandomOrder()->first();
        return $avatar->image;
    }


}
