<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Models\BaseModel;
use App\Models\Sports\SportsDota2CountryModel;
use App\Models\Sports\SportsDota2EquipmentModel;
use App\Models\Sports\SportsDota2HeroModel;
use App\Models\Sports\SportsDota2HeroRuneModel;
use App\Models\Sports\SportsDota2HeroSpellModel;
use App\Models\Sports\SportsDota2MatchLiveModel;
use App\Models\Sports\SportsDota2MatchModel;
use App\Models\Sports\SportsDota2MatchOddsModel;
use App\Models\Sports\SportsDota2MatchSingleModel;
use App\Models\Sports\SportsDota2MatchSinglePlayerStatModel;
use App\Models\Sports\SportsDota2PlayerModel;
use App\Models\Sports\SportsDota2RegionModel;
use App\Models\Sports\SportsDota2StageModel;
use App\Models\Sports\SportsDota2TeamModel;
use App\Models\Sports\SportsDota2TournamentModel;
use App\Models\Sports\SportsDota2TournamentPvpLineModel;
use App\Models\Sports\SportsDota2TournamentPvpModel;
use App\Models\Sports\SportsDota2TournamentRankModel;
use App\Models\Sports\SportsDota2TournamentRoundModel;
use App\Models\Sports\SportsDota2TournamentTeamLinksModel;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class Dota2Task extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dota2Task:handle {--action=} {--debug=}';

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
                $err = "任务异常！";
                switch ($action) {
                    case 'country':
                        self::country();
                        break;
                    case 'tournament':
                        self::tournament();
                        break;
                    case 'player':
                        self::player();
                        break;
                    case 'team':
                        self::team();
                        break;
                    case 'region':
                        self::region();
                        break;
                    case 'hero':
                        self::hero();
                        break;
                    case 'heroRune':
                        self::heroRune();
                        break;
                    case 'heroSpell':
                        self::heroSpell();
                        break;
                    case 'equipment':
                        self::equipment();
                        break;
                    case 'match':
                        self::match();
                        break;
                    case 'matchSingle':
                        self::matchSingle();
                        break;
                    case 'matchSinglePlayerStat':
                        self::matchSinglePlayerStat();
                        break;
                    case 'tournamentMatch':
                        self::tournamentMatch();
                        break;
                    case 'matchLive':
                        self::matchLive();
                        break;
                    case 'stream':
                        self::stream();
                        break;
                    case 'deleted':
                        self::deleted();
                        break;
                    case 'oddsLive':
                        self::oddsLive();
                        break;
                    case 'oddsHistory':
                        self::oddsHistory();
                        break;
                    case 'stage':
                        self::stage();
                        break;
                    case 'tournamentTeam':
                        self::tournamentTeam();
                        break;
                    case 'tournamentRound':
                        self::tournamentRound();
                        break;
                    case 'tournamentPvp':
                        self::tournamentPvp();
                        break;
                    case 'tournamentPvpLine':
                        self::tournamentPvpLine();
                        break;
                    case 'tournamentRank':
                        self::tournamentRank();
                        break;
                    default:
                        echo "无此命令！\r\n";
                        sleep(10);
                }
            } catch (\Exception $e) {
                echo "{$err}{$e->getMessage()} ， file:{$e->getFile()}，line:{$e->getLine()}，trace:{$e->getTraceAsString()}\r\n";
                sleep(10);
            }
        }
    }

    public static function country()
    {
        echo "正在转储国家数据！\r\n";
        $queryParams['query']['user'] = config('params.naMi.game.user');
        $queryParams['query']['secret'] = config('params.naMi.game.secret');
        Helper::saveNaMiApiData(
            new SportsDota2CountryModel(),
            'insertOrUpdate',
            config('params.naMi.dota2.country'),
            $queryParams
        );
        echo "国家数据转储完毕！\r\n";
        sleep(86400);
    }

    public static function tournament()
    {
        echo "正在转储赛事数据！\r\n";
        $queryParams['query']['user'] = config('params.naMi.game.user');
        $queryParams['query']['secret'] = config('params.naMi.game.secret');
        Helper::saveNaMiApiPageData(
            new SportsDota2TournamentModel(),
            'insertOrUpdate',
            '5',
            config('params.naMi.dota2.tournament'),
            $queryParams
        );
        echo "赛事数据转储完毕！\r\n";

        sleep(60);
    }

    public static function player()
    {
        echo "正在转储选手数据！\r\n";
        $queryParams['query']['user'] = config('params.naMi.game.user');
        $queryParams['query']['secret'] = config('params.naMi.game.secret');
        Helper::saveNaMiApiPageData(
            new SportsDota2PlayerModel(),
            'insertOrUpdate',
            '5',
            config('params.naMi.dota2.player'),
            $queryParams
        );
        echo "选手数据转储完毕！\r\n";

        sleep(60);
    }

    public static function team()
    {
        echo "正在转储战队数据！\r\n";
        $queryParams['query']['user'] = config('params.naMi.game.user');
        $queryParams['query']['secret'] = config('params.naMi.game.secret');
        Helper::saveNaMiApiPageData(
            new SportsDota2TeamModel(),
            'insertOrUpdate',
            '60',
            config('params.naMi.dota2.team'),
            $queryParams
        );
        echo "战队数据转储完毕！\r\n";

        sleep(60);
    }

    public static function region()
    {
        echo "正在转储赛区数据！\r\n";
        $queryParams['query']['user'] = config('params.naMi.game.user');
        $queryParams['query']['secret'] = config('params.naMi.game.secret');
        Helper::saveNaMiApiData(
            new SportsDota2RegionModel(),
            'insertOrUpdate',
            config('params.naMi.dota2.region'),
            $queryParams
        );
        echo "赛区数据转储完毕！\r\n";

        sleep(86400);
    }

    public static function hero()
    {
        echo "正在转储英雄数据！\r\n";
        $queryParams['query']['user'] = config('params.naMi.game.user');
        $queryParams['query']['secret'] = config('params.naMi.game.secret');
        Helper::saveNaMiApiPageData(
            new SportsDota2HeroModel(),
            'insertOrUpdate',
            '60',
            config('params.naMi.dota2.hero'),
            $queryParams
        );
        echo "英雄数据转储完毕！\r\n";

        sleep(60);
    }

    public static function heroRune()
    {
        echo "正在转储英雄天赋数据！\r\n";
        $queryParams['query']['user'] = config('params.naMi.game.user');
        $queryParams['query']['secret'] = config('params.naMi.game.secret');
        Helper::saveNaMiApiPageData(
            new SportsDota2HeroRuneModel(),
            'insertOrUpdate',
            '60',
            config('params.naMi.dota2.hero_rune'),
            $queryParams
        );
        echo "英雄天赋数据转储完毕！\r\n";

        sleep(60);
    }

    public static function heroSpell()
    {
        echo "正在转储英雄技能数据！\r\n";
        $queryParams['query']['user'] = config('params.naMi.game.user');
        $queryParams['query']['secret'] = config('params.naMi.game.secret');
        Helper::saveNaMiApiPageData(
            new SportsDota2HeroSpellModel(),
            'insertOrUpdate',
            '60',
            config('params.naMi.dota2.hero_spell'),
            $queryParams
        );
        echo "英雄技能数据转储完毕！\r\n";

        sleep(60);
    }

    public static function equipment()
    {
        echo "正在转储装备数据！\r\n";
        $queryParams['query']['user'] = config('params.naMi.game.user');
        $queryParams['query']['secret'] = config('params.naMi.game.secret');
        Helper::saveNaMiApiPageData(
            new SportsDota2EquipmentModel(),
            'insertOrUpdate',
            '60',
            config('params.naMi.dota2.equipment'),
            $queryParams
        );
        echo "装备数据转储完毕！\r\n";

        sleep(60);
    }

    public static function match()
    {
        echo "正在转储比赛数据！\r\n";
        $queryParams['query']['user'] = config('params.naMi.game.user');
        $queryParams['query']['secret'] = config('params.naMi.game.secret');
        Helper::saveNaMiApiPageData(
            new SportsDota2MatchModel(),
            'insertOrUpdate',
            '60',
            config('params.naMi.dota2.match'),
            $queryParams
        );
        echo "比赛数据转储完毕！\r\n";

        sleep(60);
    }

    public static function matchSingle()
    {
        echo "正在转储比赛单局数据！\r\n";
        $queryParams['query']['user'] = config('params.naMi.game.user');
        $queryParams['query']['secret'] = config('params.naMi.game.secret');
        Helper::saveNaMiApiPageData(
            new SportsDota2MatchSingleModel(),
            'insertOrUpdate',
            '60',
            config('params.naMi.dota2.match_single'),
            $queryParams
        );
        echo "比赛单局数据转储完毕！\r\n";

        sleep(60);
    }

    public static function matchSinglePlayerStat()
    {
        echo "正在转储比赛单局选手数据！\r\n";
        $queryParams['query']['user'] = config('params.naMi.game.user');
        $queryParams['query']['secret'] = config('params.naMi.game.secret');
        Helper::saveNaMiApiPageData(
            new SportsDota2MatchSinglePlayerStatModel(),
            'insertOrUpdate',
            '60',
            config('params.naMi.dota2.match_single_player_stat'),
            $queryParams
        );
        echo "比赛单局选手数据转储完毕！\r\n";

        sleep(60);
    }

    public static function tournamentMatch()
    {
        $ids = SportsDota2TournamentModel::getBeforeAfter15DaysPks();
        foreach ($ids as $id) {
            echo "正在转储赛事{$id}比赛数据！\r\n";
            $queryParams['query']['user'] = config('params.naMi.game.user');
            $queryParams['query']['secret'] = config('params.naMi.game.secret');
            $queryParams['query']['id'] = $id;

            Helper::saveNaMiApiData(
                new SportsDota2MatchModel(),
                'insertOrUpdate',
                config('params.naMi.dota2.tournament_match'),
                $queryParams
            );
            echo "赛事{$id}比赛数据转储完毕！\r\n";

            usleep(500 * 1000);
        }
        sleep(60);
    }

    public static function matchLive()
    {
        echo "正在转储实时比赛数据！\r\n";
        $queryParams['query']['user'] = config('params.naMi.game.user');
        $queryParams['query']['secret'] = config('params.naMi.game.secret');
        Helper::saveNaMiApiData(
            new SportsDota2MatchLiveModel(),
            'insertOrUpdate',
            config('params.naMi.dota2.match_live'),
            $queryParams
        );
        echo "实时比赛数据转储完毕！\r\n";

        sleep(5);
    }

    public static function stream()
    {
        echo "正在转储比赛视频录像、动画数据！\r\n";

        $client = new Client();
        $response = $client->request("GET", config('params.naMi.dota2.stream'), [
            'timeout' => '60',
            'query' => ['user' => config('params.naMi.game.user'), 'secret' => config('params.naMi.game.secret')]
        ]);
        $data = json_decode($response->getBody()->getContents());
        $streamList = isset($data->data) && !empty($data->data) ? $data->data  : [];
        $matchModel = new SportsDota2MatchModel();
        foreach ($streamList as $stream) {
            if (!empty($stream->animations)) {
                $matchModel->updateColumnsByPk(['id' => $stream->id, 'animations' => json_encode($stream->animations)]);
            }
        }
        echo "比赛视频录像、动画数据转储完毕！\r\n";
        sleep(300);
    }

    public static function deleted()
    {
        echo "正在更新删除的数据！\r\n";
        $client = new Client();
        $response = $client->request("GET", config('params.naMi.dota2.delete'), [
            'timeout' => '60',
            'query' => ['user' => config('params.naMi.game.user'), 'secret' => config('params.naMi.game.secret')]
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
        $func('match_ids', new SportsDota2MatchModel());
        $func('match_single_ids', new SportsDota2MatchSingleModel());
        $func('player_stat_ids', new SportsDota2MatchSinglePlayerStatModel());
        $func('tournament_ids', new SportsDota2TournamentModel());
        $func('player_ids', new SportsDota2PlayerModel());
        $func('team_ids', new SportsDota2TeamModel());

        echo "更新删除的数据完毕！\r\n";
        sleep(120);
    }

    public static function oddsLive()
    {
        echo "正在转储实时比赛指数数据！\r\n";
        $client = new Client();
        $response = $client->request("GET", config('params.naMi.dota2.odds_live'), [
            'timeout' => '60',
            'query' => ['user' => config('params.naMi.game.user'), 'secret' => config('params.naMi.game.secret')]
        ]);
        $data = json_decode($response->getBody()->getContents());
        $oddsList = isset($data->results) && !empty($data->results) ? $data->results  : [];
        foreach ($oddsList as $odds_company_id => $list) {
            foreach ($list as $odds) {
                SportsDota2MatchOddsModel::insertOrUpdate($odds_company_id, $odds[1], $odds[2], $odds[0], $odds[3]);
            }
        }
        echo "实时比赛指数数据转储完毕！\r\n";

        sleep(5);
    }

    public static function oddsHistory()
    {
        $matchIds = SportsDota2MatchModel::getBeforeAfter15DaysPks();
        foreach ($matchIds as $matchId) {
            echo "正在转储比赛{$matchId}历史指数数据！\r\n";
            $client = new Client();
            $response = $client->request("GET", config('params.naMi.dota2.odds_history'), [
                'timeout' => '60',
                'query' => [
                    'user' => config('params.naMi.game.user'),
                    'secret' => config('params.naMi.game.secret'),
                    'id' => $matchId
                ]
            ]);
            $data = json_decode($response->getBody()->getContents());
            $oddsCompanies = isset($data->results) && !empty($data->results) ? $data->results  : [];

            foreach ($oddsCompanies as $odds_company_id => $oddsTypes) {
                foreach ($oddsTypes as $odds_type_id => $oddsRanges) {
                    foreach ($oddsRanges as $range => $oddsList) {
                        foreach ($oddsList as $odds) {
                            SportsDota2MatchOddsModel::insertOrUpdate($odds_company_id, $odds_type_id, $range, $matchId, $odds);
                        }
                    }
                }
            }
            echo "比赛{$matchId}历史指数数据转储完毕！\r\n";
            usleep(500 * 1000);
        }
        sleep(5);
    }

    public static function stage()
    {
        echo "正在转储阶段数据！\r\n";
        $queryParams['query']['user'] = config('params.naMi.game.user');
        $queryParams['query']['secret'] = config('params.naMi.game.secret');
        Helper::saveNaMiApiPageData(
            new SportsDota2StageModel(),
            'insertOrUpdate',
            '60',
            config('params.naMi.dota2.stage'),
            $queryParams
        );
        echo "阶段数据转储完毕！\r\n";

        sleep(60);
    }

    public static function tournamentTeam()
    {
        echo "正在转储阶段数据！\r\n";
        $queryParams['query']['user'] = config('params.naMi.game.user');
        $queryParams['query']['secret'] = config('params.naMi.game.secret');
        Helper::saveNaMiApiPageData(
            new SportsDota2TournamentTeamLinksModel(),
            'insertOrUpdate',
            '60',
            config('params.naMi.dota2.tournament_team'),
            $queryParams
        );
        echo "阶段数据转储完毕！\r\n";

        sleep(60);
    }

    public static function tournamentRound()
    {
        echo "正在转储赛事轮次数据！\r\n";
        $queryParams['query']['user'] = config('params.naMi.game.user');
        $queryParams['query']['secret'] = config('params.naMi.game.secret');
        Helper::saveNaMiApiPageData(
            new SportsDota2TournamentRoundModel(),
            'insertOrUpdate',
            '60',
            config('params.naMi.dota2.tournament_round'),
            $queryParams
        );
        echo "赛事轮次数据转储完毕！\r\n";

        sleep(60);
    }

    public static function tournamentPvp()
    {
        echo "正在转储赛事对阵数据！\r\n";
        $queryParams['query']['user'] = config('params.naMi.game.user');
        $queryParams['query']['secret'] = config('params.naMi.game.secret');
        Helper::saveNaMiApiPageData(
            new SportsDota2TournamentPvpModel(),
            'insertOrUpdate',
            '60',
            config('params.naMi.dota2.tournament_pvp'),
            $queryParams
        );
        echo "赛事对阵数据转储完毕！\r\n";

        sleep(60);
    }

    public static function tournamentPvpLine()
    {
        echo "正在转储赛事对阵连线数据！\r\n";
        $queryParams['query']['user'] = config('params.naMi.game.user');
        $queryParams['query']['secret'] = config('params.naMi.game.secret');
        Helper::saveNaMiApiPageData(
            new SportsDota2TournamentPvpLineModel(),
            'insertOrUpdate',
            '60',
            config('params.naMi.dota2.tournament_pvp_line'),
            $queryParams
        );
        echo "赛事对阵连线数据转储完毕！\r\n";

        sleep(60);
    }

    public static function tournamentRank()
    {
        echo "正在转储赛事积分数据！\r\n";
        $queryParams['query']['user'] = config('params.naMi.game.user');
        $queryParams['query']['secret'] = config('params.naMi.game.secret');
        Helper::saveNaMiApiPageData(
            new SportsDota2TournamentRankModel(),
            'insertOrUpdate',
            '60',
            config('params.naMi.dota2.tournament_rank'),
            $queryParams
        );
        echo "赛事积分数据转储完毕！\r\n";

        sleep(60);
    }


}
