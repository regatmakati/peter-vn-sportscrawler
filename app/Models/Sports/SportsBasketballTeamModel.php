<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsBasketballTeamModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_basketball_team';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($team)
    {
        $model = self::where(['id' => $team->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $team->id;
        $model->competition_id = $team->competition_id;
        $model->conference_id = $team->conference_id;
        $model->name_zh = $team->name_zh;
        $model->name_zht = $team->name_zht;
        $model->name_en = $team->name_en;
        $model->short_name_zh = $team->short_name_zh;
        $model->short_name_zht = $team->short_name_zht;
        $model->short_name_en = $team->short_name_en;
        $model->logo = $team->logo;
        $model->updated_time = $team->updated_at;
        return $model->save();
    }

}
