<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CmfStatisticsModel extends Model
{
    protected $table = 'cmf_statistics';
    protected $primaryKey = 'id';
    public $timestamps = true;

//    protected $hidden = ['id', 'created_at', 'updated_at'];

    public static function click($input)
    {
        $date = Helper::currentTime('date');
        $condition = [
            'type' => $input['type'],
            'action' => $input['action'],
            'date' => $date,
        ];
        if (self::where($condition)->exists()) {
            $flag = DB::table('cmf_statistics')->where($condition)->increment('click_cnt');
        } else {
            $model = new self();
            $model->type = $input['type'];
            $model->action = $input['action'];
            $model->date = $date;
            $model->click_cnt = 1;
            $flag = $model->save();
        }
        return $flag;
    }
}
