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
    global $league_list_offset;
    $league_list_offset = 0;
    $database = config();
    global $db;
    $db  = new \Workerman\MySQL\Connection($database['hostname'], $database['hostport'], $database['username'], $database['password'], $database['database']);

//    联赛列表
    Timer::add(60,function(){
        global $db;
        global $league_list_offset;
        $offset = $league_list_offset;
        $league_list_offset += 10;
        $url = "http://esportsapi.feijing88.com/data-service/lol/league/list?offset=".$offset."&limit=10&version=2";
        list($msec, $sec) = explode(' ', microtime());
        $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        $header =array(
            "Content-Type:application/json;charset=utf-8",
            "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
            "Accept-ClientTime:" .$microtime,
            "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/league/list"))
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
        if($return['code'] == 200){
            $return_data = $return['data'];
            if(count($return_data) == 0){
                $league_list_offset = 0;
                return;
            }
            $league_ids = array_column($return_data,'league_id');
            $league_ids_str = implode(",",$league_ids);
            //联赛积分榜
            $url = "http://esportsapi.feijing88.com/data-service/lol/league/board?league_id={$league_ids_str}&version=2";
            list($msec, $sec) = explode(' ', microtime());
            $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
            $header =array(
                "Content-Type:application/json;charset=utf-8",
                "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
                "Accept-ClientTime:" .$microtime,
                "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/league/board"))
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
                $l_b_exist = $db->select('id')->from('cmf_lol_league_board')->where("league_id = ".$value['league_id']." and team_id = ".$value['team_id']." and stage = '".$value['stage']."'")->row();
                if(!$l_b_exist){
                    $insert = [
                        'league_id'=>$value['league_id'],
                        'team_id'=>$value['team_id'],
                        'win_count'=>$value['win_count'],
                        'lost_count'=>$value['lost_count'],
                        'score'=>$value['score'],
                        'type_name'=>$value['type_name'],
                        'stage'=>$value['stage'],
                        'addtime'=>time(),
                    ];
                    $insert_id = $db->insert('cmf_lol_league_board')->cols($insert)->query();
                }else{
                    $update = [
                        'league_id'=>$value['league_id'],
                        'team_id'=>$value['team_id'],
                        'win_count'=>$value['win_count'],
                        'lost_count'=>$value['lost_count'],
                        'score'=>$value['score'],
                        'type_name'=>$value['type_name'],
                        'stage'=>$value['stage'],
                        'addtime'=>time(),
                    ];
                    $row_count = $db->update('cmf_lol_league_board')->cols($update)->where('id='.$l_b_exist['id'])->query();
                }
            }
            foreach ($return_data as $key=>$value){
                //联赛统计（战队）
                $url = "http://esportsapi.feijing88.com/data-service/lol/league/stats/team?league_id={$value['league_id']}&version=2";
                list($msec, $sec) = explode(' ', microtime());
                $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
                $header =array(
                    "Content-Type:application/json;charset=utf-8",
                    "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
                    "Accept-ClientTime:" .$microtime,
                    "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/league/stats/team"))
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

                foreach ($return_data3 as $tkey=>$tvalue){
                    $t_s_exist = $db->select('id')->from('cmf_lol_team_stat')->where("league_id = ".$tvalue['league_id']." and team_id = ".$tvalue['team_id'])->row();
                    if(!$t_s_exist){
                        $insert = [
                            'league_id'=>$tvalue['league_id'],
                            'league_name'=>$tvalue['league_name'],
                            'league_name_en'=>$tvalue['league_name_en'],
                            'team_id'=>$tvalue['team_id'],
                            'team_name'=>$tvalue['team_name'],
                            'team_name_en'=>$tvalue['team_name_en'],
                            'team_short_name'=>$tvalue['team_short_name'],
                            'team_logo'=>$tvalue['team_logo'],
                            'game_count'=>$tvalue['game_count'],
                            'win_rate'=>$tvalue['win_rate'],
                            'game_duration'=>$tvalue['game_duration'],
                            'deaths_per_game'=>$tvalue['deaths_per_game'],
                            'kills_per_game'=>$tvalue['kills_per_game'],
                            'golds_per_minute'=>$tvalue['golds_per_minute'],
                            'first_blood_rate'=>$tvalue['first_blood_rate'],
                            'tower_killed'=>$tvalue['tower_killed'],
                            'five_kills_rate'=>$tvalue['five_kills_rate'],
                            'ten_kills_rate'=>$tvalue['ten_kills_rate'],
                            'kda'=>$tvalue['kda'],
                            'range_type'=>$tvalue['range_type'],
                            'damage_per_game'=>$tvalue['damage_per_game'],
                            'nash_killed_per_game'=>$tvalue['nash_killed_per_game'],
                            'dragon_killed_per_game'=>$tvalue['dragon_killed_per_game'],
                            'first_tower_rate'=>$tvalue['first_tower_rate'],
                            'gdm'=>$tvalue['gdm'],
                            'kd'=>$tvalue['kd'],
                            'damage_per_minute'=>$tvalue['damage_per_minute'],
                            'last_hit_per_minute'=>$tvalue['last_hit_per_minute'],
                            'golds_per_game'=>$tvalue['golds_per_game'],
                            'addtime'=>time(),
                        ];
                        $insert_id = $db->insert('cmf_lol_team_stat')->cols($insert)->query();

                    }else{
                        $update = [
                            'league_id'=>$tvalue['league_id'],
                            'league_name'=>$tvalue['league_name'],
                            'league_name_en'=>$tvalue['league_name_en'],
                            'team_id'=>$tvalue['team_id'],
                            'team_name'=>$tvalue['team_name'],
                            'team_name_en'=>$tvalue['team_name_en'],
                            'team_short_name'=>$tvalue['team_short_name'],
                            'team_logo'=>$tvalue['team_logo'],
                            'game_count'=>$tvalue['game_count'],
                            'win_rate'=>$tvalue['win_rate'],
                            'game_duration'=>$tvalue['game_duration'],
                            'deaths_per_game'=>$tvalue['deaths_per_game'],
                            'kills_per_game'=>$tvalue['kills_per_game'],
                            'golds_per_minute'=>$tvalue['golds_per_minute'],
                            'first_blood_rate'=>$tvalue['first_blood_rate'],
                            'tower_killed'=>$tvalue['tower_killed'],
                            'five_kills_rate'=>$tvalue['five_kills_rate'],
                            'ten_kills_rate'=>$tvalue['ten_kills_rate'],
                            'kda'=>$tvalue['kda'],
                            'range_type'=>$tvalue['range_type'],
                            'damage_per_game'=>$tvalue['damage_per_game'],
                            'nash_killed_per_game'=>$tvalue['nash_killed_per_game'],
                            'dragon_killed_per_game'=>$tvalue['dragon_killed_per_game'],
                            'first_tower_rate'=>$tvalue['first_tower_rate'],
                            'gdm'=>$tvalue['gdm'],
                            'kd'=>$tvalue['kd'],
                            'damage_per_minute'=>$tvalue['damage_per_minute'],
                            'last_hit_per_minute'=>$tvalue['last_hit_per_minute'],
                            'golds_per_game'=>$tvalue['golds_per_game'],
                            'addtime'=>time(),
                        ];
                        $row_count = $db->update('cmf_lol_team_stat')->cols($update)->where('id='.$t_s_exist['id'])->query();
                    }
                }

                //联赛统计（选手）
                $url = "http://esportsapi.feijing88.com/data-service/lol/league/stats/player?league_id={$value['league_id']}&version=2";
                list($msec, $sec) = explode(' ', microtime());
                $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
                $header =array(
                    "Content-Type:application/json;charset=utf-8",
                    "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
                    "Accept-ClientTime:" .$microtime,
                    "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/league/stats/player"))
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
                foreach ($return_data4 as $pkey=>$pvalue){
                    $p_s_exist =  $db->select('id')->from('cmf_lol_player_stats')->where("league_id = ".$pvalue['league_id']." and player_id = ".$pvalue['player_id'])->row();
                    if(!$p_s_exist){
                        $insert = [
                          'league_id'=>$pvalue['league_id'],
                          'league_name'=>$pvalue['league_name'],
                          'league_name_en'=>$pvalue['league_name_en'],
                          'team_id'=>$pvalue['team_id'],
                          'team_name'=>$pvalue['team_name'],
                          'team_name_en'=>$pvalue['team_name_en'],
                          'team_short_name'=>$pvalue['team_short_name'],
                          'player_id'=>$pvalue['player_id'],
                          'player_name'=>$pvalue['player_name'],
                          'player_avatar'=>$pvalue['player_avatar'],
                          'game_count'=>$pvalue['game_count'],
                          'kda'=>$pvalue['kda'],
                          'kill_average'=>$pvalue['kill_average'],
                          'assist_average'=>$pvalue['assist_average'],
                          'death_average'=>$pvalue['death_average'],
                          'last_hit_per_game'=>$pvalue['last_hit_per_game'],
                          'gold_per_minute'=>$pvalue['gold_per_minute'],
                          'last_hit_per_minute'=>$pvalue['last_hit_per_minute'],
                          'most_kill_per_games'=>$pvalue['most_kill_per_games'],
                          'most_death_per_games'=>$pvalue['most_death_per_games'],
                          'most_assist_per_games'=>$pvalue['most_assist_per_games'],
                          'range_type'=>$pvalue['range_type'],
                          'offered_rate'=>$pvalue['offered_rate'],
                          'damage_per_minute'=>$pvalue['damage_per_minute'],
                          'damage_percent'=>$pvalue['damage_percent'],
                          'damage_taken_minute'=>$pvalue['damage_taken_minute'],
                          'damage_taken_percent'=>$pvalue['damage_taken_percent'],
                          'addtime'=>time(),
                        ];
                        $insert_id = $db->insert('cmf_lol_player_stats')->cols($insert)->query();

                    }else{
                        $update = [
                            'league_id'=>$pvalue['league_id'],
                            'league_name'=>$pvalue['league_name'],
                            'league_name_en'=>$pvalue['league_name_en'],
                            'team_id'=>$pvalue['team_id'],
                            'team_name'=>$pvalue['team_name'],
                            'team_name_en'=>$pvalue['team_name_en'],
                            'team_short_name'=>$pvalue['team_short_name'],
                            'player_id'=>$pvalue['player_id'],
                            'player_name'=>$pvalue['player_name'],
                            'player_avatar'=>$pvalue['player_avatar'],
                            'game_count'=>$pvalue['game_count'],
                            'kda'=>$pvalue['kda'],
                            'kill_average'=>$pvalue['kill_average'],
                            'assist_average'=>$pvalue['assist_average'],
                            'death_average'=>$pvalue['death_average'],
                            'last_hit_per_game'=>$pvalue['last_hit_per_game'],
                            'gold_per_minute'=>$pvalue['gold_per_minute'],
                            'last_hit_per_minute'=>$pvalue['last_hit_per_minute'],
                            'most_kill_per_games'=>$pvalue['most_kill_per_games'],
                            'most_death_per_games'=>$pvalue['most_death_per_games'],
                            'most_assist_per_games'=>$pvalue['most_assist_per_games'],
                            'range_type'=>$pvalue['range_type'],
                            'offered_rate'=>$pvalue['offered_rate'],
                            'damage_per_minute'=>$pvalue['damage_per_minute'],
                            'damage_percent'=>$pvalue['damage_percent'],
                            'damage_taken_minute'=>$pvalue['damage_taken_minute'],
                            'damage_taken_percent'=>$pvalue['damage_taken_percent'],
                            'addtime'=>time(),
                        ];
                        $row_count = $db->update('cmf_lol_player_stats')->cols($update)->where('id='.$p_s_exist['id'])->query();

                    }
                }

                //联赛统计（英雄）
                $url = "http://esportsapi.feijing88.com/data-service/lol/league/stats/hero?league_id={$value['league_id']}&version=2";
                list($msec, $sec) = explode(' ', microtime());
                $microtime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
                $header =array(
                    "Content-Type:application/json;charset=utf-8",
                    "Accept-ApiAccess:"."AZ7OYwYoAMhY37NmgWzkWo1xQWF5zAZd",
                    "Accept-ClientTime:" .$microtime,
                    "Accept-ApiSign:".strtoupper(md5("5pVlqjN9QZypQbXmSQQOfIPQD17QxIR4|".$microtime."|/data-service/lol/league/stats/hero"))
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
                foreach ($return_data5 as $hkey=>$hvalue){
                    $h_s_exist = $db->select('id')->from('cmf_lol_hero_stat')->where("league_id = ".$hvalue['league_id']." and hero_id = ".$hvalue['hero_id'])->row();
                    if(!$h_s_exist){
                        $insert = [
                            'league_id'=>$hvalue['league_id'],
                            'league_name'=>$hvalue['league_name'],
                            'league_name_en'=>$hvalue['league_name_en'],
                            'hero_id'=>$hvalue['hero_id'],
                            'hero_name'=>$hvalue['hero_name'],
                            'hero_name_en'=>$hvalue['hero_name_en'],
                            'hero_avatar'=>$hvalue['hero_avatar'],
                            'pick_rate'=>$hvalue['pick_rate'],
                            'ban_rate'=>$hvalue['ban_rate'],
                            'win_rate'=>$hvalue['win_rate'],
                            'kda'=>$hvalue['kda'],
                            'pick_count'=>$hvalue['pick_count'],
                            'ban_count'=>$hvalue['ban_count'],
                            'win_count'=>$hvalue['win_count'],
                            'range_type'=>$hvalue['range_type'],
                            'addtime'=>time(),
                        ];
                        $insert_id = $db->insert('cmf_lol_hero_stat')->cols($insert)->query();

                    }else{
                        $update = [
                            'league_id'=>$hvalue['league_id'],
                            'league_name'=>$hvalue['league_name'],
                            'league_name_en'=>$hvalue['league_name_en'],
                            'hero_id'=>$hvalue['hero_id'],
                            'hero_name'=>$hvalue['hero_name'],
                            'hero_name_en'=>$hvalue['hero_name_en'],
                            'hero_avatar'=>$hvalue['hero_avatar'],
                            'pick_rate'=>$hvalue['pick_rate'],
                            'ban_rate'=>$hvalue['ban_rate'],
                            'win_rate'=>$hvalue['win_rate'],
                            'kda'=>$hvalue['kda'],
                            'pick_count'=>$hvalue['pick_count'],
                            'ban_count'=>$hvalue['ban_count'],
                            'win_count'=>$hvalue['win_count'],
                            'range_type'=>$hvalue['range_type'],
                            'addtime'=>time(),
                        ];
                        $row_count = $db->update('cmf_lol_hero_stat')->cols($update)->where('id='.$h_s_exist['id'])->query();

                    }
                }

                $exist = $db->select('id')->from('cmf_lol_league')->where("league_id = ".$value['league_id'])->row();
                if(!$exist){
                    $insert = [
                        'league_id'=>$value['league_id'],
                        'name'=>$value['name'],
                        'name_en'=>$value['name_en'],
                        'short_name'=>$value['short_name'],
                        'start_time'=>sprintf('%.0f', $value['start_time']/1000),
                        'end_time'=>sprintf('%.0f', $value['end_time']/1000),
                        'organizer'=>$value['organizer'],
                        'logo'=>$value['logo'],
                        'address'=>$value['address'],
                        'team_ids'=>json_encode($value['team_ids']),
                        'status'=>$value['status'],
                        'addtime'=>time(),
                    ];
                    $insert_id = $db->insert('cmf_lol_league')->cols($insert)->query();
                }else{
                    $update = [
                        'league_id'=>$value['league_id'],
                        'name'=>$value['name'],
                        'name_en'=>$value['name_en'],
                        'short_name'=>$value['short_name'],
                        'start_time'=>sprintf('%.0f', $value['start_time']/1000),
                        'end_time'=>sprintf('%.0f', $value['end_time']/1000),
                        'organizer'=>$value['organizer'],
                        'logo'=>$value['logo'],
                        'address'=>$value['address'],
                        'team_ids'=>json_encode($value['team_ids']),
                        'status'=>$value['status'],
                        'addtime'=>time(),
                    ];
                    $row_count = $db->update('cmf_lol_league')->cols($update)->where('id='.$exist['id'])->query();
                }
            }

        }
    });

};
// 运行worker
Worker::runAll();