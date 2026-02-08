<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;

use App\Helpers\RedisKeyMap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CmfLiveRecordModel extends Model
{
    protected $table = 'cmf_live_record';
    protected $primaryKey = 'id';
    public $timestamps = false;


    public static function deleteRoomUpdateLiveRecord($uid)
    {
        $redis = Helper::predis();
        $live = CmfLiveModel::selectRaw('uid,showid,starttime,title,province,city,stream,lng,lat,type,type_val,liveclassid')
            ->where(['uid' => $uid])
            ->first();
        if (!empty($live)) {
            $live = $live->toArray();
            $nums = $redis->zCard('user_' . $live['stream']);
            $pcNums = $redis->zCard('userpc_' . $live['stream']);
            $androidNums = $redis->zCard('userandroid_' . $live['stream']);
            $iosNums = $redis->zCard('userios_' . $live['stream']);
            $h5Nums = $redis->zCard('userh5_' . $live['stream']);
            $live['endtime'] = time();
            $live['time'] = date("Y-m-d", $live['showid']);
            $live['votes'] = 0;
            $live['nums'] = $nums;
            $live['pcnums'] = $pcNums;
            $live['androidnums'] = $androidNums;
            $live['iosnums'] = $iosNums;
            $live['h5nums'] = $h5Nums;

            if (self::insert($live)) {
                $redis->hDel("livelist", $uid);
                $redis->del($uid . '_zombie');
                $redis->del($uid . '_zombie_uid');
                $redis->del('attention_' . $uid);
                $redis->del('user_' . $live['stream']);
                $redis->del('userpc_' . $live['stream']);
                $redis->del('userandroid_' . $live['stream']);
                $redis->del('userios_' . $live['stream']);
                $redis->del('userh5_' . $live['stream']);
                $redis->del('roomNum_pc_' . $uid);
                $redis->del('roomNum_h5_' . $uid);
                $redis->del('roomNum_android_' . $uid);
                $redis->del('roomNum_ios_' . $uid);
                return true;
            }
        }
        return;
    }

}
