<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsBasketballMatchAnalysisModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_basketball_match_analysis';
    protected $primaryKey = 'match_id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($analysis)
    {
        $model = self::where(['match_id' => $analysis->match_id])->first();
        if (empty($model)) $model = new self();
        $model->match_id = $analysis->match_id;
        if (!empty($analysis->info)) $model->info = json_encode($analysis->info);
        if (!empty($analysis->result)) $model->result = json_encode($analysis->result);
        if (!empty($analysis->fixture)) $model->fixture = json_encode($analysis->fixture);
        if (!empty($analysis->teams)) $model->teams = json_encode($analysis->teams);
        if (!empty($analysis->events)) $model->events = json_encode($analysis->events);
        return $model->save();
    }

}
