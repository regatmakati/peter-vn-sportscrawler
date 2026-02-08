<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Helpers\RedisKeyMap;
use App\Log\Log;
use App\Models\CmfOptionModel;
use App\Models\CmfSportsBasketballMatchModel;
use App\Models\CmfSportsBasketballLineUpModel;
use App\Models\CmfSportsBasketballPlayerModel;
use App\Models\CmfSportsBasketballTextLiveModel;
use App\Models\CmfSportsFootballMatchModel;
use App\Models\CmfLiveModel;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Live\V20180801\LiveClient;
use TencentCloud\Live\V20180801\Models\DescribeLiveStreamStateRequest;

use Qcloud\Cos\Client as QClient;

class LiveTask extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'liveTask:handle {--action=} {--debug=}';

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
                    case 'live':    //正在进行直播
                        $err = "正在进行直播爬取任务异常！";
                        self::live();
                        break;
                    case 'deleteLive':    //删除异常下播
                        $err = "删除异常下播任务异常！";
                        self::deleteLive();
                        break;
                    case 'pushThirdLive': //推送第三方流
                        $err = "推送第三方流任务异常！";
                        self::pushThirdLive();
                        break;
//                    case 'getLiveShot':    //获取直播截图
//                        $err = "获取直播截图任务异常！";
//                        self::getLiveShot();
//                        break;
//                    case 'future':    //未来直播
//                        $err = "未来直播爬取任务异常！";
//                        self::future();
//                        break;
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
     * 获取直播截图（一分钟执行一次）
     */
    public function getLiveShot()
    {
		$secretId = config('params.tencent.cos.secret_id'); //"云 API 密钥 SecretId";
		$secretKey = config('params.tencent.cos.secret_key'); //"云 API 密钥 SecretKey";
		$region = config('params.tencent.cos.region'); //设置一个默认的存储桶地域
		$cosClient = new QClient(
			array(
				'region' => $region,
				'schema' => 'https', //协议头部，默认为http
				'credentials'=> array(
					'secretId'  => $secretId ,
					'secretKey' => $secretKey)));

        $liveList = CmfLiveModel::getAllLive();

		foreach($liveList as $key=>$val){
			$streamName = $val['stream'];
			$pull = $val['pull'];//拉流地址,现有鉴权有可能过期,需重新鉴权
			$pareurl = explode('?',$pull);
			$baseurl = $pareurl[0];
			$pos = strpos($baseurl,'://');
			if(!$pos){
				$baseurl = config('params.tencent.live.pullProtocol').'://'.$baseurl;
			}

			//生成鉴权链接$url
			$key = config('params.tencent.live.key');
			$txTime = strtoupper(base_convert(time(),10,16));
			$txSecret = md5($key.$streamName.$txTime);
			$url = $baseurl."?txSecret={$txSecret}&txTime={$txTime}";

			//生成截图存储目录
			$uid = $val['uid'];
			$ntime = time();
			$sdate = date('Ymd',$ntime);
			$dir = "/data/wwwroot/SportsCrawler/public/liveScreenShot/{$sdate}";
			$pic = $dir."/pic_{$uid}_{$ntime}.jpg";
			if(!is_dir($dir)){
				$cmd = "mkdir -p $dir";
				exec($cmd,$res);
			}

			//删除已存在的截图
			$cmd = "rm -f {$dir}/*";
			exec($cmd,$res);

			//获取截图
			$cmd="/usr/local/ffmpeg/bin/ffmpeg -i '$url' -y -f image2 -ss 00:00:01 -t 0.001 -vframes 1 -s 800x450 -aspect 16:9 '$pic'";
			exec($cmd,$res);

			//获取成功则上传腾讯cos
			$input['uid'] = $uid;
			if(file_exists($pic)){
				$key = "images/liveScreenShot/{$sdate}/pic_{$uid}_{$ntime}.jpg";
				### 上传文件流
				try {
					$file = fopen($pic, 'rb');
					if ($file) {
						$result = $cosClient->Upload(
							$bucket = config('params.tencent.cos.bucket'),
							$key = $key,
							$body = $file);
					}
					print_r($result);
					echo "文件已上传cos：{$key}\r\n";
				} catch (\Exception $e) {
					echo "上传文件到腾讯云失败，路径：{$key}\r\n";
					echo $e->getMessage() . "\r\n";
				}

				//更新live表
				$picurl = "images/liveScreenShot/{$sdate}/pic_{$uid}_{$ntime}.jpg";
				$input['pic_full_url'] = $picurl;
				$input['isoff'] = 0;
				CmfLiveModel::updateLive($input);
			}else{
				$input['pic_full_url'] = '';
				$input['isoff'] = 1;
				CmfLiveModel::updateLive($input);
			}

		}

        sleep(60);

    }


    /**
     * 推送第三方流（一分钟执行一次）
     */
    public function pushThirdLive()
    {
        $liveList = CmfLiveModel::getThirdLive();
        exec("ps -A | grep ffmpeg | grep -v grep", $ffmpegOutputs);
        //关闭后台已删除http转https推流任务
        foreach ($ffmpegOutputs as $ffmpegOutput) {
            $isProcessExists = false;
            $ffmpegOutputArr = explode(' ', $ffmpegOutput);
            $ffmpegOutputProccessId = empty($ffmpegOutputArr[0]) ? $ffmpegOutputArr[1] : '';
            $ffmpegCmdLine = file_get_contents("/proc/{$ffmpegOutputProccessId}/cmdline");
            foreach($liveList as $live) {
                if (strstr($ffmpegCmdLine, $live->third_pull) !== false) {
                    $isProcessExists = true;
                }
            }
            if (empty($isProcessExists)) {      //如果此ffmpeg推流任务不存在，则关闭此进程
                echo "此ffpmeg推流任务将被关闭：{$ffmpegOutput}\r\n";
                echo "进程id：{$ffmpegOutputProccessId}\r\n";
                $killOutput = shell_exec("kill {$ffmpegOutputProccessId}");
                echo "关闭进程执行结果:{$killOutput}\r\n";
            }
        }

        //新建推流任务
        foreach($liveList as $live){
            echo "正在修改用户{$live['uid']}的流地址！\r\n";
            $third_pull = $live->third_pull;
            $path = parse_url($third_pull);
            $streams = explode(".",$path['path']);
            $stream = explode("/",$streams[0]);
            $stream = $stream[count($stream)-1];
            $config = json_decode(CmfOptionModel::select(['option_value'])->where(['option_name' => "configpri"])->value('option_value'));
            $tx_pull = $config->tx_pull;
            $selfPull = $tx_pull . $path['path'];
            try {
                //推流
                $input['pull'] = $selfPull;
                $input['stream'] = $stream;
                $isExistsPushCommand = false;
                CmfLiveModel::where('uid','=',$live['uid'])->update($input);
                $push = Helper::getTencentPushOrPullUrl($stream, 1, config("params.recordpush.url"));
                //判断当前命令是否存在
                foreach ($ffmpegOutputs as $ffmpegOutput) {
                    $ffmpegOutputArr = explode(' ', $ffmpegOutput);
                    $ffmpegOutputProccessId = $ffmpegOutputArr[0] ?? '';
                    $ffmpegCmdLine = file_get_contents("/proc/{$ffmpegOutputProccessId}/cmdline");
                    if (strstr($ffmpegCmdLine, $third_pull) !== false) {
                        $isExistsPushCommand = true;
                    }
                }

                if (empty($isExistsPushCommand)) {  //进程不存在
                    $cmd = "/usr/local/ffmpeg/bin/ffmpeg -i '{$third_pull}' -acodec copy -vcodec copy -f flv '{$push}' </dev/null &>/dev/null & ";
                    echo "执行命令：{$cmd}\r\n";
                    $outputStr = shell_exec($cmd);
                    echo "执行结果：".json_encode($outputStr)."\r\n";
                } else {
                    echo "此直播间正在推流，不能重复推流！\r\n";
                }
                unset($outputStr);
            } catch(\Exception $e) {
                echo "流地址修改异常！{$e->getMessage()} ， file:{$e->getFile()}，line:{$e->getLine()}，trace:{$e->getTraceAsString()}\r\n";
            }
        }

        unset($ffmpegOutputs);
        sleep(10);
    }


    /**
     * 删除异常下播直播（一分钟执行一次）
     */
    public function deleteLive()
    {
        $liveList = CmfLiveModel::getDeleteLive();
		foreach($liveList as $key=>$val){
		    echo "正在检测用户{$val['uid']}的直播间！\r\n";
			$stream = $val['stream'];

			try {
				$cred = new Credential(config('params.tencent.live.SecretId'), config('params.tencent.live.SecretKey'));
				$httpProfile = new HttpProfile();
				$httpProfile->setEndpoint("live.tencentcloudapi.com");

				$clientProfile = new ClientProfile();
				$clientProfile->setHttpProfile($httpProfile);
				$client = new LiveClient($cred, "", $clientProfile);

				$req = new DescribeLiveStreamStateRequest();

				$params = array(
					"AppName" => config('params.tencent.live.appName'),
					"DomainName" => config('params.tencent.live.pushDomain'),
					"StreamName" => $stream
				);
				$req->fromJsonString(json_encode($params));
				$resp = $client->DescribeLiveStreamState($req);
				$res = json_decode($resp->toJsonString(),true);
				echo "用户{$val['uid']}的播流状态是{$res['StreamState']}\r\n";
				if($res['StreamState']=='inactive'){
                    CmfLiveModel::deleteLive($val['uid']);
                    echo Helper::currentTime() . "用户{$val['uid']}的直播间未推流，已被关闭！\r\n";
                    //推送消息通知前端
                    Helper::sendDataToChatServer([
                        'secretKey' => config('params.chat.socketSecretKey'),
                        'type' => 'adminEndLive',
                        'msg' => [
                            'liveuid' => $val['uid'],
                        ]
                    ]);
				}
			}
			catch(TencentCloudSDKException $e) {
				echo $e->getMessage() . "\r\n";
			}
		}

        sleep(120);

    }


    /**
     * 正在进行直播（一分钟抓取一次）
     */
    public function live()
    {
            try {
                $client = new Client();
                $time = Helper::getMillisecond();
                $response = $client->request("GET", config('params.retailLive.live'), [
                    'timeout' => '60',
                    'query' => [
                        'accessKey' => config('params.retailLive.accessKey'),
                        'time' => $time,
                        'authCode' => md5(config('params.retailLive.accessSecret')."-".$time."-" . config('params.retailLive.accessKey'))
                    ]
                ]);
                $data = json_decode($response->getBody()->getContents());

                $liveList = isset($data->data->live) && !empty($data->data->live) ? $data->data->live  : [];
                foreach ($liveList as $live) {
                    if($live->class1 == 1){
                         //足球
                         echo "足球直播地址更新，直播Id：{$live->id}\r\n";
                         $res = CmfSportsFootballMatchModel::updateLiveUrl($live);
                         if(!$res) echo "足球直播地址更新异常！".json_encode($live);
                    }elseif($live->class1 == 2){
                        echo "篮球直播地址更新，直播Id：{$live->id}\r\n";
                        //篮球
                        $res = CmfSportsBasketballMatchModel::updateLiveUrl($live);
                        if(!$res) echo "篮球直播地址更新异常！".json_encode($live);
                    }
                }
            } catch (\Exception $e) {
                echo "直播地址抓取异常！{$e->getMessage()} ， file:{$e->getFile()}，line:{$e->getLine()}，trace:{$e->getTraceAsString()}\r\n";
            }
            sleep(60);
    }

    /**
     * 未来直播（五分钟抓取一次）
     */
//    public function future()
//    {
//        try {
//            $client = new Client();
//            $time = self::getMillisecond();
//            $response = $client->request("GET", config('params.retailLive.future') . "?accessKey=".config('params.retailLive.accessKey')."&time=".$time."&authCode=". md5(config('params.retailLive.accessSecret')."-".$time."-".config('params.retailLive.accessKey')), [
//            ]);
//            $data = json_decode($response->getBody()->getContents());
//            $liveList = isset($data->data->live) && !empty($data->data->live) ? $data->data->live  : [];
//            foreach ($liveList as $live) {
//                if($live->class1 == 1){
//                    //足球
//                    echo "未来足球直播转储，直播Id：{$live->id}\r\n";
//                    $res = CmfSportsFootballMatchModel::insertOrUpdateLive($live, true);
//                    if(!$res){
//                        echo "未来足球存储直播地址异常！".json_encode($live);
//                    }
//                }elseif($live->class1 == 2){
//                    echo "未来篮球直播转储，直播Id：{$live->id}\r\n";
//                    //篮球
//                    $res = CmfSportsBasketballMatchModel::insertOrUpdateLive($live, true);
//                    if(!$res){
//                        echo "未来篮球存储直播地址异常！".json_encode($live);
//                    }
//                }
//            }
//        } catch (\Exception $e) {
//            echo "未来直播抓取异常！{$e->getMessage()} ， file:{$e->getFile()}，line:{$e->getLine()}，trace:{$e->getTraceAsString()}\r\n";
//        }
//        sleep(300);
//    }


}
