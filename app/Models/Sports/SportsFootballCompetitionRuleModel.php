<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsFootballCompetitionRuleModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_football_competition_rule';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($competitionRule)
    {
        $model = self::where(['id' => $competitionRule->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $competitionRule->id;
        $model->text = $competitionRule->text;
        $model->updated_time = $competitionRule->updated_at;
        return $model->save();
    }

}
