<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsDota2RegionModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_dota2_region';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($region)
    {
        $model = self::where(['id' => $region->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $region->id;
        $model->name_zh = $region->name_zh;
        $model->name_en = $region->name_en;
        $model->updated_time = $region->updated_at;
        return $model->save();
    }

}
