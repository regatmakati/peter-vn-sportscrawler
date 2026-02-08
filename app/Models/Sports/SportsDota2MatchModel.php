<?php

namespace App\Models\Sports;

use App\Helpers\Helper;
use App\Helpers\RedisKeyMap;
use App\Models\BaseModel;
use Illuminate\Support\Facades\Redis;

class SportsDota2MatchModel extends BaseModel
{
    protected $connection = 'mysql_sports';
    protected $table = 'sports_dota2_match';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at', 'updated_time'];
    protected $appends = ['match_date'];

    CONST STATUS_ABNORMAL = 0;
    CONST STATUS_NOT_START = 1;
    CONST STATUS_PLAYING = 2;
    CONST STATUS_FINISH = 3;
    CONST STATUS_INTERRUPT = 11;
    CONST STATUS_CANCEL = 12;
    CONST STATUS_DELAY = 13;
    CONST STATUS_HALF_CUT = 14;
    CONST STATUS_DETERMINE = 15;

    public static $statusMap = [
        self::STATUS_ABNORMAL => '比赛异常',
        self::STATUS_NOT_START => '未开赛',
        self::STATUS_PLAYING => '进行中',
        self::STATUS_FINISH => '完场',
        self::STATUS_INTERRUPT => '中断',
        self::STATUS_CANCEL => '取消',
        self::STATUS_DELAY => '延期',
        self::STATUS_HALF_CUT => '腰斩',
        self::STATUS_DETERMINE => '待定',
    ];


    public function tournament()
    {
        return $this->hasOne(SportsDota2TournamentModel::class, 'id', 'tournament_id');
    }
    public function homeTeam()
    {
        return $this->hasOne(SportsDota2TeamModel::class, 'id', 'home_id');
    }
    public function awayTeam()
    {
        return $this->hasOne(SportsDota2TeamModel::class, 'id', 'away_id');
    }

    public function matchSingle()
    {
        return $this->hasOne(SportsDota2MatchSingleModel::class, 'match_id', 'id');
    }

    public function getMatchDateAttribute()
    {
        return date("Y-m-d", $this->match_time);
    }

    public function getAnimationsAttribute($value)
    {
        return json_decode($value);
    }


    public static function insertOrUpdate($match)
    {
        $model = self::where(['id' => $match->id])->first();
        if (empty($model)) $model = new self();
        $model->id = $match->id;
        $model->box = $match->box;
        $model->tournament_id = $match->tournament->id;
        $model->stage_id = $match->stage->id;
        $model->home_id = $match->home->id;
        $model->home_score = $match->home->score;
        $model->away_id = $match->away->id;
        $model->away_score = $match->away->score;
        $model->status_id = $match->status_id;
        $model->match_time = $match->match_time;
        $model->description = $match->description;
        $model->updated_time = $match->updated_at;
        return $model->save();
    }

    public static function getBeforeAfter15DaysPks()
    {
        return self::whereBetween('match_time', [strtotime("-15 day"), strtotime("+15 day")])
            ->pluck('id');
    }

    public static function getMatchList($input)
    {
        $data = json_decode(Redis::get(RedisKeyMap::getDota2MatchList($input['page'], $input['action'])));
        if (!empty($data)) return $data;

        $t1 = 'sports_dota2_match';
        $t2 = 'sports_dota2_match_single';
        $model = self::with(['tournament' => function ($query) {
            $query->select('id', 'name_zh', 'name_en', 'abbr_zh', 'abbr_en', 'logo');
        }, 'homeTeam' => function ($query) {
            $query->select('id', 'name_zh', 'name_en', 'logo');
        }, 'awayTeam' => function ($query) {
            $query->select('id', 'name_zh', 'name_en', 'logo');
        },
            'matchSingle' => function ($query) {
                $query->where(['status_id' => SportsDota2MatchSingleModel::STATUS_PLAYING, 'is_deleted' => self::DELETED_NO]);
            }
        ])->leftJoin($t2, "{$t2}.match_id", '=', "{$t1}.id");
        switch ($input['action']) {
            case 'all':         //全部列表
                $model->whereIn("{$t1}.status_id", [self::STATUS_NOT_START, self::STATUS_PLAYING]);
                break;
            case 'playing':     //进行中列表
                $model->where(["{$t1}.status_id" => self::STATUS_PLAYING]);
                break;
            case 'schedule':    //赛程列表
                $dateTimestampsArr = Helper::getDateTimestamps($input['date']);
                $model->where(["{$t1}.status_id" => self::STATUS_NOT_START])->whereBetween("{$t1}.match_time", [$dateTimestampsArr['date_start_time'], $dateTimestampsArr['date_end_time']]);
                break;
            case 'result':      //赛果列表
                $dateTimestampsArr = Helper::getDateTimestamps($input['date']);
                $model->where(["{$t1}.status_id" => self::STATUS_FINISH])->whereBetween("{$t1}.match_time", [$dateTimestampsArr['date_start_time'], $dateTimestampsArr['date_end_time']]);
                break;
        }


        $matchList = $model->whereBetween("{$t1}.match_time", [
                Helper::getBeforeAfterDayEndTimestamp('-1', 'Y-m-d 00:00:00'),
                Helper::getBeforeAfterDayEndTimestamp('+15')
            ])
            ->select(["tournament_id", "{$t1}.id", "{$t1}.match_time", "{$t1}.status_id", "{$t1}.home_id", "{$t1}.away_id", "{$t1}.home_score", "{$t1}.away_score", "{$t1}.animations", "{$t1}.live_url_1", "{$t1}.live_url_2", "{$t1}.live_url_3"])
            ->where(["{$t1}.is_deleted" => self::DELETED_NO])
            ->orderBy("match_time")
            ->paginate(20);

        if (count($matchList) > 0) {
            $data = [];
            $tournamentName = '';
            $matchList = Helper::array_index_array($matchList, 'match_date');

            foreach ($matchList as $date => $list) {
                $newLists = [];
                foreach ($list as $match) {
                    if (!empty($match->tournament->abbr_zh)) {
                        $tournamentName = $match->tournament->abbr_zh;
                    } elseif (!empty($match->tournament->abbr_en)) {
                        $tournamentName = $match->tournament->abbr_en;
                    } elseif (!empty($match->tournament->name_zh)) {
                        $tournamentName = $match->tournament->name_zh;
                    } elseif (!empty($match->tournament->name_en)) {
                        $tournamentName = $match->tournament->name_en;
                    }
                    $newLists[$tournamentName][] = $match;
                    if (!empty($match->tournament->id)) unset($match->tournament->id);
                    if (!empty($match->tournament->name_zh)) unset($match->tournament->name_zh);
                    if (!empty($match->tournament->name_en)) unset($match->tournament->name_en);
                    if (!empty($match->tournament->abbr_zh)) unset($match->tournament->abbr_zh);
                    if (!empty($match->tournament->abbr_en)) unset($match->tournament->abbr_en);

                }
                $data[] = [
                    'week_day_str' => Helper::getWeekDayStr($date),
                    'date' => $date,
                    'list' => $newLists,
                ];
            }
            Redis::setex(RedisKeyMap::getDota2MatchList($input['page'], $input['action']), config('params.cache.ttl'), $data);
            return $data;
        }
        return [];
    }


}
