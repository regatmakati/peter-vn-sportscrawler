<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsDota2PlayerModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_dota2_player';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($player)
    {
        $model = self::where(['id' => $player->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $player->id;
        $model->name_zh = $player->name_zh;
        $model->abbr_zh = $player->abbr_zh;
        $model->name_en = $player->name_en;
        $model->abbr_en = $player->abbr_en;
        $model->logo = $player->logo;
        $model->team_id = $player->team->id;
        $model->country_id = $player->country->id;
        $model->real_name = $player->real_name;
        $model->birthday = $player->birthday;
        $model->retired = $player->retired;
        $model->position = $player->position;
        $model->updated_time = $player->updated_at;
        return $model->save();
    }

}
