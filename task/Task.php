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

    $database = config();
    global $db;
    $db  = new \Workerman\MySQL\Connection($database['hostname'], $database['hostport'], $database['username'], $database['password'], $database['database']);
    //1秒一次
    Timer::add(1,function(){
        global $db;

        $row = $db->select('id')->from('cmf_user')->where("coin= 1 ")->row();
        print($row);

        //
        $res = $db->query('select *  from cmf_user');  
        print_r($res);
        //插入
        /*
        $db->insert('cmf_user')->cols(array(
                'user_type' => 1,
                'user_nicename'=>'self'
        ))->query();
        */


     
    });
    Timer::add(1,function(){

    });
};
// 运行worker
Worker::runAll();