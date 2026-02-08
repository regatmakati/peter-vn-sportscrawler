<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsFootballInjuryModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_football_injury';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($injury)
    {
        $model = self::where(['id' => $injury->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $injury->id;
        $model->player_id = $injury->player_id;
        $model->team_id = $injury->team_id;
        $model->competition_id = $injury->competition_id;
        $model->type = $injury->type;
        $model->reason_zh = $injury->reason_zh;
        $model->start_time = $injury->start_time;
        $model->end_time = $injury->end_time;
        $model->missed_matches = $injury->missed_matches;
        $model->updated_time = $injury->updated_at;
        return $model->save();
    }

}
