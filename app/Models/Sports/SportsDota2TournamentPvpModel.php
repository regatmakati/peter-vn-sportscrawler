<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsDota2TournamentPvpModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_dota2_tournament_pvp';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($tournamentPvp)
    {
        $model = self::where(['id' => $tournamentPvp->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $tournamentPvp->id;
        $model->match_id = $tournamentPvp->match->id;
        $model->stage_id = $tournamentPvp->stage->id;
        $model->part_stage_id = $tournamentPvp->part_stage->id;
        $model->round_id = $tournamentPvp->round->id;
        $model->tournament_id = $tournamentPvp->tournament->id;
        $model->column_num = $tournamentPvp->column_num;
        $model->row_num = $tournamentPvp->row_num;
        $model->sequence_type = $tournamentPvp->sequence_type;
        $model->num = $tournamentPvp->num;
        $model->is_promotion = $tournamentPvp->is_promotion;
        $model->promotion_name = $tournamentPvp->promotion_name;
        $model->updated_time = $tournamentPvp->updated_at;
        return $model->save();
    }

}
