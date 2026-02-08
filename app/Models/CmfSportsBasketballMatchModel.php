<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Helpers\RedisKeyMap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CmfSportsBasketballMatchModel extends BaseModel
{
    protected $table = 'cmf_sports_basketball_match';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['id', 'created_at', 'updated_at'];
    public $appends = ['state_str', 'is_playing'];

    //比赛状态:  0：比赛一场，1：未开赛，2：第一节，3：第一节完，4：第二节，5：第二节完，6：第三节，7：第三节完，
    //8：第四节，9：加时，10：完场，11：中断，12：取消，13：延期，14：腰斩，15：待定
    CONST STATE_MATCH = 0;
    CONST STATE_NOT_START = 1;
    CONST STATE_FIRST = 2;
    CONST STATE_FIRST_FINISH = 3;
    CONST STATE_SECOND = 4;
    CONST STATE_SECOND_FINISH = 5;
    CONST STATE_THREE = 6;
    CONST STATE_THREE_FINISH = 7;
    CONST STATE_FOUR = 8;
    CONST STATE_OVERTIME = 9;
    CONST STATE_FINISH = 10;
    CONST STATE_INTERRUPT = 11;
    CONST STATE_CANCEL = 12;
    CONST STATE_DELAY = 13;
    CONST STATE_HALF_CUT = 14;
    CONST STATE_DETERMINE = 15;


    CONST SELF_STATE_MATCH = 0;
    CONST SELF_STATE_FIRST = 10;
    CONST SELF_STATE_FIRST_FINISH = 20;
    CONST SELF_STATE_SECOND = 30;
    CONST SELF_STATE_SECOND_FINISH = 40;
    CONST SELF_STATE_THREE = 50;
    CONST SELF_STATE_THREE_FINISH = 60;
    CONST SELF_STATE_FOUR = 70;
    CONST SELF_STATE_OVERTIME = 80;
    CONST SELF_STATE_NOT_START = 90;
    CONST SELF_STATE_DETERMINE = 100;
    CONST SELF_STATE_DELAY = 110;
    CONST SELF_STATE_INTERRUPT = 120;
    CONST SELF_STATE_HALF_CUT = 130;
    CONST SELF_STATE_CANCEL = 140;
    CONST SELF_STATE_FINISH = 150;

    public static $stateToSelfMap = [
        self::STATE_MATCH => 0,
        self::STATE_FIRST => 10,
        self::STATE_FIRST_FINISH => 20,
        self::STATE_SECOND => 30,
        self::STATE_SECOND_FINISH => 40,
        self::STATE_THREE => 50,
        self::STATE_THREE_FINISH => 60,
        self::STATE_FOUR => 70,
        self::STATE_OVERTIME => 80,
        self::STATE_NOT_START => 90,
        self::STATE_DETERMINE => 100,
        self::STATE_DELAY => 110,
        self::STATE_INTERRUPT => 120,
        self::STATE_HALF_CUT => 130,
        self::STATE_CANCEL => 140,
        self::STATE_FINISH => 150,
    ];

    public static $stateMap = [
        self::STATE_MATCH => '未开赛',
        self::STATE_NOT_START => '未开赛',
        self::STATE_FIRST => '第一节',
        self::STATE_FIRST_FINISH => '第一节完',
        self::STATE_SECOND => '第二节',
        self::STATE_SECOND_FINISH => '第二节完',
        self::STATE_THREE => '第三节',
        self::STATE_THREE_FINISH => '第三节完',
        self::STATE_FOUR => '第四节',
        self::STATE_OVERTIME => '加时',
        self::STATE_FINISH => '完场',
        self::STATE_INTERRUPT => '中断',
        self::STATE_CANCEL => '取消',
        self::STATE_DELAY => '延期',
        self::STATE_HALF_CUT => '腰斩',
        self::STATE_DETERMINE => '待定',
    ];

    public static $playingStates = [
        self::STATE_FIRST,
        self::STATE_FIRST_FINISH,
        self::STATE_SECOND,
        self::STATE_SECOND_FINISH,
        self::STATE_THREE,
        self::STATE_THREE_FINISH,
        self::STATE_FOUR,
        self::STATE_OVERTIME,
    ];

    public static $playingSelfStates = [
        self::SELF_STATE_FIRST,
        self::SELF_STATE_FIRST_FINISH,
        self::SELF_STATE_SECOND,
        self::SELF_STATE_SECOND_FINISH,
        self::SELF_STATE_THREE,
        self::SELF_STATE_THREE_FINISH,
        self::SELF_STATE_FOUR,
        self::SELF_STATE_OVERTIME,
    ];

    public static $selfNotStartedStatesMap = [
        self::SELF_STATE_MATCH,
        self::SELF_STATE_NOT_START,
        self::SELF_STATE_DETERMINE,
        self::SELF_STATE_DELAY,
        self::SELF_STATE_INTERRUPT,
        self::SELF_STATE_HALF_CUT,
        self::SELF_STATE_CANCEL,
    ];

    public static function getPlayingSelfStats()
    {
        $selfStates = [];
        foreach (self::$playingStates as $playingState) {
            $selfStates[] = self::$stateToSelfMap[$playingState];
        }
        return $selfStates;
    }

    public function homeTeam()
    {
        return $this->hasOne(CmfSportsBasketballTeamModel::class, 'teamId', 'homeId');
    }

    public function awayTeam()
    {
        return $this->hasOne(CmfSportsBasketballTeamModel::class, 'teamId', 'awayId');
    }

    public function league()
    {
        return $this->hasOne(CmfSportsBasketballLeagueModel::class, 'leagueId', 'leagueId');
    }

    public function getStateStrAttribute()
    {
        return self::$stateMap[array_search($this->state, self::$stateToSelfMap)];
    }

    public function getIsPlayingAttribute()
    {
        //进行中
        if (in_array($this->state, self::$playingSelfStates)) return 2;
        //已完场
        if (self::SELF_STATE_FINISH == $this->state) return 3;
        //未开赛
        return 1;
    }

    public function getLiveUrlAttribute($value)
    {
        return self::getLiveUrl($value);
    }

    public static function insertOrUpdate($match, $isSaveOther = false)
    {
        $flag = false;
        DB::transaction(function () use ($match, $isSaveOther, &$flag) {
            if ($isSaveOther) {
                //主队
                CmfSportsBasketballTeamModel::insertOrUpdate([
                    'teamId' => $match->homeId,
                    'rank' => $match->homeRank,
                    'nameCn' => $match->homeNameCn,
                    'nameCnShort' => $match->homeNameCnShort,
                    'nameTrad' => $match->homeNameTrad,
                    'nameTradShort' => $match->homeNameTradShort,
                    'nameEn' => $match->homeNameEn,
                    'nameEnShort' => $match->homeNameEnShort,
                    'logo' => $match->homeTeamLogo ?? '',
                ]);
                //客队
                CmfSportsBasketballTeamModel::insertOrUpdate([
                    'teamId' => $match->awayId,
                    'rank' => $match->awayRank,
                    'nameCn' => $match->awayNameCn,
                    'nameCnShort' => $match->awayNameCnShort,
                    'nameTrad' => $match->awayNameTrad,
                    'nameTradShort' => $match->awayNameTradShort,
                    'nameEn' => $match->awayNameEn,
                    'nameEnShort' => $match->awayNameEnShort,
                    'logo' => $match->awayTeamLogo ?? '',
                ]);
                //联赛
                CmfSportsBasketballLeagueModel::insertOrUpdate([
                    'leagueId' => $match->leagueId,
    //            'leagueNameCn' => $match->leagueNameCn,
    //            'leagueNameTrad' => $match->leagueNameTrad,
    //            'leagueNameEn' => $match->leagueNameEn,
                    'leagueNameCnShort' => $match->leagueNameCnShort,
                    'leagueNameTradShort' => $match->leagueNameTradShort,
                    'leagueNameEnShort' => $match->leagueNameEnShort,
                ]);
            }
            //如果没有状态异常，则不保存
            if (!isset(self::$stateToSelfMap[$match->state])) return false;

            $model = self::where(['matchId' => $match->matchId])->first();
            if (empty($model)) $model = new self();

            $model->matchId = $match->matchId;
            if (isset($match->leagueId)) $model->leagueId = $match->leagueId;
            if (isset($match->homeId)) $model->homeId = $match->homeId;
            if (isset($match->awayId)) $model->awayId = $match->awayId;
            if (isset($match->state)) $model->state = self::$stateToSelfMap[$match->state];
            if (isset($match->matchStartTime)) $model->matchStartTime = $match->matchStartTime;
            if (isset($match->letgoalHomeOdds)) $model->letgoalHomeOdds = $match->letgoalHomeOdds;
            if (isset($match->letgoalGoal)) $model->letgoalGoal = $match->letgoalGoal;
            if (isset($match->letgoalAwayOdds)) $model->letgoalAwayOdds = $match->letgoalAwayOdds;
            if (isset($match->letgoalIsEntertained)) $model->letgoalIsEntertained = $match->letgoalIsEntertained;
            if (isset($match->totalScoreHomeOdds)) $model->totalScoreHomeOdds = $match->totalScoreHomeOdds;
            if (isset($match->totalScoreGoal)) $model->totalScoreGoal = $match->totalScoreGoal;
            if (isset($match->totalScoreAwayOdds)) $model->totalScoreAwayOdds = $match->totalScoreAwayOdds;
            if (isset($match->totalScoreIsEntertained)) $model->totalScoreIsEntertained = $match->totalScoreIsEntertained;
            if (isset($match->isSources)) $model->isSources = $match->isSources;
            if (isset($match->isAnimation)) $model->isAnimation = $match->isAnimation;
            if (isset($match->isLive)) $model->isLive = $match->isLive;

            if (isset($match->homeScore)) $model->homeScore = $match->homeScore;
            if (isset($match->homeNode1Score)) $model->homeNode1Score = $match->homeNode1Score;
            if (isset($match->homeNode2Score)) $model->homeNode2Score = $match->homeNode2Score;
            if (isset($match->homeNode3Score)) $model->homeNode3Score = $match->homeNode3Score;
            if (isset($match->homeNode4Score)) $model->homeNode4Score = $match->homeNode4Score;
            if (isset($match->homeNode5Score)) $model->homeNode5Score = $match->homeNode5Score;
            if (isset($match->awayScore)) $model->awayScore = $match->awayScore;
            if (isset($match->awayNode1Score)) $model->awayNode1Score = $match->awayNode1Score;
            if (isset($match->awayNode2Score)) $model->awayNode2Score = $match->awayNode2Score;
            if (isset($match->awayNode3Score)) $model->awayNode3Score = $match->awayNode3Score;
            if (isset($match->awayNode4Score)) $model->awayNode4Score = $match->awayNode4Score;
            if (isset($match->awayNode5Score)) $model->awayNode5Score = $match->awayNode5Score;
            if (isset($match->nodeCount)) $model->nodeCount = $match->nodeCount;

            //补充
            if (isset($match->matchStartTime)) $model->match_date = date('Y-m-d', $match->matchStartTime);
            $flag = $model->save();
        });
        return $flag;
    }

    public static function timelyListUpdate($match)
    {
        $flag1 = $flag2 = false;
        DB::transaction(function () use ($match, &$flag1, &$flag2) {
            $flag1 = self::where(['matchId' => $match->matchId])->update([
                'state' => self::$stateToSelfMap[$match->state],
                'homeScore' => $match->homeScore,
                'awayScore' => $match->awayScore,
            ]);
            if ($flag1) {
                $flag2 = CmfLolMatchPushModel::insertOrUpdate([
                    'match_id' => $match->matchId,
                    'status' => CmfLolMatchPushModel::STATUS_PUSH_NOT,
                    'addtime' => time(),
                    'type' => CmfLolMatchPushModel::TYPE_BASKETBALL,
                ]);
            }
        });
        return $flag1 && $flag2;
    }

    /**
     * 正在进行的比赛Id
     */
    public static function getPlayingMatchIds()
    {
        return self::select(['matchId'])
            ->where(['state' => self::$playingStates])
            ->pluck('matchId');
    }

    /**
     * 正在进行的比赛Id
     */
    public static function getRecentMatchIds()
    {
        $startTime = strtotime("-15 day");
        $endTime = strtotime("+15 day");
        return self::select(['matchId'])
            ->where('matchStartTime', '>=', $startTime)
            ->where('matchStartTime', '<=', $endTime)
            ->pluck('matchId');
    }

    public static function getMatchAllList($input)
    {
        $matchList = json_decode(Redis::get(RedisKeyMap::getFootballMatchAllList($input['page'])));
        if (!empty($matchList)) return $matchList;
        $endDate = date('Y-m-d', strtotime("+15 day"));
        $playingSelfStates = implode(',', self::$playingSelfStates);
        $matchList = self::with(['homeTeam', 'awayTeam', 'league'])
            ->where('match_date', '>=', date("Y-m-d"))
            ->where('match_date', '<=', $endDate)
            ->whereRaw("IF(state IN({$playingSelfStates}), live_url!='', 1=1)")
            ->orderBy('match_date', 'ASC')
            ->orderBy('state', 'ASC')
            ->orderBy('matchStartTime', 'ASC')
            ->offset(($input['page'] - 1) * 10)
            ->limit(10)
            ->get();
        if (count($matchList) > 0) {
            $data = [];
            $matchList = Helper::array_index_array($matchList, 'match_date');
            foreach ($matchList as $date => $list) {

                $data[] = [
                    'week_day_str' =>  Helper::getWeekDayStr($date),
                    'date' => $date,
                    'list' => $list,
                ];
            }
            Redis::setex(RedisKeyMap::getBasketballMatchAllList($input['page']), config('params.cache.ttl'), $matchList);
            return $data;
        }
        return [];
    }

    public static function getMatchPLayingList($input)
    {
        $matchList = json_decode(Redis::get(RedisKeyMap::getBasketballMatchPLayingList($input['page'])));
        if (!empty($matchList)) return $matchList;

        $showStartTime = strtotime("-1 day");

        $matchList = self::with(['homeTeam', 'awayTeam', 'league'])
            ->whereIn('state', self::$playingSelfStates)
            ->whereRaw('live_url!=""')
            ->where('matchStartTime', '>=', $showStartTime)
            ->orderBy('state', 'ASC')
            ->orderBy('matchStartTime', 'DESC')
            ->offset(($input['page'] - 1) * 10)
            ->limit(10)
            ->get();
        if (count($matchList) > 0) {
            $data = [];
            $matchList = Helper::array_index_array($matchList, 'match_date');
            foreach ($matchList as $date => $list) {
                $data[] = [
                    'week_day_str' =>  Helper::getWeekDayStr($date),
                    'date' => $date,
                    'list' => $list,
                ];
            }
            Redis::setex(RedisKeyMap::getBasketballMatchPLayingList($input['page']), config('params.cache.ttl'), $matchList);
            return $data;
        }
        return [];
    }

    public static function getMatchListByDate($input)
    {
        $matchList = json_decode(Redis::get(RedisKeyMap::getBasketballMatchListByDate($input['page'], $input['date'], $input['action'])));
        if (!empty($matchList)) return $matchList;
        $model = self::with(['homeTeam', 'awayTeam', 'league'])
            ->where(['match_date' => $input['date']]);
        if (isset($input['action'])) {
            switch ($input['action']) {
                case 1:     //赛程
                    $model->whereIn('state', self::$selfNotStartedStatesMap);
                    break;
                case 2:     //赛果
                    $model->where(['state' => self::SELF_STATE_FINISH]);
                    break;
            }
        }
        $matchList = $model->orderBy('state', 'ASC')
            ->orderBy('matchStartTime', 'ASC')
            ->orderBy('id', 'ASC')
            ->offset(($input['page'] - 1) * 10)
            ->limit(10)
            ->get();
        if (count($matchList) > 0) {
            Redis::setex(RedisKeyMap::getBasketballMatchListByDate($input['page'], $input['date'], $input['action']), config('params.cache.ttl'), json_encode($matchList));
            return $matchList;
        }
        return [];
    }


    public static function updateLiveUrl($live)
    {
        if ($live->playStatus != 2) return false;
        $homeTeam = CmfSportsBasketballTeamModel::where(['nameCn' => $live->homeQtCnName])->first();
        $awayTeam = CmfSportsBasketballTeamModel::where(['nameCn' => $live->awayQtCnName])->first();
        if (empty($homeTeam) && empty($awayTeam)) return false;
        $startTime = $live->startTime / 1000;
        if (empty($homeTeam)) {
            $map['awayId'] = $awayTeam->teamId;
        } elseif (empty($awayTeam)) {
            $map['homeId'] = $homeTeam->teamId;
        } else {
            $map['awayId'] = $awayTeam->teamId;
            $map['homeId'] = $homeTeam->teamId;
        }
        $map['matchStartTime'] = $startTime;
        $match = self::where($map)->first();
        if (self::where($map)->exists()) {
            $url = $match->live_url ? $match->live_url : [];
            foreach ($url as $key=>$value){
                $value = self::decrypt($value);
                $path = parse_url($value);
                $path = explode(".",$path['path']);
                $url[$key] = $path[0];
            }
            $thisUrls = parse_url($live->url);
            $thisUrl = $thisUrls['path'];
            if (!in_array($thisUrl, $url)) {
                $url [] = $thisUrl;
            }
            $url = json_encode($url);
            $update['live_url'] = $url;
            self::where(['matchId' => $match->matchId])->update($update);
            return 1;
        } else {
            return false;
        }
    }

    public static function getMatch($input)
    {
        $match = json_decode(Redis::get(RedisKeyMap::getBasketballMatch($input['matchId'])));
        if (!empty($match)) return $match;
        $match = self::with(['homeTeam', 'awayTeam', 'league'])->where(['matchId' => $input['matchId']])->first();
        if (!empty($match) > 0) {
            Redis::setex(RedisKeyMap::getBasketballMatch($input['matchId']), config('params.cache.ttl'), json_encode($match));
            return $match;
        }
        return NULL;
    }

    public static function getLiveUrl($value)
    {
        if(!$value) return[];
        $urls = json_decode($value,true);
        $prefix = config('params.domain.live.sports');
        $suffix = ".flv";
        foreach ($urls as $key=>$value){
            $urls[$key] = self::encrypt($prefix.$value.$suffix);
        }
        return $urls;
    }

    /**
     * Encrypt
     *
     * @param string $data Input data
     * @return string
     */
    /**
     * Encrypt
     *
     * @param string $data Input data
     * @return string
     */
    public static function encrypt($data) {

        $key = hash('MD5', "123123", true);
        $iv = hash('MD5', "123123", true);

        $data = openssl_encrypt($data, 'AES-128-CBC', $key, OPENSSL_RAW_DATA,$iv);
        return base64_encode($data);
    }

    /**
     * Decrypt
     *
     * @param string $data Encrypted data
     * @return string
     */
    public static function decrypt($str) {
        $key = hash('MD5', "123123", true);
        $iv = hash('MD5', "123123", true);
        $decrypted = openssl_decrypt(base64_decode($str), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return $decrypted;
    }



}
