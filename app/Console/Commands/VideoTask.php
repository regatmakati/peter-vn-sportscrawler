<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Helpers\RedisKeyMap;
use App\Log\Log;
use App\Models\CmfSportsBasketballMatchModel;
use App\Models\CmfSportsBasketballLineUpModel;
use App\Models\CmfSportsBasketballPlayerModel;
use App\Models\CmfSportsBasketballTextLiveModel;
use App\Models\CmfSportsFootballMatchModel;
use App\Models\CmfSportsFootballTeamModel;
use App\Models\CmfUserModel;
use App\Models\CmfVideoModel;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class VideoTask extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'videoTask:handle {--action=} {--debug=}';

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
                    case 'update':    //视频绑定用户
                        $err = "视频绑定用户任务异常！";
                        self::update();
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
     * 视频绑定用户（一分钟抓取一次）
     */
    public function update()
    {
        $new = CmfVideoModel::anyNewVideo();
        if(!$new){
            echo "暂无新视频\r\n";
        }else{
            $userId = CmfUserModel::addRandUser();
            echo "视频绑定用户，用户ID：{$userId}\r\n";
            $res = CmfVideoModel::updateVideo($userId);
        }
        sleep(60);
    }




}
