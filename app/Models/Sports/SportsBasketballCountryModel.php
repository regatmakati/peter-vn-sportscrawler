<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsBasketballCountryModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_basketball_country';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($country)
    {
        $model = self::where(['id' => $country->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $country->id;
        $model->category_id = $country->category_id;
        $model->name_en = $country->name_en;
        $model->name_zh = $country->name_zh;
        $model->name_zht = $country->name_zht;
        $model->logo = $country->logo;
        $model->updated_time = $country->updated_at;
        return $model->save();
    }

}
