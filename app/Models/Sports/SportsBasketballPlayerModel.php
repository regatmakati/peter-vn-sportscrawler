<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsBasketballPlayerModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_basketball_player';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($player)
    {
        $model = self::where(['id' => $player->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $player->id;
        //$model->team_id = $player->team_id;
        $model->name_zh = $player->name_zh;
        $model->name_en = $player->name_en;
        $model->short_name_zh = $player->short_name_zh;
        $model->short_name_en = $player->short_name_en;
        $model->birthday = $player->birthday;
        $model->age = $player->age;
        $model->weight = $player->weight;
        $model->height = $player->height;
        $model->drafted = $player->drafted;
        $model->league_career_age = $player->league_career_age;
        $model->school = $player->school;
        $model->city = $player->city;
        $model->salary = $player->salary;
        $model->shirt_number = $player->shirt_number ?? NULL;
        $model->updated_time = $player->updated_at;
        return $model->save();
    }

//    public static function updateTeamId($teamPlayerLink)
//    {
//        if (empty($teamPlayerLink->squad)) return;
//        foreach ($teamPlayerLink->squad as $player) {
//            $model = new self();
//            $model->updateColumnsByPk(['id' => $player->player->id, 'team_id' => $teamPlayerLink->id]);
//        }
//    }

}
