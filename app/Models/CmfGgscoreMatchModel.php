<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Helpers\RedisKeyMap;
use App\Models\Sports\SportsFootballMatchModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CmfGgscoreMatchModel extends BaseModel
{
    protected $connection = 'mysql'; // 第二个数据库
    protected $table = 'cmf_ggscore_match';
    protected $primaryKey = 'id';
    public $timestamps = false;


    /**
     * @param $input
     * @return array|mixed
     */
    public static function getMatchAllListV3($input)
    {
        $matchList = json_decode(Redis::get(RedisKeyMap::getFootballMatchAllListV3($input['page'])));
        if (!empty($matchList)) return $matchList;
        $nowDate = date("Y-m-d");
        $endDate = date('Y-m-d', strtotime("+2 day"));

        $query = self::from('cmf_ggscore_match as m')
            ->join('cmf_ggscore_league as l', function ($join) {
                $join->on('l.league_id', '=', 'm.league_id')
                    ->where('l.has_live', 1);
            })
            ->leftJoin('cmf_ggscore_match_anchor as a', 'm.match_id', '=', 'a.match_id')
            ->select('m.*', 'a.user_ids')
            ->where('m.sport_id','=',$input['sport_id'])
            ->whereRaw(
                "FROM_UNIXTIME(m.start_time, '%Y-%m-%d') BETWEEN ? AND ?",
                [$nowDate, $endDate]
            );


        $matchList = [];
        $total = (clone $query)->count();

        $list = $query
            ->orderBy('m.start_time', 'ASC')
            ->orderBy('m.match_id', 'ASC')
            ->offset(($input['page'] - 1) * 10)
            ->limit(10)
            ->get();
        $matchList['total'] = $total;
        $matchList['list'] = [];
        if (count($list) > 0) {

            $anchorList = CmfAnchorAuth::getAllAnchor();
            foreach ($list as &$v){
                $d = [];
                if(!empty($v['user_ids'])){
                    $userIds = explode(',', $v['user_ids']);
                    foreach ($userIds as $uid){
                        $d[] = $anchorList[$uid];
                    }
                }
                $v['lives'] = $d;
            }
            $matchList['list'] = $list;
            Redis::setex(RedisKeyMap::getFootballMatchAllListV3($input['page']), config('params.cache.ttl'), $matchList);
        }
        return $matchList;
    }


    public static function getMatchListByHot($input)
    {
        $matchList = json_decode(Redis::get(RedisKeyMap::getFootballMatchListByHotV3($input['page'])));
        if (!empty($matchList)) return $matchList;
        $nowDate = date("Y-m-d");
        $endDate = date('Y-m-d', strtotime("+2 day"));

        $query = self::from('cmf_ggscore_match as m')
            ->join('cmf_ggscore_league as l', function ($join) {
                $join->on('l.league_id', '=', 'm.league_id')
                    ->where('l.has_live', 1);
            })
            ->leftJoin('cmf_ggscore_match_anchor as a', 'm.match_id', '=', 'a.match_id')
            ->select('m.*', 'a.user_ids')
            ->where('m.is_hot','=',  1)
            ->where('m.sport_id','=',$input['sport_id'])
            ->whereRaw(
                "FROM_UNIXTIME(m.start_time, '%Y-%m-%d') BETWEEN ? AND ?",
                [$nowDate, $endDate]
            );

        $matchList = [];
        $total = (clone $query)->count();

        $list = $query
            ->orderBy('m.start_time', 'ASC')
            ->orderBy('m.match_id', 'ASC')
            ->offset(($input['page'] - 1) * 10)
            ->limit(10)
            ->get();
        $matchList['total'] = $total;
        $matchList['list'] = [];
        if (count($list) > 0) {
            $anchorList = CmfAnchorAuth::getAllAnchor();
            foreach ($list as &$v){
                $d = [];
                if(!empty($v['user_ids'])){
                    $userIds = explode(',', $v['user_ids']);
                    foreach ($userIds as $uid){
                        $d[] = $anchorList[$uid];
                    }
                }
                $v['lives'] = $d;
            }

            $matchList['list'] = $list;
            Redis::setex(RedisKeyMap::getFootballMatchListByHotV3($input['page']), config('params.cache.ttl'), $matchList);
        }
        return $matchList;
    }

    /**
     * @param $input
     * @return array|mixed
     */
    public static function getMatchPLayingListV3($input)
    {
        $matchList = json_decode(Redis::get(RedisKeyMap::getFootballMatchPlayingListV3($input['page'])));
        if (!empty($matchList)) return $matchList;
        $showStartTime = strtotime("-1 day");


        $query = self::from('cmf_ggscore_match as m')
            ->join('cmf_ggscore_league as l', function ($join) {
                $join->on('l.league_id', '=', 'm.league_id')
                    ->where('l.has_live', 1);
            })
            ->leftJoin('cmf_ggscore_match_anchor as a', 'm.match_id', '=', 'a.match_id')
            ->select('m.*', 'a.user_ids')
            ->where('m.sport_id','=',$input['sport_id'])
            ->where('m.start_time','>=',  $showStartTime)
            ->where('m.status','=',  'live');

        $matchList = [];
        $total = (clone $query)->count();
        $list = $query
            ->orderBy('m.start_time', 'ASC')
            ->orderBy('m.match_id', 'ASC')
            ->offset(($input['page'] - 1) * 10)
            ->limit(10)
            ->get();

        $matchList['total'] = $total;
        $matchList['list'] = [];
        if (count($list) > 0) {
            $anchorList = CmfAnchorAuth::getAllAnchor();
            foreach ($list as &$v){
                $d = [];
                if(!empty($v['user_ids'])){
                    $userIds = explode(',', $v['user_ids']);
                    foreach ($userIds as $uid){
                        $d[] = $anchorList[$uid];
                    }
                }
                $v['lives'] = $d;
            }

            $matchList['list'] = $list;
            Redis::setex(RedisKeyMap::getFootballMatchPlayingListV3($input['page']), config('params.cache.ttl'), $matchList);
        }
        return $matchList;
    }


    public static function getMatchListByDateV3($input)
    {
        $matchList = json_decode(Redis::get(RedisKeyMap::getFootballMatchListByDateV3($input['page'], $input['date'], $input['action'])));
        if (!empty($matchList)) return $matchList;

        $model = self::from('cmf_ggscore_match as m')
            ->join('cmf_ggscore_league as l', function ($join) {
                $join->on('l.league_id', '=', 'm.league_id')
                    ->where('l.has_live', 1);
            })
            ->leftJoin('cmf_ggscore_match_anchor as a', 'm.match_id', '=', 'a.match_id')
            ->select('m.*', 'a.user_ids')
            ->where('m.sport_id','=',$input['sport_id'])
            ->whereRaw("FROM_UNIXTIME(m.start_time, '%Y-%m-%d') = '{$input['date']}'");

        if (isset($input['action'])) {
            switch ($input['action']) {
                case 1:     //赛程
                    $model->whereIn('status', 'live');
                    break;
                case 2:     //赛果
                    $model->where(['status' => 'past']);
                    break;
            }
        }

        $matchList = [];
        $total =  (clone $model)->count();
        $matchList['total'] = $total;

        $list = $model->orderBy('m.start_time', 'ASC')
            ->orderBy('m.match_id', 'ASC')
            ->offset(($input['page'] - 1) * 10)
            ->limit(10)
            ->get();
        $matchList['list'] = [];
        if (count($list) > 0) {

            $anchorList = CmfAnchorAuth::getAllAnchor();
            foreach ($list as &$v){
                $d = [];
                if(!empty($v['user_ids'])){
                    $userIds = explode(',', $v['user_ids']);
                    foreach ($userIds as $uid){
                        $d[] = $anchorList[$uid];
                    }
                }
                $v['lives'] = $d;
            }

            $matchList['list'] = $list;
            Redis::setex(RedisKeyMap::getFootballMatchListByDateV3($input['page'], $input['date'], $input['action']), config('params.cache.ttl'), json_encode($matchList));

        }
        return $matchList;
    }


    /**
     * @param $input
     * @return SportsFootballMatchModel|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|mixed|object|null
     */
    public static function getMatchSum($input)
    {
        $match = json_decode(Redis::get('getFootballMatchSum'));
        if (!empty($match)) return $match;
        $nowDate = date("Y-m-d");
        $t1Date = date('Y-m-d', strtotime("+1 day"));
        $t2Date = date('Y-m-d', strtotime("+2 day"));

        $todayTotal = self::whereRaw(
            "FROM_UNIXTIME(start_time, '%Y-%m-%d') BETWEEN ? AND ?",
            [$nowDate, $t1Date]
        )->where('sport_id','=',$input['sport_id'])->count();


        $match['todayTotal'] = $todayTotal;

        $tomorrowTotal = self::whereRaw(
            "FROM_UNIXTIME(start_time, '%Y-%m-%d') BETWEEN ? AND ?",
            [$t1Date, $t2Date]
        )->where('sport_id','=',$input['sport_id'])->count();

        $match['tomorrowTotal'] = $tomorrowTotal;
        $match['allTotal'] = $todayTotal+$tomorrowTotal;


        $showStartTime = strtotime("-1 day");
        $playingTotal = self::where('start_time','>=',  $showStartTime)
            ->where('sport_id','=',$input['sport_id'])->count();

        $match['playingTotal'] = $playingTotal;



        $hotTotal = self::where('is_hot','=',  1)->whereRaw(
            "FROM_UNIXTIME(start_time, '%Y-%m-%d') BETWEEN ? AND ?",
            [$nowDate, $t2Date]
        )->where('sport_id','=',$input['sport_id'])->count();

        $match['hotTotal'] = $hotTotal;

        Redis::setex('getFootballMatchSum', config('params.cache.ttl'), json_encode($match));
        return $match;
    }

}
