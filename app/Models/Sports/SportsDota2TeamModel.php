<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsDota2TeamModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_dota2_team';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($team)
    {
        $model = self::where(['id' => $team->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $team->id;
        $model->name_zh = $team->name_zh;
        $model->abbr_zh = $team->abbr_zh;
        $model->name_en = $team->name_en;
        $model->abbr_en = $team->abbr_en;
        $model->logo = $team->logo;
        $model->region_id = $team->region->id;
        $model->country_id = $team->country->id;
        $model->create_time = $team->create_time;
        $model->total_earnings = $team->total_earnings;
        $model->updated_time = $team->updated_at;
        return $model->save();
    }

}
