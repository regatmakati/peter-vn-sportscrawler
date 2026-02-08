<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Helpers\RedisKeyMap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CmfDynamicCommentsModel extends BaseModel
{
    protected $table = 'cmf_dynamic_comments';
    protected $primaryKey = 'id';
    public $timestamps = false;


	public static function insertAll($insertAll){
        return self::insert($insertAll);
    }

    public static function filter($banWordList, $string)
    {
        if (empty($banWordList)) $banWordList = [];
        $banWord = array_combine($banWordList, array_fill(0, count($banWordList), '*'));
        return strtr($string, $banWord);
    }
}
