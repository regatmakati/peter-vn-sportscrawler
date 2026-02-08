<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsDota2TournamentRankModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_dota2_tournament_rank';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($tournamentRank)
    {
        $model = self::where(['id' => $tournamentRank->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $tournamentRank->id;
        $model->win = $tournamentRank->win;
        $model->lose = $tournamentRank->lose;
        $model->equal = $tournamentRank->equal;
        $model->score = $tournamentRank->score;
        $model->team_id = $tournamentRank->team->id;
        $model->stage_id = $tournamentRank->stage->id;
        $model->part_stage_id = $tournamentRank->part_stage->id;
        $model->tournament_id = $tournamentRank->tournament->id;
        $model->updated_time = $tournamentRank->updated_at;
        return $model->save();
    }
}
