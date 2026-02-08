<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsDota2MatchSingleModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_dota2_match_single';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];
    protected $appends = ['status_str'];

    CONST STATUS_ABNORMAL = 0;
    CONST STATUS_NOT_START = 1;
    CONST STATUS_PLAYING = 2;
    CONST STATUS_FINISH = 3;
    CONST STATUS_INTERRUPT = 11;
    CONST STATUS_CANCEL = 12;
    CONST STATUS_DELAY = 13;
    CONST STATUS_HALF_CUT = 14;
    CONST STATUS_DETERMINE = 15;

    public static $statusMap = [
        self::STATUS_ABNORMAL => '比赛异常',
        self::STATUS_NOT_START => '未开赛',
        self::STATUS_PLAYING => '进行中',
        self::STATUS_FINISH => '完场',
        self::STATUS_INTERRUPT => '中断',
        self::STATUS_CANCEL => '取消',
        self::STATUS_DELAY => '延期',
        self::STATUS_HALF_CUT => '腰斩',
        self::STATUS_DETERMINE => '待定',
    ];

    public static function getRadiantAttribute($value)
    {
        return json_decode($value);
    }

    public static function getDireAttribute($value)
    {
        return json_decode($value);
    }

    public function getStatusStrAttribute()
    {
        return self::$statusMap[$this->status_id] ?? '';
    }

    public static function insertOrUpdate($match)
    {
        $model = self::where(['match_id' => $match->match->id, 'box_num' => $match->box_num])->first();
        if (empty($model)) $model = new self();
        $model->id = $match->id;
        $model->box_num = $match->box_num;
        $model->match_id = $match->match->id;
        $model->status_id = $match->status_id;
        $model->match_time = $match->match_time;
        $model->radiant = json_encode($match->radiant);
        $model->dire = json_encode($match->dire);
        $model->length_time = $match->length_time;
        $model->first_blood = $match->first_blood;
        $model->first_tower = $match->first_tower;
        $model->first_roushan = $match->first_roushan;
        $model->five_kill = $match->five_kill;
        $model->ten_kill = $match->ten_kill;
        $model->eco_list = $match->eco_list;
        $model->exp_list = $match->exp_list;
        $model->updated_time = $match->updated_at;
        return $model->save();
    }

}
