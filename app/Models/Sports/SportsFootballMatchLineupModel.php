<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsFootballMatchLineupModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_football_match_lineup';
    protected $primaryKey = 'match_id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($lineup)
    {
        $model = self::where(['match_id' => $lineup->match_id])->first();
        if (empty($model)) $model = new self();
        $model->match_id = $lineup->match_id;
        $model->home_team_id = $lineup->home_team_id;
        $model->home = json_encode($lineup->home);
        $model->home_formation = $lineup->home_formation;
        $model->away_team_id = $lineup->away_team_id;
        $model->away = json_encode($lineup->away);
        $model->away_formation = $lineup->away_formation;
        $model->confirmed = $lineup->confirmed;
        return $model->save();
    }

}
