<?php

namespace App\Models;

use App\Helpers\RedisKeyMap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CmfVideoModel extends BaseModel
{
    protected $table = 'cmf_video';
    protected $primaryKey = 'id';
    public $timestamps = false;
//    protected $hidden = ['id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->hasOne(CmfUserModel::class, 'id', 'uid')
            ->select('id' ,'user_nicename', 'avatar', 'avatar_thumb', 'viewnum');
    }

    public static function anyNewVideo(){
        $videoList = self::where(['uid'=>1])
            ->orderBy('id','ASC')
            ->get();
        if(count($videoList) > 0){
            return true;
        }else{
            return false;
        }
    }

    public static function updateVideo($userId){
        $limit = rand(20,30);
        $videoList = self::where(['uid'=>1])
            ->orderBy('id','ASC')
            ->take($limit)
            ->get();
        if(count($videoList) > 0){
            $last = $videoList[count($videoList)-1];
            $update['uid'] = $userId;
            self::where('uid','=','1')
                ->where('id','<=',$last->id)
                ->update($update);
        }else{
            return false;
        }

    }


}
