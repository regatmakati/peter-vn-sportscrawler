<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsFootballCompetitionModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_football_competition';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($competition)
    {
        $model = self::where(['id' => $competition->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $competition->id;
        $model->category_id = $competition->category_id;
        $model->name_en = $competition->name_en;
        $model->name_zh = $competition->name_zh;
        $model->name_zht = $competition->name_zht;
        $model->short_name_en = $competition->short_name_en;
        $model->short_name_en = $competition->short_name_en;
        $model->short_name_en = $competition->short_name_en;
        $model->country_id = $competition->country_id;
        //$model->cur_round = $competition->cur_round;
        //$model->cur_season_id = $competition->cur_season_id;
        //$model->cur_stage_id = $competition->cur_stage_id;
        $model->type = $competition->type;
        $model->logo = $competition->logo;
        $model->updated_time = $competition->updated_at;
        return $model->save();
    }

}
