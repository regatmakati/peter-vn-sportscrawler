<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Helpers\RedisKeyMap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CmfLiveModel extends BaseModel
{
    protected $connection = 'mysql'; // 第二个数据库
    protected $table = 'cmf_live';
    protected $primaryKey = 'uid';
    public $timestamps = false;
//    protected $hidden = ['id', 'created_at', 'updated_at'];

    const IS_VIDEO_REAL = 0;
    const IS_VIDEO_FAKE= 1;
    public static $videoMap = [
        self::IS_VIDEO_REAL => '已方直播',
        self::IS_VIDEO_FAKE => '第三方直播',
    ];

    CONST MANUAL_CLOSE_NO = 0;
    CONST MANUAL_CLOSE_YES = 1;
    public static $autoCloseMap = [
        self::MANUAL_CLOSE_NO => '自动',
        self::MANUAL_CLOSE_YES => '手动',
    ];

    public function user()
    {
        return $this->hasOne(CmfUserModel::class, 'id', 'uid')
            ->select('id' ,'user_nicename', 'avatar', 'avatar_thumb', 'viewnum');
    }

    public function getThumbAttribute($value)
    {
        if (!empty($value)) {
			$pos = strpos($value,'://');
			if(!$pos){			
				return config('params.domain.image') . "/{$value}";
			}		
			return $value;				
        }else{
            if(!empty($this->pic_full_url)){
                return $this->pic_full_url;
            }
        }
        return $value;
    }

    public function getPicFullUrlAttribute($value)
    {
        if (empty($value)) return;
        $config = CmfOptionModel::getConfig(CmfOptionModel::OPTION_SITE_INFO);
		$pos = strpos($value,'://');
		if(!$pos){
			return "{$config->psite}/{$value}";
		}		
        return "{$value}";
    }


    public static function getRandomList($input)
    {
        $liveList = json_decode(Redis::get(RedisKeyMap::getLiveRandomList()));
        if (!empty($liveList)) return $liveList;
        $model = self::with(['user'])
            ->whereRaw("uid >= ((SELECT MAX(uid) FROM cmf_live)-(SELECT MIN(uid) FROM cmf_live)) * RAND() + (SELECT MIN(uid) FROM cmf_live)");


        if (!empty($input['class_id'])) $model->where(['liveclassid' => $input['class_id']]);
        $liveList = $model->take($input['limit'])->get();
        if (count($liveList) > 0) {
            foreach ($liveList as $key=>$value){
                $liveList[$key]['id'] = $value['user']['id'];
                $liveList[$key]['user_nicename'] = $value['user']['user_nicename'];
                $liveList[$key]['avatar'] = $value['user']['avatar'];
                $liveList[$key]['avatar_thumb'] = $value['user']['avatar_thumb'];
                $liveList[$key]['viewnum'] = $value['user']['viewnum'];
                unset($liveList[$key]['user']);
            }
            Redis::setex(RedisKeyMap::getLiveRandomList(), config('params.cache.ttl'), $liveList);
            return $liveList;
        }
        return [];
    }

    public static function getPcLiveList($input)
    {
        $liveList = json_decode(Redis::get(RedisKeyMap::getPcLiveList($input['page'], $input['limit'], $input['class_id'])));
        if (!empty($liveList)) return $liveList;

        $model = self::with(['user'])
            ->orderBy('recom_sort', 'DESC')
            ->join('cmf_user', 'cmf_user.id', '=', 'cmf_live.uid')
            ->select('cmf_live.*')
            ->orderByDesc('cmf_live.recom_sort')
            ->orderBy('cmf_live.isvideo')
            ->orderByDesc('cmf_user.viewnum')
        ;

        if (!empty($input['class_id'])) $model->where(['liveclassid' => $input['class_id']]);
        $liveList = $model->paginate($input['limit']);
        if (count($liveList) > 0) {
            Redis::setex(RedisKeyMap::getPcLiveList($input['page'], $input['limit'], $input['class_id']), config('params.cache.ttl'), $liveList);
            return $liveList;
        }
        return [];
    }

    public static function getThirdLive(){
        $liveList = self::where('isvideo','=', self::IS_VIDEO_FAKE)
            ->where('third_pull','like',"%http://%")
            ->orderBy('uid','ASC')
            ->get();
        if (count($liveList) > 0) {
            return $liveList;
        }
        return [];
    }


    public static function getDeleteLive(){
		$nowTime = time() - 60;
        $liveList = self::where('starttime','<',$nowTime)
            ->where(['is_manual_close' => self::MANUAL_CLOSE_NO])
            ->orderBy('uid','ASC')
            ->get();
        if (count($liveList) > 0) {
            return $liveList;
        }
        return [];
    }

    public static function deleteLive($uid){
        $isInsert = CmfLiveRecordModel::deleteRoomUpdateLiveRecord($uid);
        if ($isInsert) echo Helper::currentTime() . "直播间{$uid}记录已增加！\r\n";
        $isDel = self::where('uid','=',$uid)->delete();
        if ($isDel) echo Helper::currentTime() . "直播间{$uid}已删除！\r\n";
    }

    public static function getAllLive(){
		$pullDomain = config('params.tencent.live.pullDomain');
        $liveList = self::where('pull','like',"%$pullDomain%")
            ->orderBy('uid','ASC')
            ->get();
        if (count($liveList) > 0) {
            return $liveList;
        }
        return [];
    }

    public static function updateLive($input){

        $update['pic_full_url'] = $input['pic_full_url'];
		$update['isoff'] = $input['isoff'];
        $res = self::where('uid','=',$input['uid'])
			->update($update);
        if ($res) {
            return true;
        }
        return false;

    }

}
