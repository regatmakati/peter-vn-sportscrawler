<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsFootballMatchAnalysisModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_football_match_analysis';
    protected $primaryKey = 'match_id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($analysis)
    {
        $model = self::where(['match_id' => $analysis->match_id])->first();
        if (empty($model)) $model = new self();
        $model->match_id = $analysis->match_id;
        if (!empty($analysis->info)) $model->info = json_encode($analysis->info);
        if (!empty($analysis->history)) $model->history = json_encode($analysis->history);
        if (!empty($analysis->goal_distribution)) $model->goal_distribution = json_encode($analysis->goal_distribution);
        if (!empty($analysis->injury)) $model->injury = json_encode($analysis->injury);
//            if (!empty($analysis->table)) $model->table = json_encode($analysis->table);
        if (!empty($analysis->teams)) $model->teams = json_encode($analysis->teams);
        if (!empty($analysis->matchevents)) $model->matchevents = json_encode($analysis->matchevents);

        return $model->save();
    }

}
