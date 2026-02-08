<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmfSportsFootballTeamModel extends Model
{
    protected $table = 'cmf_sports_football_team';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['id', 'created_at', 'updated_at'];

    public static function insertOrUpdate($team)
    {
        $columns['teamId'] = $team->teamId ?? $team['teamId'];
        $columns['rank'] = $team->rank ?? $team['rank'];
        $columns['nameCn'] = $team->nameCn ?? $team['nameCn'];
        $columns['nameTrad'] = $team->nameTrad ?? $team['nameTrad'];
        $columns['nameEn'] = $team->nameEn ?? $team['nameEn'];
        $columns['logo'] = $team->logo ?? $team['logo'];
        if (self::where(['teamId' => $columns['teamId']])->exists()) {    //更新
            self::where(['teamId' => $columns['teamId']])->update($columns);
        } else {    //创建
            self::insert($columns);
        }
    }

}
