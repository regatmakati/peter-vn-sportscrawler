<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsFootballHonorModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_football_honor';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($honor)
    {
        $model = self::where(['id' => $honor->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $honor->id;
        $model->name_zh = $honor->name_zh;
		$model->name_zht = $honor->name_zht;
		$model->name_en = $honor->name_en;
        $model->logo = $honor->logo;
        $model->updated_time = $honor->updated_at;
        return $model->save();
    }

}
