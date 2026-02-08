<?php

namespace App\Models;

use App\Helpers\RedisKeyMap;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CmfUserModel extends Model
{
    protected $table = 'cmf_user';
    protected $primaryKey = 'id';
    public $timestamps = true;

//    protected $hidden = ['id', 'created_at', 'updated_at'];

    public function getThumbAttribute($value)
    {
        if (!empty($value)) {
            return config('params.domain.image') . "/{$value}";
        }
        return $value;
    }

    public function getAvatarThumbAttribute($value)
    {
        if (!empty($value)) {
            return config('params.domain.image') . "/{$value}";
        }
        return $value;
    }

    public function getViewnumAttribute($value)
    {
        if ($value < 100) {
            $value = 2000 + $value;
        } elseif ($value >= 100 && $value < 1000) {
            $value = 100000 + $value;
        } else {
            $value = 1000000 + $value;
        }
        return $value;
    }


    public static function addRandUser(){
        $user_login = self::randMobile();
        $user_nickname = CmfNicknameModel::getRandNickname();
        $user_pass=self::setPass("a123456");
        $columns['user_login'] = $user_login;
        $columns['mobile'] = $user_login;
        $columns['user_nicename'] = $user_nickname;
        $columns['user_pass'] =$user_pass;
        $columns['sex'] = 1;
        $columns['signature'] = '这家伙很懒，什么都没留下';
        $avatar = CmfAvatarModel::getRandAvatar();
        $columns['avatar'] = $avatar;
        $columns['avatar_thumb'] = $avatar;
        $columns['create_time'] = time();
        $columns['user_status'] = 1;
        $columns['user_type'] = 2;
        $model = new self();
        return $model->insertGetId($columns);
    }

    public static function setPass($pass){
        $authcode='rCt52pF2cnnKNB3Hkp';
        $pass="###".md5(md5($authcode.$pass));
        return $pass;
    }

    public static function randUser(){
        $user = self::where('mobile','like',"12%")->inRandomOrder()->first();;
        return $user;
    }

    public static function randMobile(){
        $arr = array(
            126,127,128,129,
        );
        return $arr[array_rand($arr)].rand(1000,9999).rand(1000,9999);
    }

}
