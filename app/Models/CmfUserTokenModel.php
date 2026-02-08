<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Helpers\RedisKeyMap;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CmfUserTokenModel extends Model
{
    protected $table = 'cmf_user_token';
    protected $primaryKey = 'id';
    public $timestamps = false;
//    protected $hidden = ['id', 'created_at', 'updated_at'];

    const USER_STATUS_UNAVAILABLE = 0;
    const USER_STATUS_AVAILABLE = 1;
    const USER_STATUS_UNVERIFIED = 2;

    public static $userStatus = [
        self::USER_STATUS_UNAVAILABLE => '禁用',
        self::USER_STATUS_AVAILABLE => '正常',
        self::USER_STATUS_UNVERIFIED => '未验证',
    ];

    const USER_TYPE_ADMIN = 1;
    const USER_TYPE_NORMAL = 2;
    const USER_TYPE_KM_VISITOR = 3;
    const USER_TYPE_KM_NORMAL = 4;

    public static $userType = [
        self::USER_TYPE_ADMIN => 'admin',
        self::USER_TYPE_NORMAL => '会员',
        self::USER_TYPE_KM_VISITOR => '酷咪游客',
        self::USER_TYPE_KM_NORMAL => '酷咪用户',
    ];

    public static function generate($uid, $userLogin)
    {
        return md5(md5($uid . $userLogin . time()));
    }

    public static function updateToken($uid, $token)
    {
        $time = time();
        $tokenInfo = NULL;
        $expireTime = $time + 60 * 60 * 24 * 300;

        if (self::where(['user_id' => $uid])->exists()) {
            $flag = self::where(['user_id' => $uid])->update([
                'token' => $token,
                'expire_time' => $expireTime,
                'create_time' => $time,
            ]);
        } else {
            $flag = self::insert([
                "user_id" => $uid,
                "token" => $token,
                "expire_time" => $expireTime,
                'create_time' => $time
            ]);
        }

        if (!empty($flag)) {
            $tokenInfo = [
                'uid' => $uid,
                'token' => $token,
                'expire_time' => $expireTime,
            ];
            $redis = Helper::predis();
            $redis->set("token_{$uid}", json_encode($tokenInfo));
            $redis->setex("chatToken_{$uid}", $expireTime - $time, $token);
        }

        return $tokenInfo;
    }
}
