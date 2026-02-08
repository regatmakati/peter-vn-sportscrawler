<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsDota2MatchSinglePlayerStatModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_dota2_match_single_player';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($match)
    {
        $model = self::where(['id' => $match->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $match->id;
        $model->match_single_id = $match->match_single->id;
        $model->team_id = $match->team->id;
        $model->player_id = $match->player->id;
        $model->hero_id = $match->hero->id;
        $model->position = $match->position;
        $model->kill = $match->kill;
        $model->die = $match->die;
        $model->assists = $match->assists;
        $model->equipments = json_encode($match->equipments);
        $model->level = $match->level;
        $model->last_hits = $match->last_hits;
        $model->denies = $match->denies;
        $model->gpm = $match->gpm;
        $model->xpm = $match->xpm;
        $model->hero_damage = $match->hero_damage;
        $model->tower_damage = $match->tower_damage;
        $model->gold = $match->gold;
        $model->gold_spent = $match->gold_spent;
        $model->spell_order = json_encode($match->spell_order);
        $model->rune_order = json_encode($match->rune_order);
        $model->equipments_order = json_encode($match->equipments_order);
        $model->updated_time = $match->updated_at;
        return $model->save();
    }

}
