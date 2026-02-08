<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsDota2TournamentModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_dota2_tournament';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($tournament)
    {
        $model = self::where(['id' => $tournament->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $tournament->id;
        $model->name_en = $tournament->name_en;
        $model->name_zh = $tournament->name_zh;
        $model->abbr_zh = $tournament->abbr_zh;
        $model->abbr_en = $tournament->abbr_en;
        $model->logo = $tournament->logo;
        $model->status_id = $tournament->status_id;
        $model->cover = $tournament->cover;
        $model->start_time = $tournament->start_time;
        $model->end_time = $tournament->end_time;
        $model->type = $tournament->type;
        $model->city_name = $tournament->city_name;
        $model->city_name_en = $tournament->city_name_en;
        $model->price_pool = $tournament->price_pool;
        $model->updated_time = $tournament->updated_at;
        return $model->save();
    }

    public static function getBeforeAfter15DaysPks()
    {
        return self::where('end_time', '<=', strtotime("+15 day"))
            ->where('start_time', '>=', strtotime("-15 day"))->pluck('id');
    }

}
