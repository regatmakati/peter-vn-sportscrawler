<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Helpers\RedisKeyMap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CmfSportsFootballMatchModel extends BaseModel
{
    protected $table = 'cmf_sports_football_match';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['id', 'created_at', 'updated_at'];
    public $appends = ['state_str', 'is_playing'];
    //比赛状态:-14:推迟，-13:中断，-12:腰斩，-11:待定，-10:取消，-1.完场，0:未开始，1:上半场，2:中场，3:下半场，4:加时，5:点球
    CONST STATE_DELAY = -14;
    CONST STATE_INTERRUPT = -13;
    CONST STATE_HALF_CUT = -12;
    CONST STATE_DETERMINE = -11;
    CONST STATE_CANCEL = -10;
    CONST STATE_FINISH = -1;
    CONST STATE_NOT_START = 0;
    CONST STATE_FIRST_HALF = 1;
    CONST STATE_MIDFIELD = 2;
    CONST STATE_SECOND_HALF = 3;
    CONST STATE_OVERTIME = 4;
    CONST STATE_KICK = 5;

    CONST SELF_STATE_FIRST_HALF = 10;
    CONST SELF_STATE_MIDFIELD = 20;
    CONST SELF_STATE_SECOND_HALF = 30;
    CONST SELF_STATE_OVERTIME = 40;
    CONST SELF_STATE_KICK = 50;
    CONST SELF_STATE_NOT_START = 60;
    CONST SELF_STATE_DETERMINE = 70;
    CONST SELF_STATE_DELAY = 80;
    CONST SELF_STATE_INTERRUPT = 90;
    CONST SELF_STATE_HALF_CUT = 100;
    CONST SELF_STATE_CANCEL = 110;
    CONST SELF_STATE_FINISH = 120;

    public static $stateToSelfMap = [
        self::STATE_FIRST_HALF => 10,
        self::STATE_MIDFIELD => 20,
        self::STATE_SECOND_HALF => 30,
        self::STATE_OVERTIME => 40,
        self::STATE_KICK => 50,
        self::STATE_NOT_START => 60,
        self::STATE_DETERMINE => 70,
        self::STATE_DELAY => 80,
        self::STATE_INTERRUPT => 90,
        self::STATE_HALF_CUT => 100,
        self::STATE_CANCEL => 110,
        self::STATE_FINISH => 120,
    ];

    public static $stateMap = [
        self::STATE_DELAY => '推迟',
        self::STATE_INTERRUPT => '中断',
        self::STATE_HALF_CUT => '腰斩',
        self::STATE_DETERMINE => '待定',
        self::STATE_CANCEL => '取消',
        self::STATE_FINISH => '完场',
        self::STATE_NOT_START => '未开赛',
        self::STATE_FIRST_HALF => '上半场',
        self::STATE_MIDFIELD => '中场',
        self::STATE_SECOND_HALF => '下半场',
        self::STATE_OVERTIME => '加时',
        self::STATE_KICK => '点球',
    ];

    public static $playingStates = [
        self::STATE_FIRST_HALF,
        self::STATE_MIDFIELD,
        self::STATE_SECOND_HALF,
        self::STATE_OVERTIME,
        self::STATE_KICK,
    ];

    public static $playingSelfStates = [
        self::SELF_STATE_FIRST_HALF,
        self::SELF_STATE_MIDFIELD,
        self::SELF_STATE_SECOND_HALF,
        self::SELF_STATE_OVERTIME,
        self::SELF_STATE_KICK,
    ];

    public static $selfNotStartedStatesMap = [
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
        return $this->hasOne(CmfSportsFootballTeamModel::class, 'teamId', 'homeId');
    }

    public function awayTeam()
    {
        return $this->hasOne(CmfSportsFootballTeamModel::class, 'teamId', 'awayId');
    }

    public function league()
    {
        return $this->hasOne(CmfSportsFootballLeagueModel::class, 'leagueId', 'leagueId');
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

    /**
     * @param $match
     * @param bool $isSaveOther
     * @return mixed
     */
    public static function insertOrUpdate($match, $isSaveOther = false)
    {
        $flag = false;
        DB::transaction(function () use ($match, $isSaveOther, &$flag) {
            if ($isSaveOther) {
                //主队
                CmfSportsFootballTeamModel::insertOrUpdate([
                    'teamId' => $match->homeId,
                    'rank' => $match->homeRank,
                    'nameCn' => $match->homeNameCn,
                    'nameTrad' => $match->homeNameTrad,
                    'nameEn' => $match->homeNameEn,
                    'logo' => $match->homeTeamLogo,
                ]);
                //客队
                CmfSportsFootballTeamModel::insertOrUpdate([
                    'teamId' => $match->awayId,
                    'rank' => $match->awayRank,
                    'nameCn' => $match->awayNameCn,
                    'nameTrad' => $match->awayNameTrad,
                    'nameEn' => $match->awayNameEn,
                    'logo' => $match->awayTeamLogo,
                ]);
                //联赛
                CmfSportsFootballLeagueModel::insertOrUpdate([
                    'leagueId' => $match->leagueId,
                    'leagueNameCn' => $match->leagueNameCn,
                    'leagueNameTrad' => $match->leagueNameTrad,
                    'leagueNameEn' => $match->leagueNameEn,
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
            $model->leagueId = $match->leagueId;
            $model->matchStartTime = $match->matchStartTime;
            $model->state = self::$stateToSelfMap[$match->state];
            $model->isNeutral = $match->isNeutral;
            $model->locationCn = $match->locationCn;
            $model->locationEn = $match->locationEn;
            $model->homeId = $match->homeId;
            $model->awayId = $match->awayId;
            $model->letgoalHomeOdds = $match->letgoalHomeOdds;
            $model->letgoalGoal = $match->letgoalGoal;
            $model->letgoalAwayOdds = $match->letgoalAwayOdds;
            $model->letgoalIsEntertained = $match->letgoalIsEntertained;
            $model->europeHomeOdds = $match->europeHomeOdds;
            $model->europeFlatOdds = $match->europeFlatOdds;
            $model->europeAwayOdds = $match->europeAwayOdds;
            $model->europeIsEntertained = $match->europeIsEntertained;
            $model->totalScoreHomeOdds = $match->totalScoreHomeOdds;
            $model->totalScoreGoal = $match->totalScoreGoal;
            $model->totalScoreAwayOdds = $match->totalScoreAwayOdds;
            $model->totalScoreIsEntertained = $match->totalScoreIsEntertained;
            $model->hasSources = $match->hasSources;
            $model->hasAnimation = $match->hasAnimation;
            $model->hasLive = $match->hasLive;
            //赛果字段
            $model->homeCornerNum = $match->homeCornerNum ?? NULL;
            $model->homeHalfScore = $match->homeHalfScore ?? NULL;
            $model->homeScore = $match->homeScore ?? NULL;
            $model->awayCornerNum = $match->awayCornerNum ?? NULL;
            $model->awayHalfScore = $match->awayHalfScore ?? NULL;
            $model->awayScore = $match->awayScore ?? NULL;
            $model->extraFirstKick = $match->extraFirstKick ?? NULL;
            $model->extraNormalScore = $match->extraNormalScore ?? NULL;
            $model->extraNormalTime = $match->extraNormalTime ?? NULL;
            $model->extraPenaltyKickScore = $match->extraPenaltyKickScore ?? NULL;
            $model->extraScore = $match->extraScore ?? NULL;
            $model->extraTwoLegsScore = $match->extraTwoLegsScore ?? NULL;
            $model->extraType = $match->extraType ?? NULL;
            $model->extraWin = $match->extraWin ?? NULL;

            //即时
            $model->weatherEn = $match->weatherEn ?? NULL;
            $model->weatherCn = $match->weatherCn ?? NULL;
            $model->weatherIcon = $match->weatherIcon ?? NULL;
            $model->temperature = $match->temperature ?? NULL;

            //补充
            $model->match_date = date('Y-m-d', $match->matchStartTime);

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
                    'type' => CmfLolMatchPushModel::TYPE_FOOTBALL,
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
            Redis::setex(RedisKeyMap::getFootballMatchAllList($input['page']), config('params.cache.ttl'), $matchList);
            return $data;
        }
        return [];
    }


    public static function getMatchPLayingList($input)
    {
        $matchList = json_decode(Redis::get(RedisKeyMap::getFootballMatchPLayingList($input['page'])));
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
            Redis::setex(RedisKeyMap::getFootballMatchPLayingList($input['page']), config('params.cache.ttl'), $matchList);
            return $data;
        }
        return [];
    }

    public static function getMatchListByDate($input)
    {
        $matchList = json_decode(Redis::get(RedisKeyMap::getFootballMatchListByDate($input['page'], $input['date'], $input['action'])));
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
            Redis::setex(RedisKeyMap::getFootballMatchListByDate($input['page'], $input['date'], $input['action']), config('params.cache.ttl'), json_encode($matchList));
            return $matchList;
        }
        return [];
    }

    public static function updateLiveUrl($live)
    {
        //断开不可连
        if ($live->playStatus != 2) return false;
        $league = CmfSportsFootballLeagueModel::where(['leagueNameCnShort' => $live->eventQtCnName])->orWhere(['leagueNameEnShort' => $live->eventQtEnName])->first();
        if (empty($league)) return false;
        $homeTeam = CmfSportsFootballTeamModel::where(['nameEn' => $live->homeQtEnName])->orWhere(['nameCn' => $live->homeQtCnName])->first();
        if (empty($homeTeam)) return false;
        $awayTeam = CmfSportsFootballTeamModel::where(['nameEn' => $live->awayQtEnName])->orWhere(['nameCn' => $live->awayQtCnName])->first();
        if (empty($awayTeam)) return false;
        $startTime = $live->startTime / 1000;
        $match = self::where(['leagueId' => $league->leagueId, 'homeId' => $homeTeam->teamId, 'awayId' => $awayTeam->teamId, 'matchStartTime' => $startTime])->first();
        if (self::where(['leagueId' => $league->leagueId, 'homeId' => $homeTeam->teamId, 'awayId' => $awayTeam->teamId, 'matchStartTime' => $startTime])->exists()) {
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
        $match = json_decode(Redis::get(RedisKeyMap::getFootballMatch($input['matchId'])));
        if (!empty($match)) return $match;
        $match = self::with(['homeTeam', 'awayTeam', 'league'])->where(['matchId' => $input['matchId']])->first();
        if (!empty($match) > 0) {
            Redis::setex(RedisKeyMap::getFootballMatch($input['matchId']), config('params.cache.ttl'), json_encode($match));
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
