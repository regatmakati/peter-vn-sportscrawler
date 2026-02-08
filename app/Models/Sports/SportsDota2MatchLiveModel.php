<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsDota2MatchLiveModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_dota2_match_live';
    protected $primaryKey = 'match_id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($matchLive)
    {
        //实时数据转储
        $model = self::where(['match_id' => $matchLive->id])->first();
        if (empty($model)) $model = new self();
        $model->match_id = $matchLive->id;
        $model->tournament_id = $matchLive->tournament->id;
        $model->roshan_timer = $matchLive->roshan_timer;
        $model->timer = json_encode($matchLive->timer);
        $model->home = json_encode($matchLive->home);
        $model->away = json_encode($matchLive->away);
        $model->economy_lines = json_encode($matchLive->economy_lines);
        $model->experience_lines = json_encode($matchLive->experience_lines);
        $model->is_bp = $matchLive->is_bp;
        $model->bp_data = json_encode($matchLive->bp_data);

        //更新比赛状态
        $matchModel = new SportsDota2MatchModel();
        $matchModel->updateColumnsByPk(['id' => $matchLive->id, 'status_id' => $matchLive->status_id]);

        //更新比赛单据数据
        $matchSingleModel = new SportsDota2MatchSingleModel();
        $func = function ($data) {
            return json_encode([
                'id' => $data->id,
                'win' => $data->stats[9],
                'score' => $data->score,
                'tower' => $data->tower,
                'barracks' => $data->barracks,
                'ban' => $data->ban,
                'pick' => $data->pick,
            ]);
        };

        if ($matchLive->home->side == 1) {
            $radiant = $func($matchLive->home);
            $dire = $func($matchLive->away);
        } else {
            $radiant = $func($matchLive->away);
            $dire = $func($matchLive->home);
        }
        if ($matchLive->home->stats[3] ?? NULL) {   //一血的队伍
            $firstTeam['first_blood'] = $matchLive->home->id;
        } else {
            $firstTeam['first_blood'] = $matchLive->away->id;
        }
        if ($matchLive->home->stats[4] ?? NULL) {   //一塔的队伍
            $firstTeam['first_tower'] = $matchLive->home->id;
        } else {
            $firstTeam['first_tower'] = $matchLive->away->id;
        }
        if ($matchLive->home->stats[5] ?? NULL) {   //首肉山的队伍
            $firstTeam['first_roushan'] = $matchLive->home->id;
        } else {
            $firstTeam['first_roushan'] = $matchLive->away->id;
        }
        if ($matchLive->home->stats[6] ?? NULL) {   //先十杀的队伍
            $firstTeam['ten_kill'] = $matchLive->home->id;
        } else {
            $firstTeam['ten_kill'] = $matchLive->away->id;
        }
        if ($matchLive->home->stats[10] ?? NULL) {  //先五杀的队伍
            $firstTeam['five_kill'] = $matchLive->home->id;
        } else {
            $firstTeam['five_kill'] = $matchLive->away->id;
        }

        $matchSingleModel->updateColumnsByConditions([
            'match_id' => $matchModel->id,
            'box_num' => $matchLive->box_num
        ], [
            'status_id' => $matchLive->single_status_id,
            'radiant' => $radiant,
            'dire' => $dire,
            'first_blood' => $firstTeam['first_blood'],
            'first_tower' => $firstTeam['first_tower'],
            'first_roushan' => $firstTeam['first_roushan'],
            'five_kill' => $firstTeam['five_kill'],
            'ten_kill' => $firstTeam['ten_kill'],
            'eco_list' => json_encode($matchLive->economy_lines),
            'exp_list' => json_encode($matchLive->experience_lines),
            'roshan_timer' => $matchLive->roshan_timer,
            'timer' => json_encode($matchLive->timer),
            'is_bp' => $matchLive->is_bp,
        ]);
        return $model->save();
    }

}
