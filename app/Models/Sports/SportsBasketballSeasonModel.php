<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsBasketballSeasonModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_basketball_season';
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
        $model->is_current = $season->is_current;
        $model->updated_time = $season->updated_at;
        return $model->save();
    }

}
