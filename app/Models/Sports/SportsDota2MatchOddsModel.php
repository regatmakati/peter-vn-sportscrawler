<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsDota2MatchOddsModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_dota2_match_odds';
    protected $primaryKey = 'match_id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($odds_company_id, $odds_type_id, $range, $match_id, $odds)
    {
        $model = self::where([
            'match_id' => $match_id,
            'odds_type_id' => $odds_type_id,
            'range' => $range,
            'odds_company_id' => $odds_company_id
        ])->first();
        if (empty($model)) $model = new self();
        $model->match_id = $match_id;
        $model->odds_type_id = $odds_type_id;
        $model->range = $range;
        $model->odds_company_id = $odds_company_id;
        $model->odds = json_encode($odds);
        return $model->save();
    }

}
