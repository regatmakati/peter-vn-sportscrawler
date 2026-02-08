<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Helpers\RedisKeyMap;
use App\Log\Log;
use App\Models\CmfSportsBasketballMatchModel;
use App\Models\CmfSportsBasketballLineUpModel;
use App\Models\CmfSportsBasketballPlayerModel;
use App\Models\CmfSportsBasketballTextLiveModel;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class BasketballTask extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'basketballTask:handle {--action=} {--debug=}';

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
                    case 'schedule':    //赛程
                        $err = "赛程爬取任务异常！";
                        self::schedule();
                        break;
                    case 'result':    //赛果
                        $err = "赛果爬取任务异常！";
                        self::result();
                        break;
                    case 'liveMatchData':   //比赛直播数据
                        $err = "比赛直播爬取任务异常！";
                        self::liveMatchData();
                        break;
                    case 'statistic':            //比赛统计，球员信息
                        $err = "比赛统计爬取任务异常！";
                        self::statistic();
                        break;
                    case 'playingTextLive':
                        $err = "比赛中的文字直播爬取任务异常！";
                        self::playingTextLive();
                        break;
                    case 'timelyList':
                        $err = "即时比赛数据爬取任务异常！";
                        self::timelyList();
                        break;
                    default:
                        exit("命令错误！\r\n");
                }
            } catch (\Exception $e) {
                echo "{$err}{$e->getMessage()} ， file:{$e->getFile()}，line:{$e->getLine()}，trace:{$e->getTraceAsString()}\r\n";
                sleep(10);
            }
        }
    }


    /**
     * 赛程（十分钟抓取一次）
     */
    public function schedule()
    {
        $dates = Helper::getBeforeAfterDates();

        foreach ($dates as $date) {
            try {
                $client = new Client();
                $response = $client->request("GET", config('params.haiOu.basketball.willList'), [
                    'timeout' => '60',
                    'query' => ['dateTime' => $date],
                ]);
                $data = json_decode($response->getBody()->getContents());
                $matchList = isset($data->data) && !empty($data->data) ? $data->data  : [];
                foreach ($matchList as $match) {
                    echo "{$date}赛程转储，比赛Id：{$match->matchId}\r\n";
                    CmfSportsBasketballMatchModel::insertOrUpdate($match, true);
                }
            } catch (\Exception $e) {
                echo "{$date}赛程抓取异常！{$e->getMessage()} ， file:{$e->getFile()}，line:{$e->getLine()}，trace:{$e->getTraceAsString()}\r\n";
            }
            sleep(30);
        }
        sleep(60);
    }

    /**
     * 赛赛果（十分钟抓取一次）
     */
    public function result()
    {
        $dates = Helper::getBeforeAfterDates();

        foreach ($dates as $date) {
            try {
                $client = new Client();
                $response = $client->request("GET", config('params.haiOu.basketball.haveList'), [
                    'timeout' => '60',
                    'query' => ['dateTime' => $date]
                ]);
                $data = json_decode($response->getBody()->getContents());
                $matchList = isset($data->data) && !empty($data->data) ? $data->data  : [];
                foreach ($matchList as $match) {
                    echo "{$date}赛果转储，比赛Id：{$match->matchId}\r\n";
                    CmfSportsBasketballMatchModel::insertOrUpdate($match, true);
                }
            } catch (\Exception $e) {
                echo "{$date}赛果抓取异常！{$e->getMessage()} ， file:{$e->getFile()}，line:{$e->getLine()}，trace:{$e->getTraceAsString()}\r\n";
            }
            sleep(30);
        }
        sleep(60);
    }

    public static function statistic()
    {
        $startTime = strtotime("-15 day");
        $endTime = strtotime("+15 day");
        $result = CmfSportsBasketballMatchModel::select(['matchId', 'homeId', 'awayId'])->where('matchStartTime', '>=', $startTime)->where('matchStartTime', '<=', $endTime)->get();
        $matchIds = Helper::array_get_column($result, 'matchId');
        $matchIdMap = Helper::array_index($result, 'matchId');
        foreach ($matchIds as $matchId) {
            try {
                $homeId = $matchIdMap[$matchId]->homeId ?? 0;
                $awayId = $matchIdMap[$matchId]->homeId ?? 0;
                if (empty($homeId) || empty($awayId)) continue;
                $client = new Client();
                $response = $client->request("GET", config('params.haiOu.basketball.statisticData') . "?matchId={$matchId}", [
                    'timeout' => '60',
                ]);
                $data = json_decode($response->getBody()->getContents());
                $statistic = isset($data->data) && !empty($data->data) ? $data->data  : [];
                if (empty($statistic) || count($statistic) <= 0) continue;

                echo "正在转储比赛统计数据！比赛Id：{$matchId}\r\n";
                //球员信息转储
                if (isset($statistic->homePlayerStatistic) && !empty($statistic->homePlayerStatistic)) {
                    foreach ($statistic->homePlayerStatistic as $player) {
                        if (empty(CmfSportsBasketballPlayerModel::insertOrUpdate($player))) {
                            echo "球员信息转储失败！比赛Id：{$matchId}\r\n";
                        }
                    }
                }

            } catch (\Exception $e) {
                echo "赛果抓取异常！{$e->getMessage()} ， file:{$e->getFile()}，line:{$e->getLine()}，trace:{$e->getTraceAsString()}\r\n";
            }
            sleep(10);      //10秒拉一次
        }
    }

    public static function playingTextLive()
    {
        $playingMatchIds = CmfSportsBasketballMatchModel::getPlayingMatchIds();
        if (empty($playingMatchIds)) return;
        foreach ($playingMatchIds as $matchId) {
            try {
                $client = new Client();
                $response = $client->request("GET", config('params.haiOu.basketball.textLiveData') . "?matchId={$matchId}", [
                    'timeout' => '60',
                ]);
                $data = json_decode($response->getBody()->getContents());
                $textLive = isset($data->data) && !empty($data->data) ? $data->data  : [];
                $textLive = json_encode($textLive);
                Redis::setex(RedisKeyMap::getBasketballMatchTextLive($matchId), 86400 * 30, $textLive);

                echo "正在转储比赛中的文字直播，比赛Id：{$matchId}\r\n";
                if (empty(CmfSportsBasketballTextLiveModel::insertOrUpdate($matchId, $textLive))) {
                    echo "转储文字直播数据失败，比赛Id：{$matchId}\r\n";
                }
            } catch (\Exception $e) {
                echo "直播抓取异常！{$e->getMessage()} ， file:{$e->getFile()}，line:{$e->getLine()}，trace:{$e->getTraceAsString()}\r\n";
            }
            sleep(10);   //10秒抓取一次
        }
    }

    public static function liveMatchData()
    {
        $matchIds = CmfSportsBasketballMatchModel::getRecentMatchIds();
        if (empty($matchIds)) return;
        foreach ($matchIds as $matchId) {
            try {
                $client = new Client();
                $response = $client->request("GET", config('params.haiOu.basketball.liveMatchData') . "?matchId={$matchId}", [
                    'timeout' => '60',
                ]);
                $data = json_decode($response->getBody()->getContents());
                $matchLive = isset($data->data) && !empty($data->data) ? $data->data  : [];

                echo "正在更新比赛的直播数据，比赛Id：{$matchId}\r\n";
                if (empty(CmfSportsBasketballMatchModel::updateMatch($matchLive))) {
                    echo "比赛的直播数据失败，比赛Id：{$matchId}\r\n";
                }
            } catch (\Exception $e) {
                echo "比赛的直播抓取异常！{$e->getMessage()} ， file:{$e->getFile()}，line:{$e->getLine()}，trace:{$e->getTraceAsString()}\r\n";
            }
            sleep(10);   //10秒抓取一次
        }
    }

    public static function timelyList()
    {
        try {
            $client = new Client();
            $response = $client->request("GET", config('params.haiOu.basketball.timelyList'), [
                'timeout' => '60',
            ]);
            $data = json_decode($response->getBody()->getContents());
            $timelyMatchList = isset($data->data) && !empty($data->data) ? $data->data  : [];
            if (empty($timelyMatchList) || count($timelyMatchList) <= 0) return;

            echo "正在转储比赛即时数据！\r\n";

            foreach ($timelyMatchList as $match) {
                CmfSportsBasketballMatchModel::insertOrUpdate($match);
                if (!in_array($match->state, CmfSportsBasketballMatchModel::$playingStates)) continue;
                if (empty(CmfSportsBasketballMatchModel::timelyListUpdate($match))) {
                    echo "更新比赛即时数据成功，比赛Id：{$match->matchId}\r\n";
                }
            }
        } catch (\Exception $e) {
            echo "即时比赛数据抓取异常！{$e->getMessage()} ， file:{$e->getFile()}，line:{$e->getLine()}，trace:{$e->getTraceAsString()}\r\n";
        }
        sleep(10);   //10秒抓取一次
    }
}
