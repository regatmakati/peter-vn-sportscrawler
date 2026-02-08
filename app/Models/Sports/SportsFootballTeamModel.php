<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsFootballTeamModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_football_team';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($team)
    {
        $model = self::where(['id' => $team->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $team->id;
        $model->competition_id = $team->competition_id;
        $model->country_id = $team->country_id;
        $model->name_zh = $team->name_zh;
        $model->name_zht = $team->name_zht;
        $model->name_en = $team->name_en;
        $model->short_name_zh = $team->short_name_zh;
        $model->short_name_zht = $team->short_name_zht;
        $model->short_name_en = $team->short_name_en;
        $model->logo = $team->logo;
        $model->national = $team->national;
        $model->foundation_time = $team->foundation_time;
        $model->website = $team->website;
        //$model->manager_id = $team->manager_id;
        $model->venue_id = $team->venue_id;
        $model->market_value = $team->market_value;
        $model->market_value_currency = $team->market_value_currency;
        $model->country_logo = $team->country_logo ?? '';
        $model->total_players = $team->total_players;
        $model->foreign_players = $team->foreign_players;
        $model->national_players = $team->national_players;
        $model->updated_time = $team->updated_at;
        return $model->save();
    }

}
