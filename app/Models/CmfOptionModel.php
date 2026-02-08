<?php

namespace App\Models;

use App\Helpers\RedisKeyMap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CmfOptionModel extends BaseModel
{
    protected $table = 'cmf_option';
    protected $primaryKey = 'id';
    public $timestamps = false;
//    protected $hidden = ['id', 'created_at', 'updated_at'];
    CONST OPTION_SITE_INFO = 'site_info';
    CONST OPTION_CMF_SETTINGS = 'cmf_settings';
    CONST OPTION_CDN_SETTINGS = 'cdn_settings';
    CONST OPTION_ADMIN_SETTINGS = 'admin_settings';
    CONST OPTION_STORAGE = 'storage';
    CONST OPTION_CONFIG_PRI = 'configpri';
    CONST OPTION_JACKPOT = 'jackpot';
    CONST OPTION_GUIDE = 'guide';
    CONST OPTION_UPLOAD_SETTING = 'upload_setting';
    CONST OPTION_HUAWEI = 'huawei';

    /**
     * @param $optionName
     * @return mixed
     */
    public static function getConfig($optionName)
    {

        $config = json_decode(Redis::get(RedisKeyMap::getConfig($optionName)));
        if (!empty($config)) return $config;
        $config = json_decode(self::select(['option_value'])->where(['option_name' => $optionName])->value('option_value'));
        Redis::setex(RedisKeyMap::getConfig($optionName), config('params.cache.ttl'), $config);
        return $config;
    }
}
