<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsBasketbalTeamPlayerLinksModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_basketball_team_player_links';
//    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($teamPlayerLink)
    {
        if (empty($teamPlayerLink->squad)) return;
        foreach ($teamPlayerLink->squad as $player) {
            $model = self::where(['team_id' => $teamPlayerLink->id, 'player_id' => $player->player->id])->first();
            if (empty($model)) $model = new self();
            $model->team_id = $teamPlayerLink->id;
            $model->player_id = $player->player->id;
            $model->save();
        }
    }

}
