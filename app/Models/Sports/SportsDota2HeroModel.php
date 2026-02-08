<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsDota2HeroModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_dota2_hero';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($hero)
    {
        $model = self::where(['id' => $hero->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $hero->id;
        $model->name_zh = $hero->name_zh;
        $model->name_en = $hero->name_en;
        $model->abbr_zh = $hero->abbr_zh;
        $model->abbr_en = $hero->abbr_en;
        $model->logo = $hero->logo;
        $model->icon = $hero->icon;
        $model->vert_logo = $hero->vert_logo;
        $model->attrs = json_encode($hero->attrs);
        $model->updated_time = $hero->updated_at;
        return $model->save();
    }

}
