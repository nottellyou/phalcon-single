<?php
use Phalcon\Logger;
use Phalcon\Logger\Formatter\Line as LineFormatter;
use Phalcon\Logger\Adapter\File as FileAdapter;

/*
 * deprecated
 */


class LogsLib {

public function logs($message='')
{
    if(empty($message)){
      return ;
    }
    $log_dir = '../Runtime/log/'.date('Y-m');
    if(!file_exists($log_dir)){
      mkdir($log_dir, 0700,  true);
    }

    $logger = new FileAdapter($log_dir."/".date('d').".log");  //初始化文件地址
    // 修改日志格式
    $time   = date('Y-m-d H:i:s');
    $formatter = new LineFormatter("[{$time}] - [%message%]");
    $logger->setFormatter($formatter);
    $logger->error($message);
}

}
