<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsBasketballCategoryModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_basketball_category';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['id', 'created_at', 'updated_at'];

    public static function insertOrUpdate($category)
    {
        $model = self::where(['id' => $category->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $category->id;
        $model->name_en = $category->name_en;
        $model->name_zh = $category->name_zh;
        $model->name_zht = $category->name_zht;
        $model->updated_time = $category->updated_at;
        return $model->save();
    }

}
