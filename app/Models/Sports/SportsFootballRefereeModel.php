<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsFootballRefereeModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_football_referee';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($referee)
    {
        $model = self::where(['id' => $referee->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $referee->id;
        $model->name_zh = $referee->name_zh;
        $model->name_zht = $referee->name_zht;
		$model->name_en = $referee->name_en;
		$model->age = $referee->age;
        $model->birthday = $referee->birthday;
        $model->updated_time = $referee->updated_at;
        return $model->save();
    }

}
