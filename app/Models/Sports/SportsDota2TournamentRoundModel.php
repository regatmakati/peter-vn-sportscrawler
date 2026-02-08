<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsDota2TournamentRoundModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_dota2_tournament_round';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($tournamentRound)
    {
        $model = self::where(['id' => $tournamentRound->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $tournamentRound->id;
        $model->name_en = $tournamentRound->name_en;
        $model->name_zh = $tournamentRound->name_zh;
        $model->abbr_zh = $tournamentRound->abbr_zh;
        $model->abbr_en = $tournamentRound->abbr_en;
        $model->updated_time = $tournamentRound->updated_at;
        return $model->save();
    }

}
