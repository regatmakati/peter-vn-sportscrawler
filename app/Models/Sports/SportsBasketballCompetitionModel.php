<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsBasketballCompetitionModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_basketball_competition';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($competition)
    {
        $model = self::where(['id' => $competition->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $competition->id;
        $model->category_id = $competition->category_id;
        $model->country_id = $competition->country_id;
        $model->name_en = $competition->name_en;
        $model->name_zh = $competition->name_zh;
        $model->name_zht = $competition->name_zht;
        $model->short_name_en = $competition->short_name_en;
        $model->short_name_en = $competition->short_name_en;
        $model->short_name_en = $competition->short_name_en;
        $model->logo = $competition->logo;
        $model->updated_time = $competition->updated_at;
        return $model->save();
    }

}
