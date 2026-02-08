<?php 

use \Workerman\Worker;
use \Workerman\Lib\Timer;
require_once __DIR__ . '/vendor/autoload.php';


//获取数据库配置        
function config()
{
    $database = require_once __DIR__ . '/../data/config/database.php';
    return $database;
}



$task = new Worker();
$task->onWorkerStart = function($task)
{

    global $database;
    global $team_list_offset;
    $team_list_offset = 0;
    $database = config();
    global $db;
    $db  = new \Workerman\MySQL\Connection($database['hostname'], $database['hostport'], $database['username'], $database['password'], $database['database']);


//    战队列表 60秒更新10条
    Timer::add(60,function(){
        global $db;
        global $team_list_offset;
        $offset = $team_list_offset;
        $team_list_offset += 10;
        $url = "http://esportsapi.feijing88.com/data-service/lol/team/list?offset=".$offset."&limit=10&version=2";
        list($msec, $sec) = explode(' ', microtime());
        $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        $header =array(
            "Content-Type:application/json;charset=utf-8",
            "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
            "Accept-ClientTime:" .$microtime,
            "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/team/list"))
        );

        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $tmpInfo = curl_exec($curl);
        curl_close($curl);
        $return = json_decode($tmpInfo,true);
        $return_data = $return['data'];
        //战队基本信息
        $team_ids = array_column($return_data,'team_id');
        $team_ids_str = implode(",",$team_ids);
        $url = "http://esportsapi.feijing88.com/data-service/lol/team/basic_info?team_id={$team_ids_str}&version=2";
        list($msec, $sec) = explode(' ', microtime());
        $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        $header =array(
            "Content-Type:application/json;charset=utf-8",
            "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
            "Accept-ClientTime:" .$microtime,
            "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/team/basic_info"))
        );

        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $tmpInfo = curl_exec($curl);
        curl_close($curl);
        $return = json_decode($tmpInfo,true);
        $return_data2 = $return['data'];

            if(count($return_data) == 0){
                $team_list_offset = 0;
                return;
            }
            foreach ($return_data as $key=>$value){
                foreach ($return_data2 as $k=>$v){
                    foreach ($v['players'] as $pk=>$pv){
                        $exist = $db->select('id')->from('cmf_lol_player')->where("player_id = ".$pv['player_id'])->row();
                        if(!$exist){
                            $insert = [
                                'player_id'=>$pv['player_id'],
                                'nick_name'=>$pv['nick_name'],
                                'real_name'=>$pv['real_name'],
                                'position'=>$pv['position'],
                                'country'=>$pv['country'],
                                'avatar'=>$pv['avatar'],
                                'team_id'=>$pv['team_id'],
                                'introduction'=>$pv['introduction'],
                                'addtime'=>time(),
                            ];
                            $insert_id = $db->insert('cmf_lol_player')->cols($insert)->query();
                        }else{
                            $update = [
                                'player_id'=>$pv['player_id'],
                                'nick_name'=>$pv['nick_name'],
                                'real_name'=>$pv['real_name'],
                                'position'=>$pv['position'],
                                'country'=>$pv['country'],
                                'avatar'=>$pv['avatar'],
                                'team_id'=>$pv['team_id'],
                                'introduction'=>$pv['introduction'],
                                'addtime'=>time(),
                            ];
                            $row_count = $db->update('cmf_lol_player')->cols($update)->where('id='.$exist['id'])->query();
                        }
                    }
                    if($value['team_id'] == $v['team_id']){
                        $value['team_data'] = $v;
                        break;
                    }
                }
                $exist = $db->select('id')->from('cmf_lol_team')->where("team_id = ".$value['team_id'])->row();
                if(!$exist){
                    $insert = [
                        'team_id'=>$value['team_id'],
                        'name'=>$value['name'],
                        'name_en'=>$value['name_en'],
                        'logo'=>$value['logo'],
                        'short_name'=>$value['short_name'],
                        'introduction'=>$value['introduction'],
                        'players'=>json_encode($value['team_data']['players']),
                        'awards'=>json_encode($value['team_data']['awards']),
                        'addtime'=>time(),
                    ];
                    $insert_id = $db->insert('cmf_lol_team')->cols($insert)->query();
                }else{
                    $update = [
                        'team_id'=>$value['team_id'],
                        'name'=>$value['name'],
                        'name_en'=>$value['name_en'],
                        'logo'=>$value['logo'],
                        'short_name'=>$value['short_name'],
                        'introduction'=>$value['introduction'],
                        'players'=>json_encode($value['team_data']['players']),
                        'awards'=>json_encode($value['team_data']['awards']),
                        'addtime'=>time(),
                    ];
                    $row_count = $db->update('cmf_lol_team')->cols($update)->where('id='.$exist['id'])->query();
                }
            }

    });






    //战队排行 60秒更新所有
    Timer::add(60,function(){
        global $db;
        $url = "http://esportsapi.feijing88.com/data-service/lol/top/team";
        list($msec, $sec) = explode(' ', microtime());
        $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        $header =array(
            "Content-Type:application/json;charset=utf-8",
            "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
            "Accept-ClientTime:" .$microtime,
            "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/top/team"))
        );

        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $tmpInfo = curl_exec($curl);
        curl_close($curl);
        $return = json_decode($tmpInfo,true);
        $return_data = $return['data'];
        if($return['code'] == 200){
            foreach ($return_data as $key=>$value){
                $exist = $db->select('id')->from('cmf_lol_team_top')->where("`rank` = ".$value['rank'])->row();
                if(!$exist){
                    $insert = [
                        'team_id'=>$value['team_id'],
                        'team_name'=>$value['team_name'],
                        'team_name_en'=>$value['team_name_en'],
                        'team_short_name'=>$value['team_short_name'],
                        'logo'=>$value['logo'],
                        'rating'=>$value['rating'],
                        'rank'=>$value['rank'],
                        'country'=>$value['country'],
                        'addtime'=>time(),
                    ];
                    $insert_id = $db->insert('cmf_lol_team_top')->cols($insert)->query();
                }else{
                    $update = [
                        'team_id'=>$value['team_id'],
                        'team_name'=>$value['team_name'],
                        'team_name_en'=>$value['team_name_en'],
                        'team_short_name'=>$value['team_short_name'],
                        'logo'=>$value['logo'],
                        'rating'=>$value['rating'],
                        'rank'=>$value['rank'],
                        'country'=>$value['country'],
                        'addtime'=>time(),
                    ];
                    $row_count = $db->update('cmf_lol_team_top')->cols($update)->where('id='.$exist['id'])->query();
                }
            }
        }
    });




};
// 运行worker
Worker::runAll();