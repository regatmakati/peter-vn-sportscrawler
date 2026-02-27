<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Helpers\RedisKeyMap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CmfAnchorAuth extends BaseModel
{
    protected $connection = 'mysql'; // 第二个数据库
    protected $table = 'cmf_anchor_auth';
    protected $primaryKey = 'id';
    public $timestamps = false;
//    protected $hidden = ['id', 'created_at', 'updated_at'];

    const IS_VIDEO_REAL = 0;
    const IS_VIDEO_FAKE= 1;
    public static $videoMap = [
        self::IS_VIDEO_REAL => '已方直播',
        self::IS_VIDEO_FAKE => '第三方直播',
    ];

    CONST MANUAL_CLOSE_NO = 0;
    CONST MANUAL_CLOSE_YES = 1;
    public static $autoCloseMap = [
        self::MANUAL_CLOSE_NO => '自动',
        self::MANUAL_CLOSE_YES => '手动',
    ];

    public function user()
    {
        return $this->hasOne(CmfUserModel::class, 'id', 'uid')
            ->select('id' ,'user_nicename', 'avatar', 'avatar_thumb', 'viewnum');
    }


    public function live()
    {
        return $this->hasOne(CmfLiveModel::class, 'uid', 'uid')
            ->select('uid' ,'stream','match_id');
    }



    public static function getAllAnchor()
    {
        $list = json_decode(Redis::get('getAllAnchor'));
        if (!empty($list)) return $list;
        $arr = self::with(['user','live'])->where('status',1)->select('uid')->get()->toArray();
        if($arr){
            $list = [];
            foreach ($arr as $v)
            {
                if(!$v['live']){
                    $v['live']['uid'] = '';
                    $v['live']['stream'] = '';
                    $v['live']['match_id'] = '';
                }


                $list[$v['uid']] =  array_merge(
                    $v['user'] ?? [],
                    $v['live']
                );
            }
            Redis::setex('getAllAnchor', 30, json_encode($list));
        }

        return  $list;
    }

}
