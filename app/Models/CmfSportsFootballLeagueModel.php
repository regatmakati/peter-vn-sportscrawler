<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmfSportsFootballLeagueModel extends Model
{
    protected $table = 'cmf_sports_football_league';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['id', 'created_at', 'updated_at'];

    public static function insertOrUpdate($league)
    {
        $columns['leagueId'] = $league->leagueId ?? $league['leagueId'];
        $columns['leagueNameCn'] = $league->leagueNameCn ?? $league['leagueNameCn'];
        $columns['leagueNameTrad'] = $league->leagueNameTrad ?? $league['leagueNameTrad'];
        $columns['leagueNameEn'] = $league->leagueNameEn ?? $league['leagueNameEn'];
        $columns['leagueNameCnShort'] = $league->leagueNameCnShort ?? $league['leagueNameCnShort'];
        $columns['leagueNameTradShort'] = $league->leagueNameTradShort ?? $league['leagueNameTradShort'];
        $columns['leagueNameEnShort'] = $league->leagueNameEnShort ?? $league['leagueNameEnShort'];
        if (self::where(['leagueId' => $columns['leagueId']])->exists()) {    //更新
            self::where(['leagueId' => $columns['leagueId']])->update($columns);
        } else {    //创建
            self::insert($columns);
        }
    }

}
