<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsFootballSeasonModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_football_season';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($season)
    {
        $model = self::where(['id' => $season->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $season->id;
        $model->competition_id = $season->competition_id;
        $model->year = $season->year;
        $model->has_player_stats = $season->has_player_stats;
        $model->has_team_stats = $season->has_team_stats;
        $model->has_table = $season->has_table;
        $model->is_current = $season->is_current;
        //$model->competition_rule_id = $season->competition_rule_id;
        $model->start_time = $season->start_time;
        $model->end_time = $season->end_time;
        $model->updated_time = $season->updated_at;
        return $model->save();
    }

}
