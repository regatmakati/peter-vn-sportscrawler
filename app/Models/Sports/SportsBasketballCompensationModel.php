<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsBasketballCompensationModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_basketball_compensation';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($compensation)
    {
        $model = self::where(['match_id' => $compensation->id])->first();
        if (empty($model)) $model = new self();
        $model->match_id = $compensation->id;
        $model->history = json_encode($compensation->history);
        $model->recent = json_encode($compensation->recent);
        $model->similar = json_encode($compensation->similar);
        $model->updated_time = $compensation->updated_at;
        return $model->save();
    }


}
