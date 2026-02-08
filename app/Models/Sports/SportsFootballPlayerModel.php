<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsFootballPlayerModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_football_player';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($player)
    {
        $model = self::where(['id' => $player->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $player->id;
        //$model->team_id = $player->team_id;
        $model->country_id = $player->country_id;
        $model->name_zh = $player->name_zh;
        $model->name_en = $player->name_en;
        $model->short_name_zh = $player->short_name_zh;
        $model->short_name_en = $player->short_name_en;
        $model->birthday = $player->birthday;
        $model->age = $player->age;
        $model->weight = $player->weight;
        $model->height = $player->height;
        $model->nationality = $player->nationality;
        $model->market_value = $player->market_value;
        $model->market_value_currency = $player->market_value_currency;
        $model->contract_until = $player->contract_until;
        $model->position = $player->position;
        $model->positions = json_encode($player->positions) ?? NULL;
        $model->preferred_foot = $player->preferred_foot;
        $model->characteristics = json_encode($player->characteristics) ?? NULL;
        $model->ability = json_encode($player->ability) ?? NULL;
        $model->updated_time = $player->updated_at;
        return $model->save();
    }

}
