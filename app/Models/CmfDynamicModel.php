<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Helpers\RedisKeyMap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CmfDynamicModel extends BaseModel
{
    protected $table = 'cmf_dynamic';
    protected $primaryKey = 'id';
    public $timestamps = false;

    const TEXT_TYPE= 0;
    const IMAGE_TYPE= 1;
    const VIDEO_TYPE = 2;
    public static $typeMap = [
        self::TEXT_TYPE => '纯文字',
        self::IMAGE_TYPE => '图片+文字',
        self::VIDEO_TYPE => '视频+文字',
    ];

    CONST STATUS_WAIT= 0;
    CONST STATUS_PASS = 1;
    CONST STATUS_REJECT= 2;

    public static $statusMap = [
        self::STATUS_WAIT => '未审核',
        self::STATUS_PASS => '通过',
        self::STATUS_REJECT => '拒绝',
    ];

    public static function deleteDynamic(){
        $isDel = self::where('addtime','<',time()-7*24*3600)->delete();
        if ($isDel) echo Helper::currentTime() . "七天前动态已删除！\r\n";
    }

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
