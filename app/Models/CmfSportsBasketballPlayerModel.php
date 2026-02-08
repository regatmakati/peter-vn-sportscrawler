<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmfSportsBasketballPlayerModel extends Model
{
    protected $table = 'cmf_sports_basketball_player';
    protected $primaryKey = 'id';
    public $timestamps = true;

    public static function insertOrUpdate($player)
    {
        $model = self::where(['playerId' => $player->playerId])->first();
        if (empty($model)) {
            $model = new self();
            $model->playerId = $player->playerId;
            $model->teamId = $player->teamId;
            $model->playerNameCn = $player->playerNameCn;
            $model->playerNameCnShort = $player->playerNameCnShort;
            $model->playerNameEn = $player->playerNameEn;
            $model->playerTrad = $player->playerTrad ?? NULL;
            $model->playerTradShort = $player->playerTradShort;
            $model->playerLogo = $player->playerLogo;
            $model->playerNumber = $player->playerNumber;
        } else {
            $model->teamId = $player->teamId;
            $model->playerNameCn = $player->playerNameCn;
            $model->playerNameCnShort = $player->playerNameCnShort;
            $model->playerNameEn = $player->playerNameEn;
            $model->playerTrad = $player->playerTrad ?? NULL;
            $model->playerTradShort = $player->playerTradShort;
            $model->playerLogo = $player->playerLogo;
            $model->playerNumber = $player->playerNumber;
        }
        return $model->save();
    }

}
