<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsDota2StageModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_dota2_stage';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($stage)
    {
        $model = self::where(['id' => $stage->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $stage->id;
        $model->name_en = $stage->name_en;
        $model->name_zh = $stage->name_zh;
        $model->updated_time = $stage->updated_at;
        return $model->save();
    }

}
