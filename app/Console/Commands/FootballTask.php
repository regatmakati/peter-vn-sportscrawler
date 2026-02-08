<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Helpers\RedisKeyMap;
use App\Log\Log;
use App\Models\CmfSportsFootballLineUpModel;
use App\Models\CmfSportsFootballMatchModel;
use App\Models\CmfSportsFootballPlayerModel;
use App\Models\CmfSportsFootballTextLiveModel;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class FootballTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'footballTask:handle {--action=} {--debug=}';

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
                    case 'lineUp':            //比赛阵容，球员信息
                        $err = "比赛阵容爬取任务异常！";
                        self::lineUp();
                        break;
                    case 'playingTextLive':
                        $err = "比赛中的文字直播爬取任务异常！";
                        self::playingTextLive();
                        break;
                    case 'timelyList':
                        $err = "即时比赛数据爬取任务异常！";
                        self::timelyList();
                        break;
                    case 'liveMatchData':
                        $err = "比赛详情爬取任务异常！";
                        self::liveMatchData();
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
     * 比赛详情
     */
    public function liveMatchData()
    {
        $startTime = strtotime("-15 day");
        $endTime = strtotime("+15 day");
        $matchIds = CmfSportsFootballMatchModel::select(['matchId', 'homeId', 'awayId'])->where('matchStartTime', '>=', $startTime)->where('matchStartTime', '<=', $endTime)->pluck('matchId');
        foreach ($matchIds as $matchId) {
            try {
                $client = new Client();
                $response = $client->request("GET", config('params.haiOu.football.liveMatchData') . "?matchId={$matchId}", [
                    'timeout' => '60',
                ]);
                $data = json_decode($response->getBody()->getContents());
                $match = isset($data->data) && !empty($data->data) ? $data->data  : [];
                if (empty($match)) continue;
                echo "正在转储比赛详情，比赛Id：{$matchId}\r\n";
                CmfSportsFootballMatchModel::insertOrUpdate($match, true);
            } catch (\Exception $e) {
                echo "比赛详情抓取异常！{$e->getMessage()} ， file:{$e->getFile()}，line:{$e->getLine()}，trace:{$e->getTraceAsString()}\r\n";
            }
        }
        sleep(60);  //60秒全面抓取一次
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
                $response = $client->request("GET", config('params.haiOu.football.willList'), [
                    'timeout' => '60',
                    'query' => ['dateTime' => $date]
                ]);
                $data = json_decode($response->getBody()->getContents());
                $matchList = isset($data->data) && !empty($data->data) ? $data->data  : [];
                foreach ($matchList as $match) {
                    echo "{$date}赛程转储，比赛Id：{$match->matchId}\r\n";
                    CmfSportsFootballMatchModel::insertOrUpdate($match, true);
                }
            } catch (\Exception $e) {
//                Log::error('赛程抓取异常！', [$e->getMessage(), "file:{$e->getFile()}", "line:{$e->getLine()}，trace:{$e->getTraceAsString()}"]);
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
                $response = $client->request("GET", config('params.haiOu.football.haveList'), [
                    'timeout' => '60',
                    'query' => ['dateTime' => $date]
                ]);
                $data = json_decode($response->getBody()->getContents());
                $matchList = isset($data->data) && !empty($data->data) ? $data->data  : [];
                foreach ($matchList as $match) {
                    echo "{$date}赛果转储，比赛Id：{$match->matchId}\r\n";
                    CmfSportsFootballMatchModel::insertOrUpdate($match, true);
                }
            } catch (\Exception $e) {
//                Log::error('赛果抓取异常！', [$e->getMessage(), "file:{$e->getFile()}", "line:{$e->getLine()}，trace:{$e->getTraceAsString()}"]);
                echo "{$date}赛果抓取异常！{$e->getMessage()} ， file:{$e->getFile()}，line:{$e->getLine()}，trace:{$e->getTraceAsString()}\r\n";
            }
            sleep(30);
        }
        sleep(60);
    }

    public static function lineUp()
    {
        $startTime = strtotime("-15 day");
        $endTime = strtotime("+15 day");
        $result = CmfSportsFootballMatchModel::select(['matchId', 'homeId', 'awayId'])->where('matchStartTime', '>=', $startTime)->where('matchStartTime', '<=', $endTime)->get();
        $matchIds = Helper::array_get_column($result, 'matchId');
        $matchIdMap = Helper::array_index($result, 'matchId');
        foreach ($matchIds as $matchId) {
            try {
                $homeId = $matchIdMap[$matchId]->homeId ?? 0;
                $awayId = $matchIdMap[$matchId]->homeId ?? 0;
                if (empty($homeId) || empty($awayId)) continue;
                $client = new Client();
                $response = $client->request("GET", config('params.haiOu.football.getMatchTeamPlayer') . "?matchId={$matchId}", [
                    'timeout' => '60',
                ]);
                $data = json_decode($response->getBody()->getContents());
                $lineUp = isset($data->data) && !empty($data->data) ? $data->data  : [];
                if (empty($lineUp) || count($lineUp) <= 0) continue;

                echo "正在转储阵容信息！比赛Id：{$matchId}\r\n";

                //整容信息转储
                $lineUp->matchId = $matchId;
                CmfSportsFootballLineUpModel::insertOrUpdate($lineUp);
                //球员信息转储
                if (isset($lineUp->homeTeamLineUp) && !empty($lineUp->homeTeamLineUp)) {
                    foreach ($lineUp->homeTeamLineUp as $player) {
                        $player->teamId = $homeId;
                        $player->matchId = $matchId;
                        CmfSportsFootballPlayerModel::insertOrUpdate($player);
                    }
                }
                if (isset($lineUp->homeTeamSubLineUp) && !empty($lineUp->homeTeamSubLineUp)) {
                    foreach ($lineUp->homeTeamSubLineUp as $player) {
                        $player->teamId = $homeId;
                        $player->matchId = $matchId;
                        CmfSportsFootballPlayerModel::insertOrUpdate($player);
                    }
                }
                if (isset($lineUp->awayTeamLineUp) && !empty($lineUp->awayTeamLineUp)) {
                    foreach ($lineUp->awayTeamLineUp as $player) {
                        $player->teamId = $awayId;
                        $player->matchId = $matchId;
                        CmfSportsFootballPlayerModel::insertOrUpdate($player);
                    }
                }
                if (isset($lineUp->awayTeamSubLineUp) && !empty($lineUp->awayTeamSubLineUp)) {
                    foreach ($lineUp->awayTeamSubLineUp as $player) {
                        $player->teamId = $awayId;
                        $player->matchId = $matchId;
                        CmfSportsFootballPlayerModel::insertOrUpdate($player);
                    }
                }
            } catch (\Exception $e) {
//                Log::error('赛果抓取异常！', [$e->getMessage(), "file:{$e->getFile()}", "line:{$e->getLine()}，trace:{$e->getTraceAsString()}"]);
                echo "赛果抓取异常！{$e->getMessage()} ， file:{$e->getFile()}，line:{$e->getLine()}，trace:{$e->getTraceAsString()}\r\n";
            }
            sleep(10);      //5秒拉一次
        }
    }

    public static function playingTextLive()
    {
        $playingMatchIds = CmfSportsFootballMatchModel::getPlayingMatchIds();
        if (empty($playingMatchIds)) return;
        foreach ($playingMatchIds as $matchId) {
            try {
                $client = new Client();
                $response = $client->request("GET", config('params.haiOu.football.getMatchTextLive') . "?matchId={$matchId}", [
                    'timeout' => '60',
                ]);
                $data = json_decode($response->getBody()->getContents());
                $textLive = isset($data->data) && !empty($data->data) ? $data->data  : [];
                $textLive = json_encode($textLive);
                Redis::setex(RedisKeyMap::getFootballMatchTextLive($matchId), 86400 * 30, $textLive);

                echo "正在转储比赛中的文字直播，比赛Id：{$matchId}\r\n";
                CmfSportsFootballTextLiveModel::insertOrUpdate($matchId, $textLive);
            } catch (\Exception $e) {
//                Log::error('直播抓取异常！', [$e->getMessage(), "file:{$e->getFile()}", "line:{$e->getLine()}，trace:{$e->getTraceAsString()}"]);
                echo "直播抓取异常！{$e->getMessage()} ， file:{$e->getFile()}，line:{$e->getLine()}，trace:{$e->getTraceAsString()}\r\n";
            }
            sleep(10);   //10秒抓取一次
        }
    }

    public static function timelyList()
    {
        try {
            $client = new Client();
            $response = $client->request("GET", config('params.haiOu.football.timelyList'), [
                'timeout' => '60',
            ]);
            $data = json_decode($response->getBody()->getContents());
            $timelyMatchList = isset($data->data) && !empty($data->data) ? $data->data  : [];
            if (empty($timelyMatchList) || count($timelyMatchList) <= 0) return;

            echo "正在转储比赛即时数据！\r\n";

            foreach ($timelyMatchList as $match) {
                CmfSportsFootballMatchModel::insertOrUpdate($match);
                if (!in_array($match->state, CmfSportsFootballMatchModel::$playingStates)) continue;
                if (!empty(CmfSportsFootballMatchModel::timelyListUpdate($match))) {
                    echo "更新比赛即时数据成功，比赛Id：{$match->matchId}\r\n";
                }
            }
        } catch (\Exception $e) {
            echo "即时比赛数据抓取异常！{$e->getMessage()} ， file:{$e->getFile()}，line:{$e->getLine()}，trace:{$e->getTraceAsString()}\r\n";
        }
        sleep(10);   //10秒抓取一次
    }
}
