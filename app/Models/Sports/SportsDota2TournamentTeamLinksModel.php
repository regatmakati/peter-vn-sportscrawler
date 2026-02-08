<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsDota2TournamentTeamLinksModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_dota2_tournament_team_links';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($link)
    {
        $model = self::where(['id' => $link->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $link->id;
        $model->tournament_id = $link->tournament->id;
        $model->team_id = $link->team->id;
        return $model->save();
    }

}
