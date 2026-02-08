<?php

namespace App\Helpers;

use App\Exceptions\MessageException;
use App\Models\BaseModel;
use App\Models\CmfOptionModel;
use GuzzleHttp\Client;

class Helper
{
    /**
     * 替换文件内容
     * @param $filepath
     * @param $search
     * @param $replace
     */
    public static function replaceFileContent($filepath, $search, $replace) {
        $fp = file_get_contents($filepath) . "   ";
        $newFp = str_replace($search, $replace, $fp);
        file_put_contents($filepath, $newFp);
    }

    /**
     * 输出格式化
     * @param array $data
     * @param int $code
     * @param string $msg
     * @return array
     */
    public static function format($data = [], $code = MessageException::CODE_SUCCESS, $msg = '')
    {
        if (empty($msg)) $msg = MessageException::$MessageMap[$code];
        return ['code' => $code, 'data' => $data, 'msg' => $msg];
    }

    /**
     * 异常抛出
     * @param $code
     * @param string $msg
     * @return array
     */
    public static function returnEx($code, $msg = '')
    {
        if (empty($msg)) $msg = MessageException::$MessageMap[$code];
        return ['code' => $code, 'data' => '', 'msg' => $msg];
    }

    public static function returnData($data)
    {
        return ['code' => MessageException::CODE_SUCCESS, 'data' => $data, 'msg' => MessageException::$MessageMap[MessageException::CODE_SUCCESS]];
    }

    public static function returnSuccess($code = MessageException::CODE_SUCCESS, $msg = '')
    {
        if (empty($msg)) $msg = MessageException::$MessageMap[$code];
        return ['code' => $code, 'data' => '', 'msg' => $msg];
    }

    public static function returnJson($data) {
        $return['ret'] = MessageException::CODE_SUCCESS;
        $return['data']['code'] = 0;
        $return['data']['msg'] = '';
        $return['data']['info'] = $data;
        $return['msg'] = '';
        return response()->json($return);
    }

    /**
     * 获取当前时间
     * @param string $time
     * @return false|string
     */
    public static function currentTime($time = 'datetime')
    {
        if ($time == 'date') {
            $format = 'Y-m-d';
        } else {
            $format = 'Y-m-d H:i:s';
        }
        return date($format, time());
    }

    /**
     * 数组重建索引
     * @param array|object $arr
     * @param String $key
     * @return array
     */
    public static function array_index($arr, String $key)
    {
        if (empty($arr) || count($arr) <= 0) return [];
        $data = [];
        if (is_object($arr)) {
            foreach ($arr as $a) {
                $data[$a->$key] = $a;
            }
        }

        if (is_array($arr)) {
            foreach ($arr as $a) {
                $data[$a[$key]] = $a;
            }
        }

        return $data;
    }

    /**
     * 数组重建索引
     * @param array|object $arr
     * @param String $key
     * @return array
     */
    public static function array_index_array($arr, String $key)
    {
        if (empty($arr) || count($arr) <= 0) return [];
        $data = [];
        if (is_object($arr)) {
            foreach ($arr as $a) {
                $data[$a->$key][] = $a;
            }
        }

        if (is_array($arr)) {
            foreach ($arr as $a) {
                $data[$a[$key]][] = $a;
            }
        }

        return $data;
    }

    /**
     * 提取二维数组指定列为新数组
     * @param array|object $arr
     * @param String $column
     * @return array
     */
    public static function array_get_column($arr, String $column)
    {
        if (empty($arr) || count($arr) <= 0) return [];
        $data = [];
        if (is_object($arr)) {
            foreach ($arr as $row) {
                $data[] = $row->$column;
            }
        }

        if (is_array($arr)) {
            foreach ($arr as $row) {
                $data[] = $row[$column];
            }
        }
        return $data;
    }

    /**
     * 二维数组按指定键值排序
     * @param array $arr
     * @param string $column
     * @param int $sort
     */
//    public static function array_two_sort(array &$arr, string $column, int $sort = SORT_ASC) {
//        array_multisort(array_column($arr, $column), $sort, $arr);
//    }

    public static function delDir($dir) {
        $delDir = config('params.allow.dir.del');
        if (!strstr($dir, config('params.allow.dir.del'))) return;
        if(!is_dir($dir)) return;
        //如果是目录则继续
        //扫描一个文件夹内的所有文件夹和文件并返回数组
        $fileArr = scandir($dir);
        foreach ($fileArr as $file) {
            $filePath = "{$dir}/{$file}";
            //排除目录中的.和..
            if($file != "." && $file != ".."){
                //如果是目录则递归子目录，继续操作
                if(is_dir($filePath)){
                    //子目录中操作删除文件夹和文件
                    deldir($filePath);
                    //目录清空后删除空文件夹
                    if ($filePath != $delDir) @rmdir($filePath);
                } else {
                    //如果是文件直接删除
                    unlink($filePath);
                }
            }
        }
        if ($dir != $delDir) @rmdir($dir);
    }

    public static function whichWeekDay($date)
    {
        $w = date('w', strtotime($date));
        $week=array(
            "0"=>"星期日",
            "1"=>"星期一",
            "2"=>"星期二",
            "3"=>"星期三",
            "4"=>"星期四",
            "5"=>"星期五",
            "6"=>"星期六"
        );
        return $week[$w];
    }

    public static function getWeekDayStr($date)
    {
        $dayDesc = '';
        if (date('Y-m-d') == $date) $dayDesc = '今天 ';
        if (date('Y-m-d', strtotime("+1 day")) == $date) $dayDesc = '明天 ';
        return $dayDesc . date("m月d日", strtotime($date)) . " " . self::whichWeekDay($date);
    }

    public static function getMillisecond() {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }


    /**
     * @desc 腾讯云推拉流地址
     * @param string $host 协议，如:http、rtmp
     * @param string $stream 流名,如有则包含 .flv、.m3u8
     * @param int $type 类型，0表示播流，1表示推流
     */
    public static function PrivateKey_tx($host, $stream, $type)
    {
        $configpri = json_decode(CmfOptionModel::select(['option_value'])->where(['option_name' => "configpri"])->value('option_value'),true);
        $bizid = $configpri['tx_bizid'];
        $push_url_key = $configpri['tx_push_key'];
        $play_url_key = $configpri['tx_play_key'];
        $push = $configpri['tx_push'];
        $pull = $configpri['tx_pull'];
        $stream_a = explode('.', $stream);
        $streamKey = $stream_a[0];
//        $ext = $stream_a[1];

        //$live_code = $bizid . "_" .$streamKey;
        $live_code = $streamKey;

        $now = time();
        $now_time = $now + 3 * 60 * 60;
        $txTime = dechex($now_time);

        $txSecret = md5($push_url_key . $live_code . $txTime);
        $safe_url = "?txSecret=" . $txSecret . "&txTime=" . $txTime;

        $play_safe_url = '';
        //后台开启了播流鉴权
//    if ($configpri['tx_play_key_switch']) {
//        //播流鉴权时间
//
//        $play_auth_time = $now + (int)$configpri['tx_play_time'];
//        $txPlayTime = dechex($play_auth_time);
//        $txPlaySecret = md5($play_url_key . $live_code . $txPlayTime);
//        $play_safe_url = "?txSecret=" . $txPlaySecret . "&txTime=" . $txPlayTime;
//
//    }

        if ($type == 1) {
            //$push_url = "rtmp://" . $bizid . ".livepush2.myqcloud.com/live/" .  $live_code . "?bizid=" . $bizid . "&record=flv" .$safe_url;	可录像
            $url = "{$push}/live/" . $live_code . $safe_url;
        } else {
            $url = "{$pull}/live/" . $live_code . ".flv" . $play_safe_url;
        }

        return $url;
    }

    /**
     * @desc 腾讯云推拉流地址
     * @param string $stream 流名,如有则包含 .flv、.m3u8
     * @param int $type 类型，0表示播流，1表示推流
     * @param string $url 设定推流/拉流地址
     * @return string
     */
    public static function getTencentPushOrPullUrl($stream, $type, $url)
    {
        $configpri = json_decode(CmfOptionModel::select(['option_value'])->where(['option_name' => "configpri"])->value('option_value'),true);
        $push_url_key = $configpri['tx_push_key'];
        $push = $url ? $url : $configpri['tx_push'];
        $pull = $url ? $url : $configpri['tx_pull'];
        $stream_a = explode('.', $stream);
        $streamKey = $stream_a[0];
        $live_code = $streamKey;

        $now = time();
        $now_time = $now + 3 * 60 * 60;
        $txTime = dechex($now_time);

        $txSecret = md5($push_url_key . $live_code . $txTime);
        $safe_url = "?txSecret=" . $txSecret . "&txTime=" . $txTime;

        $play_safe_url = '';

        if ($type == 1) {
            $url = "{$push}/live/" . $live_code . $safe_url;
        } else {
            $url = "{$pull}/live/" . $live_code . ".flv" . $play_safe_url;
        }

        return $url;
    }

    public static function sendDataToChatServer($sendData, $isJsonEncode = true)
    {
        if ($isJsonEncode) $sendData = json_encode($sendData);
        if ($ws = Helper::websocketOpen(config('params.chat.chatUrl'), config('params.chat.chatPort'))) {
            Helper::websocketWrite($ws, $sendData);
        }
    }

    public static function websocketOpen($host='',$port=80,$headers='',&$error_string='',$timeout=10,$ssl=false, $persistant = false, $path = '/'){
        // Generate a key (to convince server that the update is not random)
        // The key is for the server to prove it i websocket aware. (We know it is)
        $key=base64_encode(openssl_random_pseudo_bytes(16));

        $header = "GET " . $path . " HTTP/1.1\r\n"
            ."Host: $host\r\n"
            ."pragma: no-cache\r\n"
            ."Upgrade: WebSocket\r\n"
            ."Connection: Upgrade\r\n"
            ."Sec-WebSocket-Key: $key\r\n"
            ."Sec-WebSocket-Version: 13\r\n";

        // Add extra headers
        if(!empty($headers)) foreach($headers as $h) $header.=$h."\r\n";

        // Add end of header marker
        $header.="\r\n";

        // Connect to server
        $host = $host ? $host : "127.0.0.1";
        $port = $port <1 ? 80 : $port;
        $address = ($ssl ? 'ssl://' : '') . $host . ':' . $port;
        // put in persistant ! if used in php-fpm, no handshare if same.
        if ($persistant)
            $sp = stream_socket_client($address, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT);
        else
            $sp = stream_socket_client($address, $errno, $errstr, $timeout);

        if(!$sp){
            $error_string = "Unable to connect to websocket server: $errstr ($errno)";
            return false;
        }

        // Set timeouts
        stream_set_timeout($sp,$timeout);

        if (!$persistant or ftell($sp) === 0) {

            //Request upgrade to websocket
            $rc = fwrite($sp,$header);
            if(!$rc){
                $error_string
                    = "Unable to send upgrade header to websocket server: $errstr ($errno)";
                return false;
            }

            // Read response into an assotiative array of headers. Fails if upgrade failes.
            $reaponse_header=fread($sp, 1024);

            // status code 101 indicates that the WebSocket handshake has completed.
            if (stripos($reaponse_header, ' 101 ') === false
                || stripos($reaponse_header, 'Sec-WebSocket-Accept: ') === false) {
                $error_string = "Server did not accept to upgrade connection to websocket."
                    .$reaponse_header. E_USER_ERROR;
                return false;
            }
            // The key we send is returned, concatenate with "258EAFA5-E914-47DA-95CA-
            // C5AB0DC85B11" and then base64-encoded. one can verify if one feels the need...

        }
        return $sp;
    }

    public static function websocketWrite($sp,$data,$final=true){
        // Assamble header: FINal 0x80 | Opcode 0x02
        $header=chr(($final?0x80:0) | 0x02); // 0x02 binary

        // Mask 0x80 | payload length (0-125)
        if(strlen($data)<126) $header.=chr(0x80 | strlen($data));
        elseif (strlen($data)<0xFFFF) $header.=chr(0x80 | 126) . pack("n",strlen($data));
        else $header.=chr(0x80 | 127) . pack("N",0) . pack("N",strlen($data));

        // Add mask
        $mask=pack("N",rand(1,0x7FFFFFFF));
        $header.=$mask;

        // Mask application data.
        for($i = 0; $i < strlen($data); $i++)
            $data[$i]=chr(ord($data[$i]) ^ ord($mask[$i % 4]));

        return fwrite($sp,$header.$data);
    }

    public static function websocketRead($sp,&$error_string=NULL){
        $data="";

        do{
            // Read header
            $header=fread($sp,2);
            if(!$header){
                $error_string = "Reading header from websocket failed.";
                return false;
            }

            $opcode = ord($header[0]) & 0x0F;
            $final = ord($header[0]) & 0x80;
            $masked = ord($header[1]) & 0x80;
            $payload_len = ord($header[1]) & 0x7F;

            // Get payload length extensions
            $ext_len = 0;
            if($payload_len >= 0x7E){
                $ext_len = 2;
                if($payload_len == 0x7F) $ext_len = 8;
                $header=fread($sp,$ext_len);
                if(!$header){
                    $error_string = "Reading header extension from websocket failed.";
                    return false;
                }

                // Set extented paylod length
                $payload_len= 0;
                for($i=0;$i<$ext_len;$i++)
                    $payload_len += ord($header[$i]) << ($ext_len-$i-1)*8;
            }

            // Get Mask key
            if($masked){
                $mask=fread($sp,4);
                if(!$mask){
                    $error_string = "Reading header mask from websocket failed.";
                    return false;
                }
            }

            // Get payload
            $frame_data='';
            do{
                $frame= fread($sp,$payload_len);
                if(!$frame){
                    $error_string = "Reading from websocket failed.";
                    return false;
                }
                $payload_len -= strlen($frame);
                $frame_data.=$frame;
            }while($payload_len>0);

            // Handle ping requests (sort of) send pong and continue to read
            if($opcode == 9){
                // Assamble header: FINal 0x80 | Opcode 0x0A + Mask on 0x80 with zero payload
                fwrite($sp,chr(0x8A) . chr(0x80) . pack("N", rand(1,0x7FFFFFFF)));
                continue;

                // Close
            } elseif($opcode == 8){
                fclose($sp);

                // 0 = continuation frame, 1 = text frame, 2 = binary frame
            }elseif($opcode < 3){
                // Unmask data
                $data_len=strlen($frame_data);
                if($masked)
                    for ($i = 0; $i < $data_len; $i++)
                        $data.= $frame_data[$i] ^ $mask[$i % 4];
                else
                    $data.= $frame_data;

            }else
                continue;

        }while(!$final);

        return $data;
    }

    public static function saveNaMiApiPageData( BaseModel $baseModel, $action, $sleep, $url, $queryParams = [], $method = 'GET')
    {
        if (!isset($queryParams['timeout'])) $queryParams['timeout'] = 60;
        if (!isset($queryParams['query']['user'])) $queryParams['query']['user'] = config('params.naMi.user');
        if (!isset($queryParams['query']['secret'])) $queryParams['query']['secret'] = config('params.naMi.secret');
        try {
            $client = new Client();
            $response = $client->request($method, $url, $queryParams);
            $data = json_decode($response->getBody()->getContents());
            $list = isset($data->results) && !empty($data->results) ? $data->results : [];

            if (!empty($list) && isset($data->query) && isset($data->query->max_id)) {
                echo "正在转储数据，max_id={$data->query->max_id}！\r\n";
                foreach ($list as $row) {
					print_r($row);
                    $baseModel->$action($row);
                }
                echo "数据转储完毕，max_id={$data->query->max_id}！\r\n";
                sleep($sleep);
                $queryParams['query']['id'] = ++$data->query->max_id;
                unset($response, $data, $list);
                self::saveNaMiApiPageData($baseModel, $action, $sleep, $url, $queryParams, $method);
            }
        } catch (\Exception $e) {
            echo "{$e->getMessage()} ， file:{$e->getFile()}，line:{$e->getLine()}\r\n";
            sleep(1);
            self::saveNaMiApiPageData($baseModel, $action, $sleep, $url, $queryParams, $method);
        }

    }

    public static function saveNaMiApiData(BaseModel $baseModel, $action, $url, $queryParams = [], $method = 'GET')
    {
        if (!isset($queryParams['timeout'])) $queryParams['timeout'] = 60;
        if (!isset($queryParams['query']['user'])) $queryParams['query']['user'] = config('params.naMi.user');
        if (!isset($queryParams['query']['secret'])) $queryParams['query']['secret'] = config('params.naMi.secret');
        $client = new Client();
        $response = $client->request($method, $url, $queryParams);
        $data = json_decode($response->getBody()->getContents());
        $list = isset($data->results) && !empty($data->results) ? $data->results : [];
        if (empty($list)) return;
        foreach ($list as $row) {
            $baseModel->$action($row);
        }
    }

    public static function saveNaMiApiList(BaseModel $baseModel, $action, $url, $queryParams = [], $method = 'GET')
    {
        if (!isset($queryParams['timeout'])) $queryParams['timeout'] = 60;
        if (!isset($queryParams['query']['user'])) $queryParams['query']['user'] = config('params.naMi.user');
        if (!isset($queryParams['query']['secret'])) $queryParams['query']['secret'] = config('params.naMi.secret');
        $client = new Client();
        $response = $client->request($method, $url, $queryParams);		
        $list = json_decode($response->getBody()->getContents());
        if (empty($list)) return;
        foreach ($list->results as $row) {
			print_r($row);
            $baseModel->$action($row);
        }
    }

    public static function getBeforeAfterDates()
    {
        for($i = -15; $i <= -1; $i++) {
            $dates[] = date("Ymd", strtotime("{$i} day"));
        }
        $dates[] = date('Ymd', time());

        for($i = 1; $i <= 15; $i++) {
            $dates[] = date("Ymd", strtotime("+{$i} day"));
        }
        return $dates;
    }

    /**
     * @param string $day
     * @param string $format
     * @return false|int
     */
    public static function getBeforeAfterDayEndTimestamp($day, $format = 'Y-m-d 23:59:59')
    {
        return strtotime(date($format, strtotime("{$day} day")));
    }

    public static function getDateTimestamps($date)
    {
        $dateStartTime = "{$date} 00:00:00";
        $dateEndTime = "{$date} 23:59:59";
        $data['date_start_time'] = strtotime($dateStartTime);
        $data['date_end_time'] = strtotime($dateEndTime);
        return $data;
    }

    public static function predis()
    {
        return new \Predis\Client([
            'scheme' => 'tcp',
            'host' => env('REDIS_HOST'),
            'port' => env('REDIS_PORT'),
            'password' => env('REDIS_PASSWORD'),
        ]);
    }
}
