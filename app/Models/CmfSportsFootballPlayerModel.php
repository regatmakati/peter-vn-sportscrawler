<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmfSportsFootballPlayerModel extends Model
{
    protected $table = 'cmf_sports_football_player';
    protected $primaryKey = 'id';
    public $timestamps = true;

    public static function insertOrUpdate($player)
    {
        $columns['playerId'] = $player->matchId ?? $player['playerId'];
        $columns['teamId'] = $player->teamId ?? $player['teamId'];
        $columns['nameCnShort'] = $player->nameCnShort ?? $player['nameCnShort'];
        $columns['nameEn'] = $player->nameEn ?? $player['nameEn'];
        $columns['nameEnShort'] = isset($player->nameEnShort) ? $player->nameEnShort : '';
        $columns['nameTrad'] = $player->nameTrad ?? $player['nameTrad'];
        $columns['number'] = $player->number ?? $player['number'];
        $columns['kind'] = $player->kind ?? $player['kind'];
        $columns['position'] = $player->position ?? $player['position'];
        if (self::where(['playerId' => $columns['playerId']])->exists()) {    //更新
            self::where(['playerId' => $columns['playerId']])->update($columns);
        } else {    //创建
            self::insert($columns);
        }
    }

}
