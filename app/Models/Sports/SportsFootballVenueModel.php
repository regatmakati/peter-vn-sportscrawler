<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsFootballVenueModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_football_venue';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($venue)
    {
        $model = self::where(['id' => $venue->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $venue->id;
        $model->name_zh = $venue->name_zh;
        $model->name_en = $venue->name_en;
        $model->capacity = $venue->capacity;
        $model->country_id = $venue->country_id;
        //$model->city = $venue->city;
        $model->updated_time = $venue->updated_at;
        return $model->save();
    }

}
