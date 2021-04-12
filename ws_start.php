<?php
require_once './vendor/autoload.php';

use Workerman\Worker;

define("MAX_SHOW", 8192);
$ws_worker = new Worker("websocket://0.0.0.0:8936");

// 启动4个进程对外提供服务
$ws_worker->count = 4;

$ws_worker->onMessage = function ($connection, $data) {
    if ($data == "tom") $connection->send("连接成功。");
    // 定义要查看的log文件路径
    $file_name = "/www/wwwlogs/access.log";
    $connection->send("日志文件：" . $file_name);
    $file_size = filesize($file_name);
    $file_size_new = 0;
    $add_size = 0;
    $ignore_size = 0;
    $fp = fopen($file_name, "r");
    fseek($fp, $file_size);
    $num = 0;
    while (1) {
        $num++;
        clearstatcache();
        $file_size_new = filesize($file_name);
        $add_size = $file_size_new - $file_size;
        if ($add_size > 0) {
            if ($add_size > MAX_SHOW) {
                $ignore_size = $add_size - MAX_SHOW;
                $add_size = MAX_SHOW;
                fseek($fp, $file_size + $ignore_size);
            }
            $connection->send(fread($fp, $add_size));
            $file_size = $file_size_new;
        }
        usleep(50000);
        if ($num > 20) break;
    }
    fclose($fp);
};


// 运行worker
Worker::runAll();
