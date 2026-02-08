<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsDota2EquipmentModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_dota2_equipment';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($equipment)
    {
        $model = self::where(['id' => $equipment->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $equipment->id;
        $model->name_zh = $equipment->name_zh;
        $model->name_en = $equipment->name_en;
        $model->logo = $equipment->logo;
        $model->updated_time = $equipment->updated_at;
        return $model->save();
    }

}
