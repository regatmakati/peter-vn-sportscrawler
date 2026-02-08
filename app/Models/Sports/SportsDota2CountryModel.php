<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsDota2CountryModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_dota2_country';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($country)
    {
        $model = self::where(['id' => $country->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $country->id;
        $model->name_en = $country->name_en;
        $model->name_zh = $country->name_zh;
        $model->abbr_zh = $country->abbr_zh;
        $model->abbr_en = $country->abbr_en;
        $model->logo = $country->logo;
        $model->updated_time = $country->updated_at;
        return $model->save();
    }

}
