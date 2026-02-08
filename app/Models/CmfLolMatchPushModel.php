<?php

namespace App\Models;

use App\Helpers\RedisKeyMap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CmfLolMatchPushModel extends BaseModel
{
    protected $table = 'cmf_lol_match_push';
    protected $primaryKey = 'id';
    public $timestamps = false;
//    protected $hidden = ['id', 'created_at', 'updated_at'];

    CONST STATUS_PUSH_NOT = 0;
    CONST STATUS_PUSHED= 1;

    public static $statusMap = [
        self::STATUS_PUSH_NOT => '未推送',
        self::STATUS_PUSHED => '已推送',
    ];

    CONST TYPE_GAMING = 1;
    CONST TYPE_BASKETBALL = 2;
    CONST TYPE_FOOTBALL = 3;

    public static $typeMap = [
        self::TYPE_GAMING => '电竞',
        self::TYPE_BASKETBALL => '篮球',
        self::TYPE_FOOTBALL => '足球',
    ];

    public static function insertOrUpdate($input)
    {
        $model = self::where(['match_id' => $input['match_id'], 'type' => $input['type']])->first();
        if (empty($model)) $model = new self();
        $model->match_id = $input['match_id'];
        $model->status = $input['status'];
        $model->addtime = $input['addtime'];
        $model->type = $input['type'];
        return $model->save();
    }

}
