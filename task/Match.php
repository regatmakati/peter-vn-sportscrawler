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
    global $match_recently_offset;
    global $end_offset;
    global $final_score_offset;
    global $match_final_offset;
    global $match_limit;
    global $fifteen_day;
    $match_limit = 0;
    $match_recently_offset = 0;
    $end_offset = 0;
    $final_score_offset = 0;
    $match_final_offset = 0;
    $fifteen_day = date("Y-m-d",strtotime("-15 day"));
    $database = config();
    global $db;
    $db  = new \Workerman\MySQL\Connection($database['hostname'], $database['hostport'], $database['username'], $database['password'], $database['database']);


    //比赛列表（未开赛）
    Timer::add(60,function(){
        global $db;
        global $match_recently_offset;
        $offset = $match_recently_offset;
        $match_recently_offset += 10;
        $url = "http://esportsapi.feijing88.com/data-service/lol/match/recently?offset=".$offset."&limit=10&version=2";
        list($msec, $sec) = explode(' ', microtime());
        $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        $header =array(
            "Content-Type:application/json;charset=utf-8",
            "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
            "Accept-ClientTime:" .$microtime,
            "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/match/recently"))
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
        if(count($return_data) == 0){
            $match_recently_offset = 0;
            return;
        }
        $match_ids = array_column($return_data,'match_id');
        $match_ids_str = implode(",",$match_ids);
        //赛事基本信息
        $url = "http://esportsapi.feijing88.com/data-service/lol/match/basic_info?match_id={$match_ids_str}&version=2";
        list($msec, $sec) = explode(' ', microtime());
        $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        $header =array(
            "Content-Type:application/json;charset=utf-8",
            "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
            "Accept-ClientTime:" .$microtime,
            "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/match/basic_info"))
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

        //赛事直播地址
        $url = "http://esportsapi.feijing88.com/data-service/lol/match/live_video?match_id={$match_ids_str}&version=2";
        list($msec, $sec) = explode(' ', microtime());
        $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        $header =array(
            "Content-Type:application/json;charset=utf-8",
            "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
            "Accept-ClientTime:" .$microtime,
            "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/match/live_video"))
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
        $return_data3 = $return['data'];
        foreach ($return_data3 as $lk=>$lv){
            $exist = $db->select('id')->from('cmf_lol_match_live')->where("match_id = ".$lv['match_id']." and name = '".$lv['name']."'")->row();
            if(!$exist){
                $insert = [
                    'match_id'=>$lv['match_id'],
                    'name'=>$lv['name'],
                    'url'=>$lv['url'],
                    'name_en'=>$lv['name_en'],
                    'addtime'=>time(),
                ];
                $insert_id = $db->insert('cmf_lol_match_live')->cols($insert)->query();
            }else{
                $update = [
                    'match_id'=>$lv['match_id'],
                    'name'=>$lv['name'],
                    'url'=>$lv['url'],
                    'name_en'=>$lv['name_en'],
                    'addtime'=>time(),
                ];
                $row_count = $db->update('cmf_lol_match_live')->cols($update)->where('id='.$exist['id'])->query();
            }
        }
        //早盘指数
        $url = "http://esportsapi.feijing88.com/data-service/lol/match/bet_info?match_id={$match_ids_str}&version=2";
        list($msec, $sec) = explode(' ', microtime());
        $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        $header =array(
            "Content-Type:application/json;charset=utf-8",
            "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
            "Accept-ClientTime:" .$microtime,
            "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/match/bet_info"))
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
        $return_data4 = $return['data'];
        foreach ($return_data4 as $bkey=>$bvalue){
            $b_exist = $db->select('id')->from('cmf_lol_match_bet_info')->where("bet_id = '".$bvalue['bet_id']."'")->row();
            if(!$b_exist){
                $insert = [
                    'bet_id'=>$bvalue['bet_id'],
                    'title'=>$bvalue['title'],
                    'end_time'=>sprintf('%.0f', $bvalue['end_time']/1000),
                    'status'=>$bvalue['status'],
                    'result_id'=>$bvalue['result_id'],
                    'match_id'=>$bvalue['match_id'],
                    'source'=>$bvalue['source'],
                    'options'=>json_encode($bvalue['options']),
                    'bet_type'=>$bvalue['bet_type'],
                    'board_num'=>$bvalue['board_num'],
                    'type_desc'=>$bvalue['type_desc'],
                    'handicap'=>empty($bvalue['handicap'])?"":$bvalue['handicap'],
                    'addtime'=>time(),
                ];
                $insert_id = $db->insert('cmf_lol_match_bet_info')->cols($insert)->query();

            }else{
                $update = [
                    'bet_id'=>$bvalue['bet_id'],
                    'title'=>$bvalue['title'],
                    'end_time'=>sprintf('%.0f', $bvalue['end_time']/1000),
                    'status'=>$bvalue['status'],
                    'result_id'=>$bvalue['result_id'],
                    'match_id'=>$bvalue['match_id'],
                    'source'=>$bvalue['source'],
                    'options'=>json_encode($bvalue['options']),
                    'bet_type'=>$bvalue['bet_type'],
                    'board_num'=>$bvalue['board_num'],
                    'type_desc'=>$bvalue['type_desc'],
                    'handicap'=>empty($bvalue['handicap'])?"":$bvalue['handicap'],
                    'addtime'=>time(),
                ];
                $row_count = $db->update('cmf_lol_match_bet_info')->cols($update)->where('id='.$b_exist['id'])->query();

            }
        }

        foreach ($return_data as $key=>$value){
            //前瞻分析
            $url = "http://esportsapi.feijing88.com/data-service/lol/match/analysis?match_id={$value['match_id']}&version=2";
            list($msec, $sec) = explode(' ', microtime());
            $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
            $header =array(
                "Content-Type:application/json;charset=utf-8",
                "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
                "Accept-ClientTime:" .$microtime,
                "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/match/analysis"))
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
            if(count($return['data'])){
                $return_data3 = $return['data'][0];
            }else{
                $return_data = [];
            }
            $m_a_exist = $db->select('id')->from('cmf_lol_match_analysis')->where("match_id = ".$value['match_id'])->row();
            if(!$m_a_exist){
                $insert = [
                    'league_id'=>$return_data3['league_id'],
                    'match_id'=>$return_data3['match_id'],
                    'range_type'=>$return_data3['range_type'],
                    'battle_stats'=>empty($return_data3['battle_stats'])?"":json_encode($return_data3['battle_stats']),
                    'team_a_id'=>$return_data3['team_a_id'],
                    'team_a_name'=>$return_data3['team_a_name'],
                    'team_a_name_en'=>$return_data3['team_a_name_en'],
                    'team_a_short_name'=>$return_data3['team_a_short_name'],
                    'team_b_id'=>$return_data3['team_b_id'],
                    'team_b_name'=>$return_data3['team_b_name'],
                    'team_b_name_en'=>$return_data3['team_b_name_en'],
                    'team_b_short_name'=>$return_data3['team_b_short_name'],
                    'team_a_recent_stats'=>empty($return_data3['team_a_recent_stats'])?"":json_encode($return_data3['team_a_recent_stats']),
                    'team_b_recent_stats'=>empty($return_data3['team_b_recent_stats'])?"":json_encode($return_data3['team_b_recent_stats']),
                    'team_a_hero_stats'=>empty($return_data3['team_a_hero_stats'])?"":json_encode($return_data3['team_a_hero_stats']),
                    'team_b_hero_stats'=>empty($return_data3['team_b_hero_stats'])?"":json_encode($return_data3['team_b_hero_stats']),
                    'team_a_player_stats'=>empty($return_data3['team_a_player_stats'])?"":json_encode($return_data3['team_a_player_stats']),
                    'team_b_player_stats'=>empty($return_data3['team_b_player_stats'])?"":json_encode($return_data3['team_b_player_stats']),
                    'addtime'=>time(),

                ];
                $insert_id = $db->insert('cmf_lol_match_analysis')->cols($insert)->query();
            }else{
                $update = [
                    'league_id'=>$return_data3['league_id'],
                    'match_id'=>$return_data3['match_id'],
                    'range_type'=>$return_data3['range_type'],
                    'battle_stats'=>empty($return_data3['battle_stats'])?"":json_encode($return_data3['battle_stats']),
                    'team_a_id'=>$return_data3['team_a_id'],
                    'team_a_name'=>$return_data3['team_a_name'],
                    'team_a_name_en'=>$return_data3['team_a_name_en'],
                    'team_a_short_name'=>$return_data3['team_a_short_name'],
                    'team_b_id'=>$return_data3['team_b_id'],
                    'team_b_name'=>$return_data3['team_b_name'],
                    'team_b_name_en'=>$return_data3['team_b_name_en'],
                    'team_b_short_name'=>$return_data3['team_b_short_name'],
                    'team_a_recent_stats'=>empty($return_data3['team_a_recent_stats'])?"":json_encode($return_data3['team_a_recent_stats']),
                    'team_b_recent_stats'=>empty($return_data3['team_b_recent_stats'])?"":json_encode($return_data3['team_b_recent_stats']),
                    'team_a_hero_stats'=>empty($return_data3['team_a_hero_stats'])?"":json_encode($return_data3['team_a_hero_stats']),
                    'team_b_hero_stats'=>empty($return_data3['team_b_hero_stats'])?"":json_encode($return_data3['team_b_hero_stats']),
                    'team_a_player_stats'=>empty($return_data3['team_a_player_stats'])?"":json_encode($return_data3['team_a_player_stats']),
                    'team_b_player_stats'=>empty($return_data3['team_b_player_stats'])?"":json_encode($return_data3['team_b_player_stats']),
                    'addtime'=>time(),
                ];
                $row_count = $db->update('cmf_lol_match_analysis')->cols($update)->where('id='.$m_a_exist['id'])->query();
            }

            $exist = $db->select('id')->from('cmf_lol_match')->where("match_id = ".$value['match_id'])->row();
            if(count($match_ids) == 1){
                $value['match_data'] = $return_data2;
            }else{
                foreach ($return_data2 as $k=>$v){
                    if($value['match_id'] == $v['match_id']){
                        $value['match_data'] = $v;
                        break;
                    }
                }
            }
            if(!$exist){
                $insert = [
                    'match_id'=>$value['match_id'],
                    'league_id'=>$value['league_id'],
                    'league'=>json_encode($value['match_data']['league']),
                    'status'=>$value['status'],
                    'start_time'=>sprintf('%.0f', $value['start_time']/1000),
                    'address'=>$value['address'],
                    'round_name'=>$value['round_name'],
                    'round_son_name'=>$value['round_son_name'],
                    'bo'=>$value['bo'],
                    'battle_ids'=>json_encode($value['battle_ids']),
                    'battle_list'=>json_encode($value['match_data']['battle_list']),
                    'team_a_score'=>$value['team_a_score'],
                    'team_a_id'=>$value['team_a_id'],
                    'team_a'=>empty($value['match_data']['team_a'])?"":json_encode($value['match_data']['team_a']),
                    'team_b_score'=>$value['team_b_score'],
                    'team_b_id'=>$value['team_b_id'],
                    'team_b'=>empty($value['match_data']['team_b'])?"":json_encode($value['match_data']['team_b']),
                    'addtime'=>time(),
                ];
                $insert_id = $db->insert('cmf_lol_match')->cols($insert)->query();

            }else{
                $update = [
                    'match_id'=>$value['match_id'],
                    'league_id'=>$value['league_id'],
                    'league'=>json_encode($value['match_data']['league']),
                    'status'=>$value['status'],
                    'start_time'=>sprintf('%.0f', $value['start_time']/1000),
                    'address'=>$value['address'],
                    'round_name'=>$value['round_name'],
                    'round_son_name'=>$value['round_son_name'],
                    'bo'=>$value['bo'],
                    'battle_ids'=>json_encode($value['battle_ids']),
                    'battle_list'=>json_encode($value['match_data']['battle_list']),
                    'team_a_score'=>$value['team_a_score'],
                    'team_a_id'=>$value['team_a_id'],
                    'team_a'=>empty($value['match_data']['team_a'])?"":json_encode($value['match_data']['team_a']),
                    'team_b_score'=>$value['team_b_score'],
                    'team_b_id'=>$value['team_b_id'],
                    'team_b'=>empty($value['match_data']['team_b'])?"":json_encode($value['match_data']['team_b']),
                    'addtime'=>time(),
                ];
                $row_count = $db->update('cmf_lol_match')->cols($update)->where('id='.$exist['id'])->query();
            }

        }
    });

    //比赛列表（当天结束）
    Timer::add(60,function(){
        global $db;
        global $end_offset;
        $offset = $end_offset;
        $end_offset += 10;
        $url = "http://esportsapi.feijing88.com/data-service/lol/match/today/end?offset=".$offset."&limit=10&version=2";
        list($msec, $sec) = explode(' ', microtime());
        $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        $header =array(
            "Content-Type:application/json;charset=utf-8",
            "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
            "Accept-ClientTime:" .$microtime,
            "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/match/today/end"))
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
		var_dump($tmpInfo);
		
        $return = json_decode($tmpInfo,true);
        $return_data = $return['data'];
        if(count($return_data) == 0){
            $end_offset = 0;
            return;
        }
        $match_ids = array_column($return_data,'match_id');
        $match_ids_str = implode(",",$match_ids);
        //赛事基本信息
        $url = "http://esportsapi.feijing88.com/data-service/lol/match/basic_info?match_id={$match_ids_str}&version=2";
        list($msec, $sec) = explode(' ', microtime());
        $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        $header =array(
            "Content-Type:application/json;charset=utf-8",
            "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
            "Accept-ClientTime:" .$microtime,
            "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/match/basic_info"))
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
        foreach ($return_data as $key=>$value){
            foreach ($value['battle_ids'] as $bkey=>$bvalue){
                //文字直播
                $url1 = "http://esportsapi.feijing88.com/data-service/lol/match/battle_log?battle_id=".$bvalue;
                list($msec, $sec) = explode(' ', microtime());
                $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
                $header =array(
                    "Content-Type:application/json;charset=utf-8",
                    "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
                    "Accept-ClientTime:" .$microtime,
                    "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/match/battle_log"))
                );

                $curl = curl_init(); // 启动一个CURL会话
                curl_setopt($curl, CURLOPT_URL, $url1);
                curl_setopt($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
                curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
                $tmpInfo = curl_exec($curl);
                curl_close($curl);
                $return = json_decode($tmpInfo,true);
                $return_data3 = $return['data'];
                foreach ($return_data3 as $lkey=>$lvalue){
                    $m_b_exist = $db->select('id')->from('cmf_lol_match_battle_log')->where("log_id = '".$lvalue['log_id']."'")->row();
                    if(!$m_b_exist){
                        $insert = [
                            'battle_id'=>$lvalue['battle_id'],
                            'duration'=>$lvalue['duration'],
                            'log_id'=>$lvalue['log_id'],
                            'killed_type'=>$lvalue['killed_type'],
                            'killed_object_id'=>$lvalue['killed_object_id'],
                            'killed_object_name'=>$lvalue['killed_object_name'],
                            'killed_object_name_en'=>$lvalue['killed_object_name_en'],
                            'killed_object_logo'=>$lvalue['killed_object_logo'],
                            'killed_player_id'=>$lvalue['killed_player_id'],
                            'killed_player_name'=>$lvalue['killed_player_name'],
                            'killed_player_avatar'=>$lvalue['killed_player_avatar'],
                            'killer_type'=>$lvalue['killer_type'],
                            'killer_object_id'=>$lvalue['killer_object_id'],
                            'killer_object_name'=>$lvalue['killer_object_name'],
                            'killer_object_name_en'=>$lvalue['killer_object_name_en'],
                            'killer_object_logo'=>$lvalue['killer_object_logo'],
                            'killer_player_id'=>$lvalue['killer_player_id'],
                            'killer_player_name'=>$lvalue['killer_player_name'],
                            'killer_player_avatar'=>$lvalue['killer_player_avatar'],
                            'addtime'=>time(),
                        ];
                        $insert_id = $db->insert('cmf_lol_match_battle_log')->cols($insert)->query();

                    }else{
                        $update = [
                            'battle_id'=>$lvalue['battle_id'],
                            'duration'=>$lvalue['duration'],
                            'log_id'=>$lvalue['log_id'],
                            'killed_type'=>$lvalue['killed_type'],
                            'killed_object_id'=>$lvalue['killed_object_id'],
                            'killed_object_name'=>$lvalue['killed_object_name'],
                            'killed_object_name_en'=>$lvalue['killed_object_name_en'],
                            'killed_object_logo'=>$lvalue['killed_object_logo'],
                            'killed_player_id'=>$lvalue['killed_player_id'],
                            'killed_player_name'=>$lvalue['killed_player_name'],
                            'killed_player_avatar'=>$lvalue['killed_player_avatar'],
                            'killer_type'=>$lvalue['killer_type'],
                            'killer_object_id'=>$lvalue['killer_object_id'],
                            'killer_object_name'=>$lvalue['killer_object_name'],
                            'killer_object_name_en'=>$lvalue['killer_object_name_en'],
                            'killer_object_logo'=>$lvalue['killer_object_logo'],
                            'killer_player_id'=>$lvalue['killer_player_id'],
                            'killer_player_name'=>$lvalue['killer_player_name'],
                            'killer_player_avatar'=>$lvalue['killer_player_avatar'],
                            'addtime'=>time(),
                        ];
                        $row_count = $db->update('cmf_lol_match_battle_log')->cols($update)->where('id='.$m_b_exist['id'])->query();
                    }
                }



                //比分事件（实时/赛后）
                $url2 = "http://esportsapi.feijing88.com/data-service/lol/match/live_battle?battle_id=".$bvalue."&version=2";
                list($msec, $sec) = explode(' ', microtime());
                $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
                $header =array(
                    "Content-Type:application/json;charset=utf-8",
                    "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
                    "Accept-ClientTime:" .$microtime,
                    "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/match/live_battle"))
                );

                $curl = curl_init(); // 启动一个CURL会话
                curl_setopt($curl, CURLOPT_URL, $url2);
                curl_setopt($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
                curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
                $tmpInfo = curl_exec($curl);
                curl_close($curl);
                $return = json_decode($tmpInfo,true);
                $return_data3 = $return['data'];
                $l_b_exist = $db->select('id')->from('cmf_lol_match_live_battle')->where("battle_id = ".$return_data3['battle_id'])->row();
                if(!$l_b_exist){
                    $insert = [
                        'battle_id'=>$return_data3['battle_id'],
                        'match_id'=>$return_data3['match_id'],
                        'duration'=>$return_data3['duration'],
                        'index'=>$return_data3['index'],
                        'economic_diff'=>empty($return_data3['economic_diff'])?"":json_encode($return_data3['economic_diff']),
                        'xp_diff'=>empty($return_data3['xp_diff'])?"":json_encode($return_data3['xp_diff']),
                        'creep_score_diff'=>empty($return_data3['creep_score_diff'])?"":json_encode($return_data3['creep_score_diff']),
                        'start_time'=>sprintf('%.0f', $return_data3['start_time']/1000),
                        'player_stats'=>$return_data3['player_stats']?json_encode($return_data3['player_stats']):"",
                        'team_stats'=>$return_data3['team_stats']?json_encode($return_data3['team_stats']):"",
                        'addtime'=>time(),
                    ];
                    $insert_id = $db->insert('cmf_lol_match_live_battle')->cols($insert)->query();
                }else{
                    $update = [
                        'battle_id'=>$return_data3['battle_id'],
                        'match_id'=>$return_data3['match_id'],
                        'duration'=>$return_data3['duration'],
                        'index'=>$return_data3['index'],
                        'economic_diff'=>empty($return_data3['economic_diff'])?"":json_encode($return_data3['economic_diff']),
                        'xp_diff'=>empty($return_data3['xp_diff'])?"":json_encode($return_data3['xp_diff']),
                        'creep_score_diff'=>empty($return_data3['creep_score_diff'])?"":json_encode($return_data3['creep_score_diff']),
                        'start_time'=>sprintf('%.0f', $return_data3['start_time']/1000),
                        'player_stats'=>$return_data3['player_stats']?json_encode($return_data3['player_stats']):"",
                        'team_stats'=>$return_data3['team_stats']?json_encode($return_data3['team_stats']):"",
                        'addtime'=>time(),
                    ];
                    $row_count = $db->update('cmf_lol_match_live_battle')->cols($update)->where('id='.$l_b_exist['id'])->query();

                }
            }



            $exist = $db->select('id')->from('cmf_lol_match')->where("match_id = ".$value['match_id'])->row();
            if(count($match_ids) == 1){
                $value['match_data'] = $return_data2;
            }else{
                foreach ($return_data2 as $k=>$v){
                    if($value['match_id'] == $v['match_id']){
                        $value['match_data'] = $v;
                        break;
                    }
                }
            }


            if(!$exist){
                $insert = [
                    'match_id'=>$value['match_id'],
                    'league_id'=>$value['league_id'],
                    'league'=>json_encode($value['match_data']['league']),
                    'status'=>$value['status'],
                    'start_time'=>sprintf('%.0f', $value['start_time']/1000),
                    'address'=>$value['address'],
                    'round_name'=>$value['round_name'],
                    'round_son_name'=>$value['round_son_name'],
                    'bo'=>$value['bo'],
                    'battle_ids'=>json_encode($value['battle_ids']),
                    'battle_list'=>json_encode($value['match_data']['battle_list']),
                    'team_a_score'=>$value['team_a_score'],
                    'team_a_id'=>$value['team_a_id'],
                    'team_a'=>json_encode($value['match_data']['team_a']),
                    'team_b_score'=>$value['team_b_score'],
                    'team_b_id'=>$value['team_b_id'],
                    'team_b'=>json_encode($value['match_data']['team_b']),
                    'addtime'=>time(),
                ];
                $insert_id = $db->insert('cmf_lol_match')->cols($insert)->query();

            }else{
                $update = [
                    'match_id'=>$value['match_id'],
                    'league_id'=>$value['league_id'],
                    'league'=>json_encode($value['match_data']['league']),
                    'status'=>$value['status'],
                    'start_time'=>sprintf('%.0f', $value['start_time']/1000),
                    'address'=>$value['address'],
                    'round_name'=>$value['round_name'],
                    'round_son_name'=>$value['round_son_name'],
                    'bo'=>$value['bo'],
                    'battle_ids'=>json_encode($value['battle_ids']),
                    'battle_list'=>json_encode($value['match_data']['battle_list']),
                    'team_a_score'=>$value['team_a_score'],
                    'team_a_id'=>$value['team_a_id'],
                    'team_a'=>json_encode($value['match_data']['team_a']),
                    'team_b_score'=>$value['team_b_score'],
                    'team_b_id'=>$value['team_b_id'],
                    'team_b'=>json_encode($value['match_data']['team_b']),
                    'addtime'=>time(),
                ];
                $row_count = $db->update('cmf_lol_match')->cols($update)->where('id='.$exist['id'])->query();
            }

        }
    });

    //比赛列表（历史库）
    Timer::add(60,function(){
        global $db;
        global $final_score_offset;
        global $fifteen_day;
        $offset = $final_score_offset;
        $final_score_offset += 10;
        $url = "http://esportsapi.feijing88.com/data-service/lol/match/final_score?date=$fifteen_day&offset=".$offset."&limit=10&version=2";
        list($msec, $sec) = explode(' ', microtime());
        $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        $header =array(
            "Content-Type:application/json;charset=utf-8",
            "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
            "Accept-ClientTime:" .$microtime,
            "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/match/final_score"))
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
        var_dump($fifteen_day);
        if(count($return_data) == 0 ){
            if($fifteen_day == date("Y-m-d")){
                $final_score_offset = 0;
                $fifteen_day = date("Y-m-d",strtotime("-15 day"));
                return;
            }else{
                $final_score_offset = 0;
                $fifteen_day =  date("Y-m-d",strtotime("+1 day",strtotime($fifteen_day)));
                return;
            }
        }

        $match_ids = array_column($return_data,'match_id');
        $match_ids_str = implode(",",$match_ids);
        //赛事基本信息
        $url = "http://esportsapi.feijing88.com/data-service/lol/match/basic_info?match_id={$match_ids_str}&version=2";
        list($msec, $sec) = explode(' ', microtime());
        $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        $header =array(
            "Content-Type:application/json;charset=utf-8",
            "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
            "Accept-ClientTime:" .$microtime,
            "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/match/basic_info"))
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

        foreach ($return_data as $key=>$value){


            //比分事件（实时/赛后）
            foreach ($value['battle_ids'] as $bkey=>$bvalue){
                $url = "http://esportsapi.feijing88.com/data-service/lol/match/live_battle?battle_id=".$bvalue."&version=2";
                list($msec, $sec) = explode(' ', microtime());
                $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
                $header =array(
                    "Content-Type:application/json;charset=utf-8",
                    "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
                    "Accept-ClientTime:" .$microtime,
                    "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/match/live_battle"))
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
                $return_data3 = $return['data'];
                $l_b_exist = $db->select('id')->from('cmf_lol_match_live_battle')->where("battle_id = ".$return_data3['battle_id'])->row();
                if(!$l_b_exist){
                    $insert = [
                        'battle_id'=>$return_data3['battle_id'],
                        'match_id'=>$return_data3['match_id'],
                        'duration'=>$return_data3['duration'],
                        'index'=>$return_data3['index'],
                        'economic_diff'=>empty($return_data3['economic_diff'])?"":json_encode($return_data3['economic_diff']),
                        'xp_diff'=>empty($return_data3['xp_diff'])?"":json_encode($return_data3['xp_diff']),
                        'creep_score_diff'=>empty($return_data3['creep_score_diff'])?"":json_encode($return_data3['creep_score_diff']),
                        'start_time'=>sprintf('%.0f', $return_data3['start_time']/1000),
                        'player_stats'=>empty($return_data3['player_stats'])?"":json_encode($return_data3['player_stats']),
                        'team_stats'=>empty($return_data3['team_stats'])?"":json_encode($return_data3['team_stats']),
                        'addtime'=>time(),
                    ];
                    $insert_id = $db->insert('cmf_lol_match_live_battle')->cols($insert)->query();
                }else{
                    $update = [
                        'battle_id'=>$return_data3['battle_id'],
                        'match_id'=>$return_data3['match_id'],
                        'duration'=>$return_data3['duration'],
                        'index'=>$return_data3['index'],
                        'economic_diff'=>empty($return_data3['economic_diff'])?"":json_encode($return_data3['economic_diff']),
                        'xp_diff'=>empty($return_data3['xp_diff'])?"":json_encode($return_data3['xp_diff']),
                        'creep_score_diff'=>empty($return_data3['creep_score_diff'])?"":json_encode($return_data3['creep_score_diff']),
                        'start_time'=>sprintf('%.0f', $return_data3['start_time']/1000),
                        'player_stats'=>empty($return_data3['player_stats'])?"":json_encode($return_data3['player_stats']),
                        'team_stats'=>empty($return_data3['team_stats'])?"":json_encode($return_data3['team_stats']),
                        'addtime'=>time(),
                    ];
                    $row_count = $db->update('cmf_lol_match_live_battle')->cols($update)->where('id='.$l_b_exist['id'])->query();

                }
            }


            //文字直播
            foreach ($value['battle_ids'] as $bkey=>$bvalue){
                $url = "http://esportsapi.feijing88.com/data-service/lol/match/battle_log?battle_id=".$bvalue;
                list($msec, $sec) = explode(' ', microtime());
                $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
                $header =array(
                    "Content-Type:application/json;charset=utf-8",
                    "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
                    "Accept-ClientTime:" .$microtime,
                    "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/match/battle_log"))
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
                $return_data3 = $return['data'];
                foreach ($return_data3 as $lkey=>$lvalue){
                    $m_b_exist = $db->select('id')->from('cmf_lol_match_battle_log')->where("log_id = '".$lvalue['log_id']."'")->row();
                    if(!$m_b_exist){
                        $insert = [
                            'battle_id'=>$lvalue['battle_id'],
                            'duration'=>$lvalue['duration'],
                            'log_id'=>$lvalue['log_id'],
                            'killed_type'=>$lvalue['killed_type'],
                            'killed_object_id'=>$lvalue['killed_object_id'],
                            'killed_object_name'=>$lvalue['killed_object_name'],
                            'killed_object_name_en'=>$lvalue['killed_object_name_en'],
                            'killed_object_logo'=>$lvalue['killed_object_logo'],
                            'killed_player_id'=>$lvalue['killed_player_id'],
                            'killed_player_name'=>empty($lvalue['killed_player_name'])?"":$lvalue['killed_player_name'],
                            'killed_player_avatar'=>empty($lvalue['killed_player_avatar'])?"":$lvalue['killed_player_avatar'],
                            'killer_type'=>$lvalue['killer_type'],
                            'killer_object_id'=>$lvalue['killer_object_id'],
                            'killer_object_name'=>$lvalue['killer_object_name'],
                            'killer_object_name_en'=>$lvalue['killer_object_name_en'],
                            'killer_object_logo'=>$lvalue['killer_object_logo'],
                            'killer_player_id'=>$lvalue['killer_player_id'],
                            'killer_player_name'=>empty($lvalue['killer_player_name'])?"":$lvalue['killer_player_name'],
                            'killer_player_avatar'=>empty($lvalue['killer_player_avatar'])?"":$lvalue['killer_player_avatar'],
                            'addtime'=>time(),
                        ];
                        $insert_id = $db->insert('cmf_lol_match_battle_log')->cols($insert)->query();

                    }else{
                        $update = [
                            'battle_id'=>$lvalue['battle_id'],
                            'duration'=>$lvalue['duration'],
                            'log_id'=>$lvalue['log_id'],
                            'killed_type'=>$lvalue['killed_type'],
                            'killed_object_id'=>$lvalue['killed_object_id'],
                            'killed_object_name'=>$lvalue['killed_object_name'],
                            'killed_object_name_en'=>$lvalue['killed_object_name_en'],
                            'killed_object_logo'=>$lvalue['killed_object_logo'],
                            'killed_player_id'=>$lvalue['killed_player_id'],
                            'killed_player_name'=>empty($lvalue['killed_player_name'])?"":$lvalue['killed_player_name'],
                            'killed_player_avatar'=>empty($lvalue['killed_player_avatar'])?"":$lvalue['killed_player_avatar'],
                            'killer_type'=>$lvalue['killer_type'],
                            'killer_object_id'=>$lvalue['killer_object_id'],
                            'killer_object_name'=>$lvalue['killer_object_name'],
                            'killer_object_name_en'=>$lvalue['killer_object_name_en'],
                            'killer_object_logo'=>$lvalue['killer_object_logo'],
                            'killer_player_id'=>$lvalue['killer_player_id'],
                            'killer_player_name'=>empty($lvalue['killer_player_name'])?"":$lvalue['killer_player_name'],
                            'killer_player_avatar'=>empty($lvalue['killer_player_avatar'])?"":$lvalue['killer_player_avatar'],
                            'addtime'=>time(),
                        ];
                        $row_count = $db->update('cmf_lol_match_battle_log')->cols($update)->where('id='.$m_b_exist['id'])->query();
                    }
                }
            }



            $exist = $db->select('id')->from('cmf_lol_match')->where("match_id = ".$value['match_id'])->row();
            if(count($match_ids) == 1){
                $value['match_data'] = $return_data2;
            }else{
                foreach ($return_data2 as $k=>$v){
                    if($value['match_id'] == $v['match_id']){
                        $value['match_data'] = $v;
                        break;
                    }
                }
            }

            if(!$exist){
                $insert = [
                    'match_id'=>$value['match_id'],
                    'league_id'=>$value['league_id'],
                    'league'=>json_encode($value['match_data']['league']),
                    'status'=>$value['status'],
                    'start_time'=>sprintf('%.0f', $value['start_time']/1000),
                    'address'=>$value['address'],
                    'round_name'=>$value['round_name'],
                    'round_son_name'=>$value['round_son_name'],
                    'bo'=>$value['bo'],
                    'battle_ids'=>json_encode($value['battle_ids']),
                    'battle_list'=>json_encode($value['match_data']['battle_list']),
                    'team_a_score'=>$value['team_a_score'],
                    'team_a_id'=>$value['team_a_id'],
                    'team_a'=>json_encode($value['match_data']['team_a']),
                    'team_b_score'=>$value['team_b_score'],
                    'team_b_id'=>$value['team_b_id'],
                    'team_b'=>json_encode($value['match_data']['team_b']),
                    'addtime'=>time(),
                ];
                $insert_id = $db->insert('cmf_lol_match')->cols($insert)->query();

            }else{
                $update = [
                    'match_id'=>$value['match_id'],
                    'league_id'=>$value['league_id'],
                    'league'=>json_encode($value['match_data']['league']),
                    'status'=>$value['status'],
                    'start_time'=>sprintf('%.0f', $value['start_time']/1000),
                    'address'=>$value['address'],
                    'round_name'=>$value['round_name'],
                    'round_son_name'=>$value['round_son_name'],
                    'bo'=>$value['bo'],
                    'battle_ids'=>json_encode($value['battle_ids']),
                    'battle_list'=>json_encode($value['match_data']['battle_list']),
                    'team_a_score'=>$value['team_a_score'],
                    'team_a_id'=>$value['team_a_id'],
                    'team_a'=>json_encode($value['match_data']['team_a']),
                    'team_b_score'=>$value['team_b_score'],
                    'team_b_id'=>$value['team_b_id'],
                    'team_b'=>json_encode($value['match_data']['team_b']),
                    'addtime'=>time(),
                ];
                $row_count = $db->update('cmf_lol_match')->cols($update)->where('id='.$exist['id'])->query();
            }
        }
    });


    //比赛列表（删除/延迟）
    Timer::add(60,function(){
        global $db;
        $url = "http://esportsapi.feijing88.com/data-service/lol/match/special?day=7";
        list($msec, $sec) = explode(' ', microtime());
        $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        $header =array(
            "Content-Type:application/json;charset=utf-8",
            "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
            "Accept-ClientTime:" .$microtime,
            "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/match/special"))
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


        $match_ids = array_column($return_data,'match_id');
        $match_ids_str = implode(",",$match_ids);
        //赛事基本信息
        $url = "http://esportsapi.feijing88.com/data-service/lol/match/basic_info?match_id={$match_ids_str}&version=2";
        list($msec, $sec) = explode(' ', microtime());
        $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        $header =array(
            "Content-Type:application/json;charset=utf-8",
            "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
            "Accept-ClientTime:" .$microtime,
            "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/match/basic_info"))
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

        foreach ($return_data as $key=>$value){
            $exist = $db->select('id')->from('cmf_lol_match')->where("match_id = ".$value['match_id'])->row();
            if(count($match_ids) == 1){
                $value['match_data'] = $return_data2;
            }else{
                foreach ($return_data2 as $k=>$v){
                    if($value['match_id'] == $v['match_id']){
                        $value['match_data'] = $v;
                        break;
                    }
                }
            }

            if(!$exist){
                $insert = [
                    'match_id'=>$value['match_id'],
                    'league_id'=>$value['league_id'],
                    'league'=>json_encode($value['match_data']['league']),
                    'status'=>$value['status'],
                    'start_time'=>sprintf('%.0f', $value['start_time']/1000),
                    'address'=>$value['address'],
                    'round_name'=>$value['round_name'],
                    'round_son_name'=>$value['round_son_name'],
                    'bo'=>$value['bo'],
                    'battle_ids'=>json_encode($value['battle_ids']),
                    'battle_list'=>json_encode($value['match_data']['battle_list']),
                    'team_a_score'=>$value['team_a_score'],
                    'team_a_id'=>$value['team_a_id'],
                    'team_a'=>json_encode($value['match_data']['team_a']),
                    'team_b_score'=>$value['team_b_score'],
                    'team_b_id'=>$value['team_b_id'],
                    'team_b'=>json_encode($value['match_data']['team_b']),
                    'addtime'=>time(),
                ];
                $insert_id = $db->insert('cmf_lol_match')->cols($insert)->query();

            }else{
                $update = [
                    'match_id'=>$value['match_id'],
                    'league_id'=>$value['league_id'],
                    'league'=>json_encode($value['match_data']['league']),
                    'status'=>$value['status'],
                    'start_time'=>sprintf('%.0f', $value['start_time']/1000),
                    'address'=>$value['address'],
                    'round_name'=>$value['round_name'],
                    'round_son_name'=>$value['round_son_name'],
                    'bo'=>$value['bo'],
                    'battle_ids'=>json_encode($value['battle_ids']),
                    'battle_list'=>json_encode($value['match_data']['battle_list']),
                    'team_a_score'=>$value['team_a_score'],
                    'team_a_id'=>$value['team_a_id'],
                    'team_a'=>json_encode($value['match_data']['team_a']),
                    'team_b_score'=>$value['team_b_score'],
                    'team_b_id'=>$value['team_b_id'],
                    'team_b'=>json_encode($value['match_data']['team_b']),
                    'addtime'=>time(),
                ];
                $row_count = $db->update('cmf_lol_match')->cols($update)->where('id='.$exist['id'])->query();
            }
        }
    });
//
//
//    //比赛列表（进行中） 60秒更新所有
    Timer::add(60,function(){
        global $db;
        $url = "http://esportsapi.feijing88.com/data-service/lol/match/live_score?version=2";
        list($msec, $sec) = explode(' ', microtime());
        $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        $header =array(
            "Content-Type:application/json;charset=utf-8",
            "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
            "Accept-ClientTime:" .$microtime,
            "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/match/live_score"))
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
        $return_data = $return['data']?$return['data']:[];
        $match_ids = array_column($return_data,'match_id');
        $match_ids_str = implode(",",$match_ids);
        if($match_ids_str){
            //赛事直播地址
            $url = "http://esportsapi.feijing88.com/data-service/lol/match/live_video?match_id={$match_ids_str}&version=2";
            list($msec, $sec) = explode(' ', microtime());
            $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
            $header =array(
                "Content-Type:application/json;charset=utf-8",
                "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
                "Accept-ClientTime:" .$microtime,
                "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/match/live_video"))
            );
            $curl = curl_init(); // 启动一个CURL会话
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            $tmpInfo = curl_exec($curl);
            var_dump($tmpInfo);
            curl_close($curl);
            $return = json_decode($tmpInfo,true);
            $return_data3 = $return['data'];
            foreach ($return_data3 as $lk=>$lv){
                $exist = $db->select('id')->from('cmf_lol_match_live')->where("match_id = ".$lv['match_id']." and name = '".$lv['name']."'")->row();
                if(!$exist){
                    $insert = [
                        'match_id'=>$lv['match_id'],
                        'name'=>$lv['name'],
                        'url'=>$lv['url'],
                        'name_en'=>$lv['name_en'],
                        'addtime'=>time(),
                    ];
                    $insert_id = $db->insert('cmf_lol_match_live')->cols($insert)->query();
                }else{
                    $update = [
                        'match_id'=>$lv['match_id'],
                        'name'=>$lv['name'],
                        'url'=>$lv['url'],
                        'name_en'=>$lv['name_en'],
                        'addtime'=>time(),
                    ];
                    $row_count = $db->update('cmf_lol_match_live')->cols($update)->where('id='.$exist['id'])->query();
                }
            }
        }

        if($return['code'] == 200){
            foreach ($return_data as $key=>$value){
                //滚球指数
                $url = "http://esportsapi.feijing88.com/data-service/lol/match/bet_info/rolling?match_id=".$value['match_id']."&version=2";
                list($msec, $sec) = explode(' ', microtime());
                $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
                $header =array(
                    "Content-Type:application/json;charset=utf-8",
                    "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
                    "Accept-ClientTime:" .$microtime,
                    "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/match/bet_info/rolling"))
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
                $return_data5 = $return['data'];
                foreach ($return_data5 as $bkey=>$bvalue) {
                    $b_i_exist = $db->select('id')->from('cmf_lol_match_bet_info_rolling')->where("bet_id = '".$bvalue['bet_id']."'")->row();
                    if(!$b_i_exist){
                        $insert = [
                            'bet_id'=>$bvalue['bet_id'],
                            'match_id'=>$bvalue['match_id'],
                            'title'=>$bvalue['title'],
                            'end_time'=>sprintf('%.0f', $bvalue['end_time']/1000),
                            'source'=>$bvalue['source'],
                            'result_id'=>$bvalue['result_id'],
                            'options'=>$bvalue['options']?json_encode($bvalue['options']):"",
                            'bet_type'=>$bvalue['bet_type'],
                            'board_num'=>$bvalue['board_num'],
                            'type_desc'=>$bvalue['type_desc'],
                            'handicap'=>empty($bvalue['handicap'])?"":$bvalue['handicap'],
                            'addtime'=>time(),
                        ];
                        $insert_id = $db->insert('cmf_lol_match_bet_info_rolling')->cols($insert)->query();
                    }else{
                        $update = [
                            'bet_id'=>$bvalue['bet_id'],
                            'match_id'=>$bvalue['match_id'],
                            'title'=>$bvalue['title'],
                            'end_time'=>sprintf('%.0f', $bvalue['end_time']/1000),
                            'source'=>$bvalue['source'],
                            'result_id'=>$bvalue['result_id'],
                            'options'=>$bvalue['options']?json_encode($bvalue['options']):"",
                            'bet_type'=>$bvalue['bet_type'],
                            'board_num'=>$bvalue['board_num'],
                            'type_desc'=>$bvalue['type_desc'],
                            'handicap'=>empty($bvalue['handicap'])?"":$bvalue['handicap'],
                            'addtime'=>time(),
                        ];
                        $row_count = $db->update('cmf_lol_match_bet_info_rolling')->cols($update)->where('id='.$b_i_exist['id'])->query();

                    }
                }


                //比分事件（实时/赛后）
                foreach ($value['battle_ids'] as $bkey=>$bvalue){
                    $url = "http://esportsapi.feijing88.com/data-service/lol/match/live_battle?battle_id=".$bvalue."&version=2";
                    list($msec, $sec) = explode(' ', microtime());
                    $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
                    $header =array(
                        "Content-Type:application/json;charset=utf-8",
                        "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
                        "Accept-ClientTime:" .$microtime,
                        "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/match/live_battle"))
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
                    $l_b_exist = $db->select('id')->from('cmf_lol_match_live_battle')->where("battle_id = ".$return_data2['battle_id'])->row();
                    if(!$l_b_exist){
                        $insert = [
                            'battle_id'=>$return_data2['battle_id'],
                            'match_id'=>$return_data2['match_id'],
                            'duration'=>$return_data2['duration'],
                            'index'=>$return_data2['index'],
                            'economic_diff'=>empty($return_data2['economic_diff'])?"":json_encode($return_data2['economic_diff']),
                            'xp_diff'=>empty($return_data2['xp_diff'])?"":json_encode($return_data2['xp_diff']),
                            'creep_score_diff'=>empty($return_data2['creep_score_diff'])?"":json_encode($return_data2['creep_score_diff']),
                            'start_time'=>sprintf('%.0f', $return_data2['start_time']/1000),
                            'player_stats'=>empty($return_data2['player_stats'])?"":json_encode($return_data2['player_stats']),
                            'team_stats'=>empty($return_data2['team_stats'])?"":json_encode($return_data2['team_stats']),
                            'addtime'=>time(),
                        ];
                        $insert_id = $db->insert('cmf_lol_match_live_battle')->cols($insert)->query();
                    }else{
                        $update = [
                            'battle_id'=>$return_data2['battle_id'],
                            'match_id'=>$return_data2['match_id'],
                            'duration'=>$return_data2['duration'],
                            'index'=>$return_data2['index'],
                            'economic_diff'=>empty($return_data2['economic_diff'])?"":json_encode($return_data2['economic_diff']),
                            'xp_diff'=>empty($return_data2['xp_diff'])?"":json_encode($return_data2['xp_diff']),
                            'creep_score_diff'=>empty($return_data2['creep_score_diff'])?"":json_encode($return_data2['creep_score_diff']),
                            'start_time'=>sprintf('%.0f', $return_data2['start_time']/1000),
                            'player_stats'=>empty($return_data2['player_stats'])?"":json_encode($return_data2['player_stats']),
                            'team_stats'=>empty($return_data2['team_stats'])?"":json_encode($return_data2['team_stats']),
                            'addtime'=>time(),
                        ];
                        $row_count = $db->update('cmf_lol_match_live_battle')->cols($update)->where('id='.$l_b_exist['id'])->query();

                    }
                }



                //文字直播
                foreach ($value['battle_ids'] as $bkey=>$bvalue){
                    $url = "http://esportsapi.feijing88.com/data-service/lol/match/battle_log?battle_id=".$bvalue;
                    list($msec, $sec) = explode(' ', microtime());
                    $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
                    $header =array(
                        "Content-Type:application/json;charset=utf-8",
                        "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
                        "Accept-ClientTime:" .$microtime,
                        "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/match/battle_log"))
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
                    $return_data3 = $return['data'];
                    foreach ($return_data3 as $lkey=>$lvalue){
                        $m_b_exist = $db->select('id')->from('cmf_lol_match_battle_log')->where("log_id = '".$lvalue['log_id']."'")->row();
                        if(!$m_b_exist){
                            $insert = [
                                'battle_id'=>$lvalue['battle_id'],
                                'duration'=>$lvalue['duration'],
                                'log_id'=>$lvalue['log_id'],
                                'killed_type'=>$lvalue['killed_type'],
                                'killed_object_id'=>$lvalue['killed_object_id'],
                                'killed_object_name'=>$lvalue['killed_object_name'],
                                'killed_object_name_en'=>$lvalue['killed_object_name_en'],
                                'killed_object_logo'=>$lvalue['killed_object_logo'],
                                'killed_player_id'=>$lvalue['killed_player_id'],
                                'killed_player_name'=>$lvalue['killed_player_name'],
                                'killed_player_avatar'=>$lvalue['killed_player_avatar'],
                                'killer_type'=>$lvalue['killer_type'],
                                'killer_object_id'=>$lvalue['killer_object_id'],
                                'killer_object_name'=>$lvalue['killer_object_name'],
                                'killer_object_name_en'=>$lvalue['killer_object_name_en'],
                                'killer_object_logo'=>$lvalue['killer_object_logo'],
                                'killer_player_id'=>$lvalue['killer_player_id'],
                                'killer_player_name'=>$lvalue['killer_player_name'],
                                'killer_player_avatar'=>$lvalue['killer_player_avatar'],
                                'addtime'=>time(),
                            ];
                            $insert_id = $db->insert('cmf_lol_match_battle_log')->cols($insert)->query();

                        }else{
                            $update = [
                                'battle_id'=>$lvalue['battle_id'],
                                'duration'=>$lvalue['duration'],
                                'log_id'=>$lvalue['log_id'],
                                'killed_type'=>$lvalue['killed_type'],
                                'killed_object_id'=>$lvalue['killed_object_id'],
                                'killed_object_name'=>$lvalue['killed_object_name'],
                                'killed_object_name_en'=>$lvalue['killed_object_name_en'],
                                'killed_object_logo'=>$lvalue['killed_object_logo'],
                                'killed_player_id'=>$lvalue['killed_player_id'],
                                'killed_player_name'=>$lvalue['killed_player_name'],
                                'killed_player_avatar'=>$lvalue['killed_player_avatar'],
                                'killer_type'=>$lvalue['killer_type'],
                                'killer_object_id'=>$lvalue['killer_object_id'],
                                'killer_object_name'=>$lvalue['killer_object_name'],
                                'killer_object_name_en'=>$lvalue['killer_object_name_en'],
                                'killer_object_logo'=>$lvalue['killer_object_logo'],
                                'killer_player_id'=>$lvalue['killer_player_id'],
                                'killer_player_name'=>$lvalue['killer_player_name'],
                                'killer_player_avatar'=>$lvalue['killer_player_avatar'],
                                'addtime'=>time(),
                            ];
                            $row_count = $db->update('cmf_lol_match_battle_log')->cols($update)->where('id='.$m_b_exist['id'])->query();
                        }
                    }
                }



                $push = [
                    'match_id'=>$value['match_id'],
                    'addtime'=>time(),
                ];
                $insert_id = $db->insert('cmf_lol_match_push')->cols($push)->query();
                $exist = $db->select('id')->from('cmf_lol_match')->where("match_id = ".$value['match_id']." and league_id = ".$value['league_id'])->row();
                if(!$exist){
                    $insert = [
                        'match_id'=>$value['match_id'],
                        'league_id'=>$value['league_id'],
                        'status'=>$value['status'],
                        'start_time'=>sprintf('%.0f', $value['start_time']/1000),
                        'address'=>$value['address'],
                        'round_name'=>$value['round_name'],
                        'round_son_name'=>$value['round_son_name'],
                        'bo'=>$value['bo'],
                        'battle_ids'=>json_encode($value['battle_ids']),
                        'team_a_score'=>$value['team_a_score'],
                        'team_a_id'=>$value['team_a_id'],
                        'team_b_score'=>$value['team_b_score'],
                        'team_b_id'=>$value['team_b_id'],
                        'addtime'=>time(),
                    ];
                    $insert_id = $db->insert('cmf_lol_match')->cols($insert)->query();
                }else{
                    $update = [
                        'match_id'=>$value['match_id'],
                        'league_id'=>$value['league_id'],
                        'status'=>$value['status'],
                        'start_time'=>sprintf('%.0f', $value['start_time']/1000),
                        'address'=>$value['address'],
                        'round_name'=>$value['round_name'],
                        'round_son_name'=>$value['round_son_name'],
                        'bo'=>$value['bo'],
                        'battle_ids'=>json_encode($value['battle_ids']),
                        'team_a_score'=>$value['team_a_score'],
                        'team_a_id'=>$value['team_a_id'],
                        'team_b_score'=>$value['team_b_score'],
                        'team_b_id'=>$value['team_b_id'],
                        'addtime'=>time(),
                    ];
                    $row_count = $db->update('cmf_lol_match')->cols($update)->where('id='.$exist['id'])->query();
                }
            }
        }
    });





    //   数据库中比赛列表状态更新
    Timer::add(60,function(){
        global $db;
        global $match_limit;
        $limit = $match_limit;
        $match_limit = $match_limit + 10;
        $match_ids = $db->column("SELECT match_id FROM `cmf_lol_match` limit $limit,10");
        if(empty($match_ids)){
            $match_limit = 0;
            return;
        }else{
            $match_ids_str = implode(",",$match_ids);
            //赛事基本信息
            $url = "http://esportsapi.feijing88.com/data-service/lol/match/basic_info?match_id={$match_ids_str}&version=2";
            list($msec, $sec) = explode(' ', microtime());
            $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
            $header =array(
                "Content-Type:application/json;charset=utf-8",
                "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
                "Accept-ClientTime:" .$microtime,
                "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/match/basic_info"))
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
            foreach ($return_data2 as $key=>$value){
                $update['status'] = $value['status'];
                $update['team_a_score'] = $value['team_a_score'];
                $update['team_b_score']=$value['team_b_score'];
                $where['match_id'] = $value['match_id'];
                $row_count = $db->update('cmf_lol_match')->cols($update)->where('match_id='.$where['match_id'])->query();
            }
        }
    });



};
// 运行worker
Worker::runAll();