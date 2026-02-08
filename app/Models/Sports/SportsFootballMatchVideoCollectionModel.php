<?php

namespace App\Models\Sports;

use App\Models\BaseModel;

class SportsFootballMatchVideoCollectionModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_football_match_video_collection';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];

    public static function insertOrUpdate($videoCollection)
    {
        $model = self::where(['id' => $videoCollection->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $videoCollection->id;
        $model->title = $videoCollection->title;
        $model->mobile_link = $videoCollection->mobile_link;
        $model->pc_link = $videoCollection->pc_link;
        $model->type = $videoCollection->type;
        $model->cover = $videoCollection->cover;
        $model->duration = $videoCollection->duration;
        $model->updated_time = $videoCollection->updated_at;
        return $model->save();
    }

}
