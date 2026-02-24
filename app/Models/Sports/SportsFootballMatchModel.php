<?php

namespace App\Models\Sports;

use App\Helpers\Helper;
use App\Helpers\RedisKeyMap;
use App\Models\BaseModel;
use App\Models\CmfAnchorAuth;
use App\Models\CmfLiveModel;
use Illuminate\Support\Facades\Redis;

class SportsFootballMatchModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_football_match';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];
    protected $appends = ['match_date', 'state_str', 'is_playing'];

    CONST STATUS_ABNORMAL = 0;
    CONST STATUS_NOT_START = 1;
    CONST STATUS_FIRST_HALF = 2;
    CONST STATUS_MIDFIELD = 3;
    CONST STATUS_SECOND_HALF  = 4;
    CONST STATUS_OVERTIME = 5;
    CONST STATUS_OVERTIME_GIVE_UP = 6;
    CONST STATUS_KICK = 7;
    CONST STATUS_FINISH = 8;
    CONST STATUS_DELAY = 9;
    CONST STATUS_INTERRUPT = 10;
    CONST STATUS_HALF_CUT = 11;
    CONST STATUS_CANCEL = 12;
    CONST STATUS_DETERMINE = 13;

    public static $statusMap = [
        self::STATUS_ABNORMAL => '比赛异常',
        self::STATUS_NOT_START => '未开赛',
        self::STATUS_FIRST_HALF => '上半场',
        self::STATUS_MIDFIELD => '中场',
        self::STATUS_SECOND_HALF => '下半场',
        self::STATUS_OVERTIME => '加时赛',
        self::STATUS_OVERTIME_GIVE_UP => '加时赛(弃用)',
        self::STATUS_KICK => '点球决战',
        self::STATUS_FINISH => '完场',
        self::STATUS_DELAY => '延迟',
        self::STATUS_INTERRUPT => '中断',
        self::STATUS_HALF_CUT => '腰斩',
        self::STATUS_CANCEL => '取消',
        self::STATUS_DETERMINE => '待定',
    ];

    public static $statusAdminMap = [
        self::STATUS_ABNORMAL => '比赛异常',
        self::STATUS_NOT_START => '未开赛',
        'playing' => '进行中',
        self::STATUS_FINISH => '完场',
        self::STATUS_DELAY => '延迟',
        self::STATUS_INTERRUPT => '中断',
        self::STATUS_HALF_CUT => '腰斩',
        self::STATUS_CANCEL => '取消',
        self::STATUS_DETERMINE => '待定',
    ];

    public static $playingStatusMap = [
        self::STATUS_FIRST_HALF,
        self::STATUS_MIDFIELD,
        self::STATUS_SECOND_HALF,
        self::STATUS_OVERTIME,
        self::STATUS_KICK,
    ];

    public static $notStartStatusMap = [
        self::STATUS_ABNORMAL,
        self::STATUS_NOT_START,
        self::STATUS_DELAY,
        self::STATUS_INTERRUPT,
        self::STATUS_HALF_CUT,
        self::STATUS_CANCEL,
        self::STATUS_DETERMINE,
    ];


    CONST NEUTRAL_YES = 1;
    CONST NEUTRAL_NO= 0;

    public static $neutralMap = [
        self::NEUTRAL_YES => '是',
        self::NEUTRAL_NO=> '否',
    ];


    public function homeTeam()
    {
        return $this->hasOne(SportsFootballTeamModel::class, 'id', 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->hasOne(SportsFootballTeamModel::class, 'id', 'away_team_id');
    }

    public function league()
    {
        return $this->hasOne(SportsFootballCompetitionModel::class, 'id', 'competition_id');
    }


    public function anchor()
    {
        return $this->hasOne(SportsFootballMatchAnchorModel::class, 'match_id', 'id');
    }

    public function getMatchDateAttribute()
    {
        return date("Y-m-d", $this->attributes["match_time"]);
    }

    public function getStateStrAttribute()
    {
        return self::$statusMap[$this->status_id];
    }

    public function getIsPlayingAttribute()
    {
        //进行中
        if (in_array($this->status_id, self::$playingStatusMap)) return 2;
        //已完场
        if (self::STATUS_FINISH == $this->status_id) return 3;
        //未开赛
        return 1;
    }

    /**
     * @param $match
     * @return bool
     */
    public static function insertOrUpdate($match)
    {
        $model = self::where(['id' => $match->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $match->id;
        $model->season_id = $match->season_id;
        $model->competition_id = $match->competition_id;
        $model->home_team_id = $match->home_team_id;
        $model->away_team_id = $match->away_team_id;
        $model->status_id = $match->status_id;
        $model->match_time = $match->match_time;
        $model->neutral = $match->neutral;
        $model->note = $match->note;
        if (isset($match->home_scores)) $model->home_scores = json_encode($match->home_scores);
        if (isset($match->away_scores)) $model->away_scores = json_encode($match->away_scores);
        $model->home_position = $match->home_position;
        $model->away_position = $match->away_position;
        if (isset($match->coverage)) $model->coverage = json_encode($match->coverage);
        if (isset($match->venue_id)) $model->venue_id = $match->venue_id;
        if (isset($match->referee_id)) $model->referee_id = $match->referee_id;
        if (isset($match->round)) $model->round = json_encode($match->round);
        if (isset($match->environment)) $model->environment = json_encode($match->environment);
        $model->updated_time = $match->updated_at;
        return $model->save();
    }

    /**
     * @param $input
     * @return array|mixed
     */
    public static function getMatchAllList($input)
    {
        $matchList = json_decode(Redis::get(RedisKeyMap::getFootballMatchAllListV2($input['page'])));
        if (!empty($matchList)) return $matchList;
        $nowDate = date("Y-m-d");
        $endDate = date('Y-m-d', strtotime("+15 day"));
        //$playingStatusMap = implode(',', self::$playingStatusMap);
        $matchList = self::with(['homeTeam', 'awayTeam', 'league'])
            ->whereRaw("FROM_UNIXTIME(match_time, '%Y-%m-%d') BETWEEN '{$nowDate}' AND '{$endDate}'")
            //->whereRaw("IF(status_id IN({$playingStatusMap}), (live_url_1!='' or live_url_2!='' or live_url_3!=''), 1=1)")
            ->orderBy('match_time', 'ASC')
            ->orderBy('status_id', 'ASC')
            ->offset(($input['page'] - 1) * 10)
            ->limit(10)
            ->get();

        if (count($matchList) > 0) {
            $data = [];

            $matchList = Helper::array_index_array($matchList, 'match_date');
            foreach ($matchList as $date => $list) {
                $data[] = [
                    'week_day_str' => Helper::getWeekDayStr($date),
                    'date' => $date,
                    'list' => $list,
                ];
            }
            Redis::setex(RedisKeyMap::getFootballMatchAllListV2($input['page']), config('params.cache.ttl'), $matchList);
            return $data;
        }
        return [];
    }


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

        $query = self::with(['homeTeam', 'awayTeam', 'league','anchor'])
            ->whereRaw(
                "FROM_UNIXTIME(match_time, '%Y-%m-%d') BETWEEN ? AND ?",
                [$nowDate, $endDate]
            );
        $matchList = [];
        $total = (clone $query)->count();

        $list = $query
            ->orderBy('match_time', 'ASC')
            ->orderBy('status_id', 'ASC')
            ->offset(($input['page'] - 1) * 10)
            ->limit(10)
            ->get();
        $matchList['total'] = $total;
        $matchList['list'] = [];
        if (count($list) > 0) {

            $anchorList = CmfAnchorAuth::getAllAnchor();
            foreach ($list as &$v){
                if($v['anchor'] && $v['anchor']['user_ids'] != ''){
                    $d = [];
                    $userIds = explode(',', $v['anchor']['user_ids']);
                    foreach ($userIds as $uid){
                        $d[] = $anchorList[$uid];
                    }

                    $v['lives'] = $d;
                }
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

        $query = self::with(['homeTeam', 'awayTeam', 'league','anchor'])
            ->where('is_hot','=',  1)
            ->whereRaw(
                "FROM_UNIXTIME(match_time, '%Y-%m-%d') BETWEEN ? AND ?",
                [$nowDate, $endDate]
            );
        $matchList = [];
        $total = (clone $query)->count();

        $list = $query
            ->orderBy('match_time', 'ASC')
            ->orderBy('status_id', 'ASC')
            ->offset(($input['page'] - 1) * 10)
            ->limit(10)
            ->get();
        $matchList['total'] = $total;
        $matchList['list'] = [];
        if (count($list) > 0) {
            $anchorList = CmfAnchorAuth::getAllAnchor();
            foreach ($list as &$v){
                if($v['anchor'] && $v['anchor']['user_ids'] != ''){
                    $d = [];
                    $userIds = explode(',', $v['anchor']['user_ids']);
                    foreach ($userIds as $uid){
                        $d[] = $anchorList[$uid];
                    }

                    $v['lives'] = $d;
                }
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
    public static function getMatchPLayingList($input)
    {
        $matchList = json_decode(Redis::get(RedisKeyMap::getFootballMatchPlayingListV2($input['page'])));
        if (!empty($matchList)) return $matchList;
            $showStartTime = strtotime("-1 day");
            $matchList = self::with(['homeTeam', 'awayTeam', 'league'])
                ->whereIn( 'status_id', self::$playingStatusMap)
                ->where('match_time','>=',  $showStartTime)
                ->orderBy('match_time', 'ASC')
                ->orderBy('status_id', 'ASC')
                ->offset(($input['page'] - 1) * 10)
                ->limit(10)
                ->get();

        if (count($matchList) > 0) {
            $data = [];

            $matchList = Helper::array_index_array($matchList, 'match_date');
            foreach ($matchList as $date => $list) {
                $data[] = [
                    'week_day_str' => Helper::getWeekDayStr($date),
                    'date' => $date,
                    'list' => $list,
                ];
            }
            Redis::setex(RedisKeyMap::getFootballMatchPlayingListV2($input['page']), config('params.cache.ttl'), $matchList);
            return $data;
        }
        return [];
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

        $query = self::with(['homeTeam', 'awayTeam', 'league','anchor'])
            ->whereIn( 'status_id', self::$playingStatusMap)
            ->where('match_time','>=',  $showStartTime);
        $matchList = [];
        $total = (clone $query)->count();
        $list = $query
            ->orderBy('match_time', 'ASC')
            ->orderBy('status_id', 'ASC')
            ->offset(($input['page'] - 1) * 10)
            ->limit(10)
            ->get();

        $matchList['total'] = $total;
        $matchList['list'] = [];
        if (count($list) > 0) {
            $anchorList = CmfAnchorAuth::getAllAnchor();
            foreach ($list as &$v){
                if($v['anchor'] && $v['anchor']['user_ids'] != ''){
                    $d = [];
                    $userIds = explode(',', $v['anchor']['user_ids']);
                    foreach ($userIds as $uid){
                        $d[] = $anchorList[$uid];
                    }

                    $v['lives'] = $d;
                }
            }

            $matchList['list'] = $list;
            Redis::setex(RedisKeyMap::getFootballMatchPlayingListV3($input['page']), config('params.cache.ttl'), $matchList);
        }
        return $matchList;
    }

    /**
     * @param $input
     * @return SportsFootballMatchModel[]|array|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|mixed
     */
    public static function getMatchListByDate($input)
    {
        $matchList = json_decode(Redis::get(RedisKeyMap::getFootballMatchListByDateV2($input['page'], $input['date'], $input['action'])));
        if (!empty($matchList)) return $matchList;
        $model = self::with(['homeTeam', 'awayTeam', 'league'])
            ->whereRaw("FROM_UNIXTIME(match_time, '%Y-%m-%d') = '{$input['date']}'");
        if (isset($input['action'])) {
            switch ($input['action']) {
                case 1:     //赛程
                    $model->whereIn('status_id', self::$notStartStatusMap);
                    break;
                case 2:     //赛果
                    $model->where(['status_id' => self::STATUS_FINISH]);
                    break;
            }
        }
        $matchList = $model->orderBy('status_id', 'ASC')
            ->orderBy('match_time', 'ASC')
            ->orderBy('id', 'ASC')
            ->offset(($input['page'] - 1) * 10)
            ->limit(10)
            ->get();
        if (count($matchList) > 0) {
            Redis::setex(RedisKeyMap::getFootballMatchListByDateV2($input['page'], $input['date'], $input['action']), config('params.cache.ttl'), json_encode($matchList));
            return $matchList;
        }
        return [];
    }



    public static function getMatchListByDateV3($input)
    {
        $matchList = json_decode(Redis::get(RedisKeyMap::getFootballMatchListByDateV3($input['page'], $input['date'], $input['action'])));
        if (!empty($matchList)) return $matchList;
        $model = self::with(['homeTeam', 'awayTeam', 'league','anchor'])
            ->whereRaw("FROM_UNIXTIME(match_time, '%Y-%m-%d') = '{$input['date']}'");
        if (isset($input['action'])) {
            switch ($input['action']) {
                case 1:     //赛程
                    $model->whereIn('status_id', self::$notStartStatusMap);
                    break;
                case 2:     //赛果
                    $model->where(['status_id' => self::STATUS_FINISH]);
                    break;
            }
        }

        $matchList = [];
        $total =  (clone $model)->count();
        $matchList['total'] = $total;

        $list = $model->orderBy('status_id', 'ASC')
            ->orderBy('match_time', 'ASC')
            ->orderBy('id', 'ASC')
            ->offset(($input['page'] - 1) * 10)
            ->limit(10)
            ->get();
        $matchList['list'] = [];
        if (count($list) > 0) {

            $anchorList = CmfAnchorAuth::getAllAnchor();
            foreach ($list as &$v){
                if($v['anchor'] && $v['anchor']['user_ids'] != ''){
                    $d = [];
                    $userIds = explode(',', $v['anchor']['user_ids']);
                    foreach ($userIds as $uid){
                        $d[] = $anchorList[$uid];
                    }

                    $v['lives'] = $d;
                }
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
    public static function getMatch($input)
    {
        $match = json_decode(Redis::get(RedisKeyMap::getFootballMatchV2($input['matchId'])));
        if (!empty($match)) return $match;
        $match = self::with(['homeTeam', 'awayTeam', 'league'])->where(['id' => $input['matchId']])->first();
        if (!empty($match) > 0) {
            Redis::setex(RedisKeyMap::getFootballMatchV2($input['matchId']), config('params.cache.ttl'), json_encode($match));
            return $match;
        }
        return NULL;
    }
}
