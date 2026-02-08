<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsBasketballMatchLiveModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_basketball_match_live';
    protected $primaryKey = 'match_id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($live)
    {
        //更新比赛状态
        if (!empty($live->score) && count($live->score) >= 5) {
            $matchModel = new SportsBasketballMatchModel();
            $matchModel->updateColumnsByPk([
                'id' => $live->score[0],
                'status_id' => $live->score[1],
                'home_scores' => $live->score[3],
                'away_scores' => $live->score[4],
            ]);
        }

        $model = self::where(['match_id' => $live->id])->first();
        if (empty($model)) $model = new self();
        $model->match_id = $live->id;
        if (!empty($live->tlive)) $model->tlive = json_encode($live->tlive);
        if (!empty($live->score)) $model->score = json_encode($live->score);
        if (!empty($live->stats)) $model->stats = json_encode($live->stats);
        if (!empty($live->players)) $model->players = json_encode($live->players);
        return $model->save();
    }

}
