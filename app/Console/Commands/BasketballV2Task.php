<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Models\BaseModel;
use App\Models\Sports\SportsBasketballCategoryModel;
use App\Models\Sports\SportsBasketballCompensationModel;
use App\Models\Sports\SportsBasketballCompetitionModel;
use App\Models\Sports\SportsBasketballCountryModel;
use App\Models\Sports\SportsBasketballHonorModel;
use App\Models\Sports\SportsBasketballInjuryModel;
use App\Models\Sports\SportsBasketballMatchAnalysisModel;
use App\Models\Sports\SportsBasketballMatchLiveModel;
use App\Models\Sports\SportsBasketballMatchModel;
use App\Models\Sports\SportsBasketballPlayerModel;
use App\Models\Sports\SportsBasketballSeasonModel;
use App\Models\Sports\SportsBasketballTeamModel;
use App\Models\Sports\SportsBasketballVenueModel;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class BasketballV2Task extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'basketballV2Task:handle {--action=} {--debug=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $action = $this->option('action');
        $debug = $this->option('debug');
        while (true) {
            try {
                switch ($action) {
                    case 'category':
                        $err = "分类任务异常！";
                        self::category();
                        break;
                    case 'country':
                        $err = "国家任务异常！";
                        self::country();
                        break;
                    case 'competition':
                        $err = "赛事任务异常！";
                        self::competition();
                        break;
                    case 'team':
                        $err = "队伍任务异常！";
                        self::team();
                        break;
                    case 'player':
                        $err = "球员任务异常！";
                        self::player();
                        break;
                    case 'venue':
                        $err = "场馆任务异常！";
                        self::venue();
                        break;
                    case 'honor':
                        $err = "荣誉任务异常！";
                        self::honor();
                        break;
                    case 'matchAll':
                        $err = "全量比赛任务异常！";
                        self::matchAll();
                        break;
                    case 'matchBeforeAfter15':
                        $err = "前后15天比赛任务异常！";
                        self::matchBeforeAfter15();
                        break;
                    case 'matchLive':
                        $err = "比赛实时数据任务异常！";
                        self::matchLive();
                        break;
                    case 'trendBeforeAfter15':
                        $err = "前后15天比赛趋势任务异常！";
                        self::trendBeforeAfter15();
                        break;
                    case 'videoLive':
                        $err = "比赛版权视频任务异常！";
                        self::videoLive();
                        break;
                    case 'deleted':
                        $err = "更新删除的数据异常！";
                        self::deleted();
                        break;
                    case 'injury':
                        $err = "伤停任务异常！";
                        self::injury();
                        break;
                    case 'season':
                        $err = "赛季任务异常！";
                        self::season();
                        break;
                    case 'analysis':
                        $err = "分析任务异常！";
                        self::analysis();
                        break;
                    case 'compensation':
                        $err = "历史同赔任务异常！";
                        self::compensation();
                        break;
                    case 'liveHistory':
                        $err = "比赛历史统计任务异常！";
                        self::liveHistory();
                        break;
                    case 'liveUrl':
                        $err = "比赛直播地址任务异常！";
                        self::liveUrl();
                        break;
//                    case 'squad':
//                        $err = "队伍阵容任务异常！";
//                        self::squad();
//                        break;

                }
            } catch (\Exception $e) {
                echo "{$err}{$e->getMessage()} ， file:{$e->getFile()}，line:{$e->getLine()}，trace:{$e->getTraceAsString()}\r\n";
                sleep(10);
            }
        }
    }

    public static function category()
    {
        echo "正在转储分类数据！\r\n";
        Helper::saveNaMiApiData(
            new SportsBasketballCategoryModel(),
            'insertOrUpdate',
            config('params.naMi.basketball.category')
        );
        echo "分类数据转储完毕！\r\n";

        sleep(86400);
    }

    public static function country()
    {
        echo "正在转储国家数据！\r\n";
        Helper::saveNaMiApiData(
            new SportsBasketballCountryModel(),
            'insertOrUpdate',
            config('params.naMi.basketball.country')
        );
        echo "国家数据转储完毕！\r\n";

        sleep(86400);
    }

    public static function competition()
    {
        echo "正在转储赛事数据！\r\n";
        Helper::saveNaMiApiPageData(
            new SportsBasketballCompetitionModel(),
            'insertOrUpdate',
            60,
            config('params.naMi.basketball.competition')
        );
        echo "赛事数据转储完毕！\r\n";
        sleep(60);
    }

    public static function team()
    {
        echo "正在转储队伍数据！\r\n";
        Helper::saveNaMiApiPageData(
            new SportsBasketballTeamModel(),
            'insertOrUpdate',
            60,
            config('params.naMi.basketball.team')
        );
        echo "队伍数据转储完毕！\r\n";
        sleep(60);
    }

    public static function player()
    {
        echo "正在转储球队数据！\r\n";
        Helper::saveNaMiApiPageData(
            new SportsBasketballPlayerModel(),
            'insertOrUpdate',
            60,
            config('params.naMi.basketball.player')
        );
        echo "球队数据转储完毕！\r\n";
        sleep(60);
    }

    public static function venue()
    {
        echo "正在转储场馆数据！\r\n";
        Helper::saveNaMiApiPageData(
            new SportsBasketballVenueModel(),
            'insertOrUpdate',
            60,
            config('params.naMi.basketball.venue')
        );
        echo "场馆数据转储完毕！\r\n";
        sleep(60);
    }

    public static function honor()
    {
        echo "正在转储荣誉数据！\r\n";
        Helper::saveNaMiApiPageData(
            new SportsBasketballHonorModel(),
            'insertOrUpdate',
            60,
            config('params.naMi.basketball.honor')
        );
        echo "荣誉数据转储完毕！\r\n";
        sleep(60);
    }

    public static function matchAll()
    {
        echo "正在转储比赛数据！\r\n";
        Helper::saveNaMiApiPageData(
            new SportsBasketballMatchModel(),
            'insertOrUpdate',
            60,
            config('params.naMi.basketball.match')
        );
        echo "比赛数据转储完毕！\r\n";
        sleep(60);
    }

    public static function matchBeforeAfter15()
    {
        $dates = Helper::getBeforeAfterDates();
        foreach ($dates as $date) {
            echo "正在转储{$date}比赛数据！\r\n";
            $client = new Client();
            $response = $client->request("GET", config('params.naMi.basketball.match_daily'), [
                'timeout' => '60',
                'query' => ['user' => config('params.naMi.user'), 'secret' => config('params.naMi.secret'),'date' => $date]
            ]);
            $data = json_decode($response->getBody()->getContents());
            $matchList = isset($data->results) && !empty($data->results) ? $data->results  : [];
            foreach ($matchList->match as $match) {
				print_r($match);
                SportsBasketballMatchModel::insertOrUpdate($match);
            }
            echo "{$date}比赛数据转储完毕！\r\n";
        }
        sleep(600);
    }

    public static function matchLive()
    {
        echo "正在转储比赛实时数据！\r\n";
        Helper::saveNaMiApiList(
            new SportsBasketballMatchLiveModel(),
            'insertOrUpdate',
            config('params.naMi.basketball.detail_live')
        );
        echo "比赛实时数据转储完毕！\r\n";
        sleep(10);
    }

    public static function trendBeforeAfter15()
    {
        $startTime = strtotime("-15 day");
        $endTime = strtotime("+15 day");
        $matchIds = SportsBasketballMatchModel::whereBetween('match_time', [$startTime, $endTime])->pluck('id');
        $sportsBasketballMatchModel = new SportsBasketballMatchModel();
        foreach ($matchIds as $matchId) {
            try {
                echo "正在转储{$matchId}比赛趋势数据！\r\n";
                $client = new Client();
                $response = $client->request("GET", config('params.naMi.basketball.trend'), [
                    'timeout' => '60',
                    'query' => ['user' => config('params.naMi.user'), 'secret' => config('params.naMi.secret'), 'id' => $matchId]
                ]);
                $data = json_decode($response->getBody()->getContents());
                if (!empty($data->results)) {
                    $sportsBasketballMatchModel->updateColumnsByPk(['id' => $matchId, 'trend' => json_encode($data->results)]);
                }
                usleep(500 * 1000);
                echo "{$matchId}比赛趋势数据转储完毕！\r\n";
            } catch (\Exception $e) {
                echo "{$matchId}比赛趋势请求失败！\r\n{$e->getMessage()} ， file:{$e->getFile()}，line:{$e->getLine()}，trace:{$e->getTraceAsString()}\r\n";
                sleep(1);
            }
        }
    }

    public static function videoLive()
    {
        $client = new Client();
        $response = $client->request("GET", config('params.naMi.basketball.livePath'), [
            'timeout' => '60',
            'query' => ['user' => config('params.naMi.user'), 'secret' => config('params.naMi.secret')]
        ]);
        $data = json_decode($response->getBody()->getContents());
        if (!empty($data->results)) {
            foreach ($data->results as $live) {				
                //if ($live->sport_id != 2) continue;
                echo "正在转储{$live->match_id}比赛版权视频数据！\r\n";
                $sportsBasketballMatchModel = new SportsBasketballMatchModel();
                $sportsBasketballMatchModel->updateColumnsByPk([
                    'id' => $live->match_id,
                    'pc_link' => $live->pc_link,
                    'mobile_link' => $live->mobile_link,
                ]);
            }

        }
        echo "版权视频比赛数据转储完毕！\r\n";
        sleep(600);
    }

    public static function deleted()
    {
        echo "正在更新删除的数据！\r\n";
        $client = new Client();
        $response = $client->request("GET", config('params.naMi.basketball.deleted'), [
            'timeout' => '60',
            'query' => ['user' => config('params.naMi.user'), 'secret' => config('params.naMi.secret')]
        ]);
        $data = json_decode($response->getBody()->getContents());
        $deleted= isset($data->results) && !empty($data->results) ? $data->results  : [];

        $func = function ($tagName, BaseModel $model) use ($deleted) {
            if (!empty($deleted->$tagName) && is_array($deleted->$tagName)) {
                foreach ($deleted->$tagName as $matchId) {
                    $model->updateColumnsByPk([
                        'id' => $matchId,
                        'is_deleted' => BaseModel::DELETED_YES,
                    ]);
                }
            }
        };
        $func('match', new SportsBasketballMatchModel());
        $func('team', new SportsBasketballTeamModel());
        $func('player', new SportsBasketballPlayerModel());
        $func('competition', new SportsBasketballCompetitionModel());
        $func('season', new SportsBasketballSeasonModel());

        echo "更新删除的数据完毕！\r\n";
        sleep(300);
    }

    public static function injury()
    {
        echo "正在转储伤停数据！\r\n";
        Helper::saveNaMiApiPageData(
            new SportsBasketballInjuryModel(),
            'insertOrUpdate',
            60,
            config('params.naMi.basketball.injury')
        );
        echo "伤停数据转储完毕！\r\n";
        sleep(60);
    }

    public static function season()
    {
        echo "正在赛季数据！\r\n";
        Helper::saveNaMiApiPageData(
            new SportsBasketballSeasonModel(),
            'insertOrUpdate',
            60,
            config('params.naMi.basketball.season')
        );
        echo "赛季数据转储完毕！\r\n";
        sleep(60);
    }

    public static function analysis()
    {
        $startTime = strtotime("-15 day");
        $endTime = strtotime("+15 day");
        $matchIds = SportsBasketballMatchModel::whereBetween('match_time', [$startTime, $endTime])->pluck('id');
        foreach ($matchIds as $matchId) {
            echo "正在转储{$matchId}比赛分析！\r\n";
            try {
                $client = new Client();
                $response = $client->request("GET", config('params.naMi.basketball.analysis'), [
                    'timeout' => '60',
                    'query' => [
                        'user' => config('params.naMi.user'),
                        'secret' => config('params.naMi.secret'),
                        'id' => $matchId,
                    ]
                ]);
                $data = json_decode($response->getBody()->getContents());
                if (!empty($data)) {
                    $data->match_id = $matchId;
                    SportsBasketballMatchAnalysisModel::insertOrUpdate($data);
                }
                sleep(1);
            } catch (\Exception $e) {
                echo "{$matchId}比赛分析请求失败！\r\n{$e->getMessage()} ， file:{$e->getFile()}，line:{$e->getLine()}，trace:{$e->getTraceAsString()}\r\n";
                sleep(1);
            }
        }
        sleep(10);
    }

    public static function compensation()
    {
        echo "正在转储历史同赔数据！\r\n";
        Helper::saveNaMiApiPageData(
            new SportsBasketballCompensationModel(),
            'insertOrUpdate',
            2,
            config('params.naMi.basketball.compensation')
        );
        echo "历史同赔数据转储完毕！\r\n";
        sleep(60);
    }

    public static function liveHistory()
    {
        $startTime = strtotime("-15 day");
        $endTime = strtotime("+15 day");
        $matchIds = SportsBasketballMatchModel::whereBetween('match_time', [$startTime, $endTime])->pluck('id');
        foreach ($matchIds as $matchId) {
            echo "正在转储{$matchId}历史比赛统计数据！\r\n";
            try {
                $client = new Client();
                $response = $client->request("GET", config('params.naMi.basketball.history'), [
                    'timeout' => '60',
                    'query' => [
                        'user' => config('params.naMi.user'),
                        'secret' => config('params.naMi.secret'),
                        'id' => $matchId,
                    ]
                ]);
                $data = json_decode($response->getBody()->getContents());				
                if (isset($data->results) && (array)$data->results) {
                    SportsBasketballMatchLiveModel::insertOrUpdate($data->results);
                }
                usleep(500 * 1000);
            } catch (\Exception $e) {
                echo "{$matchId}历史比赛统计请求失败！\r\n{$e->getMessage()} ， file:{$e->getFile()}，line:{$e->getLine()}，trace:{$e->getTraceAsString()}\r\n";
                sleep(1);
            }
        }
        sleep(10);
    }

    public static function liveUrl()
    {
        echo "正在更新比赛直播地址！\r\n";
        $client = new Client();
        $response = $client->request("GET", config('params.naMi.live_url'), [
            'timeout' => '60',
            'query' => ['user' => config('params.naMi.user'), 'secret' => config('params.naMi.secret')]
        ]);
        $data = json_decode($response->getBody()->getContents());

        if (!empty($data->data) && is_array($data->data)) {
            $model = new SportsBasketballMatchModel();
            foreach ($data->data as $live) {
                if ($live->sport_id != 2) continue;
                $live_url_1 = explode('/', $live->pushurl1);
                $live_url_2 = explode('/', $live->pushurl2);
                $live_url_3 = explode('/', $live->pushurl3);
                $live_url_1 = $live_url_1[count($live_url_1) - 1] ?? '';
                $live_url_2 = $live_url_2[count($live_url_2) - 1] ?? '';
                $live_url_3 = $live_url_3[count($live_url_3) - 1] ?? '';
                $model->updateColumnsByPk([
                    'id' => $live->match_id,
                    'live_url_1' => $live_url_1,
                    'live_url_2' => $live_url_2,
                    'live_url_3' => $live_url_3,
                ]);
            }
        }

        echo "比赛直播地址更新完毕！\r\n";
        sleep(30);
    }

//    public static function squad()
//    {
//        echo "正在转储队伍阵容数据！\r\n";
//        Helper::saveNaMiApiPageData(
//            new SportsBasketballPlayerModel(),
//            'updateTeamId',
//            60,
//            config('params.naMi.basketball.squad')
//        );
//        echo "队伍阵容数据转储完毕！\r\n";
//        sleep(60);
//    }

}
