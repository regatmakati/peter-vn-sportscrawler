<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsDota2TournamentPvpLineModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_dota2_tournament_pvp_line';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($tournamentPvpLine)
    {
        $model = self::where(['id' => $tournamentPvpLine->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $tournamentPvpLine->id;
        $model->tournament_id = $tournamentPvpLine->tournament->id;
        $model->stage_id = $tournamentPvpLine->stage->id;
        $model->part_stage_id = $tournamentPvpLine->part_stage->id;
        $model->lines = json_encode($tournamentPvpLine->lines);
        $model->updated_time = $tournamentPvpLine->updated_at;
        return $model->save();
    }

}
