<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsFootballManagerModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_football_manager';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($manager)
    {
        $model = self::where(['id' => $manager->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $manager->id;
        $model->team_id = $manager->team_id;
        $model->name_zh = $manager->name_zh;
        $model->name_en = $manager->name_en;
        $model->logo = $manager->logo;
        $model->birthday = $manager->birthday;
        $model->age = $manager->age;
        $model->nationality = $manager->nationality;
        $model->preferred_formation = $manager->preferred_formation;
        $model->updated_time = $manager->updated_at;
        return $model->save();
    }

}
