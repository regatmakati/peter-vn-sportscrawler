<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmfSportsBasketballTextLiveModel extends Model
{
    protected $table = 'cmf_sports_basketball_text_live';
    protected $primaryKey = 'id';
    public $timestamps = true;

    public static function insertOrUpdate($matchId, $textLive)
    {
        $model = self::where(['matchId' => $matchId])->first();
        if (empty($model)) {
            $model = new self();
            $model->matchId = $matchId;
            $model->live = $textLive;
        } else {
            $model->live = $textLive;
        }
        return $model->save();
    }

}
