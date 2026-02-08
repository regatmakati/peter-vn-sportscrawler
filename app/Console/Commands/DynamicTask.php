<?php

namespace App\Console\Commands;

use App\Helpers\RedisKeyMap;
use App\Log\Log;
use App\Models\CmfDynamicCommentsModel;
use App\Models\CmfDynamicModel;
use App\Models\CmfOptionModel;
use App\Models\CmfSensitiveModel;
use App\Models\CmfUserModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Live\V20180801\LiveClient;
use TencentCloud\Live\V20180801\Models\DescribeLiveStreamStateRequest;


class DynamicTask extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'DynamicTask:handle {--action=} {--debug=}';

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
                    case 'updateDynamic':    //更新动态
                        $err = "更新动态任务异常！";
                        self::updateDynamic();
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
     * 更新动态
     */
    public function updateDynamic()
    {
        $table_name = "cmf_dynamic_" . date("Ymd");
        $config = json_decode(CmfOptionModel::select(['option_value'])->where(['option_name' => "site_info"])->value('option_value'));
        $sql_name = $config->oss_site . "/sql_dynamic/" . date("Ymd") . ".sql";
        if (date("H") != "05") {
            echo "更新动态时间未到！\r\n";
            sleep(60);
            return;
        }
        $fileExist = file_get_contents($sql_name);
        if (!$fileExist) {
            echo date("Ymd") . "新动态文件不存在！\r\n";
        } else {
            if (!\Illuminate\Support\Facades\Schema::hasTable($table_name)) {
                //备份数据库
                $sqlStr = "CREATE TABLE {$table_name} LIKE cmf_dynamic";
                DB::select($sqlStr);
                $sqlStr2 = "INSERT INTO {$table_name} SELECT * FROM cmf_dynamic";
                DB::select($sqlStr2);
                if (\Illuminate\Support\Facades\Schema::hasTable($table_name)) {
                    echo date("Ymd") . "数据库备份结束！\r\n";
                    //删除七天前的动态
                    CmfDynamicModel::deleteDynamic();
                    echo date("Ymd", time() - 7 * 24 * 3600) . "之前数据删除结束！\r\n";
                    //引入新的数据
                    DB::unprepared(file_get_contents($sql_name));
                    $weibo = DB::select("select * from weibo");
                    if ($weibo) {
                        $banWordList = json_decode(Redis::get(RedisKeyMap::getSensitiveWords()),true);
                        if(empty($banWordList)){
                            $banWordList = CmfSensitiveModel::getSensitive();
                            Redis::setex(RedisKeyMap::getSensitiveWords(), config('params.cache.ttl'), json_encode($banWordList));
                        }
                        foreach ($weibo as $key => $value) {
                            $uids = CmfUserModel::randUser();
                            if (!$uids) {
                                $insert['uid'] = 29;//暂时写死动态的用户ID
                            } else {
                                $insert['uid'] = $uids['id'];//暂时写死动态的用户ID
                            }
                            $text = CmfDynamicModel::filter($banWordList, $value->text);
                            $insert['title'] = mb_substr($text, 0, 254, 'utf-8');
                            $insert['thumb'] = str_replace(",", ";", $value->pics);
                            $insert['href'] = $value->video_url;
                            if ($value->video_url) {
                                continue;//由于很多视频url无法访问，暂不保留
                                $insert['type'] = CmfDynamicModel::VIDEO_TYPE;//视频+文字
                            } elseif ($value->pics) {
                                $insert['type'] = CmfDynamicModel::IMAGE_TYPE;//图片+文字
                            } else {
                                $insert['type'] = CmfDynamicModel::TEXT_TYPE;//纯文字
                            }
                            $insert['status'] = CmfDynamicModel::STATUS_PASS;
                            $insert['addtime'] = strtotime($value->created_at);
                            if(!empty($value->comments)){
                                $comments = explode(",",$value->comments);
                                $comments=array_filter($comments);
                                $insert['comments'] = count($comments);
                            }

                            $dynamicId = CmfDynamicModel::insertGetId($insert);
                            if(!$dynamicId){
                                continue;
                            }else{
                                if(!$value->comments_count || empty($value->comments)){
                                    continue;
                                }else{
                                    $commentAll = [];
                                    foreach ($comments as $k=>$v){
                                        if(!$v){
                                            continue;
                                        }else{
                                            $uids = CmfUserModel::randUser();
                                            if (!$uids) {
                                                $comment['uid'] = 29;//暂时写死动态的用户ID
                                            } else {
                                                $comment['uid'] = $uids['id'];//暂时写死动态的用户ID
                                            }
                                            $comment['dynamicid'] = $dynamicId;
                                            $content = CmfDynamicCommentsModel::filter($banWordList,$v);
                                            $comment['content'] = $content;
                                            $comment['addtime'] = strtotime($value->created_at)+rand(0,10000);
                                            $commentAll []= $comment;
                                        }
                                    }
                                    CmfDynamicCommentsModel::insertAll($commentAll);
                                }
                            }
                        }
                        echo "新数引入据结束！\r\n";
                    }
                } else {
                    echo date("Ymd") . "数据库备份失败！\r\n";
                }
            } else {
                echo date("Ymd") . "新动态已导入！\r\n";
            }
        }
        sleep(60);
    }


}
