<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmfSportsFootballTextLiveModel extends Model
{
    protected $table = 'cmf_sports_football_text_live';
    protected $primaryKey = 'id';
    public $timestamps = true;

    public static function insertOrUpdate($matchId, $textLive)
    {
        $columns['matchId'] = $matchId;
        $columns['live'] = $textLive;
        if (self::where(['matchId' => $columns['matchId']])->exists()) {    //更新
            self::where(['matchId' => $columns['matchId']])->update($columns);
        } else {    //创建
            self::insert($columns);
        }
    }

}
