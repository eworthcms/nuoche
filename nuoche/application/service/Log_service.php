<?php

/**
 * 日志写入类
 * content 日志内容
 * log_name 日志名称
 */
class Log_service extends MY_Service {

    /**
	 * 获取access_token
	 */
    public function put_log($content,$log_name)
    {
        $path = config_item('put_log_path');//日志url
        if(!file_exists($path)){
            mkdir($path);
        }
        $log_name = date('Y-m-d',time()).$log_name;
        $content['time']=date('Y-m-d H:i:s',time());

        file_put_contents($path.$log_name,json_encode($content).PHP_EOL,FILE_APPEND);
    }
}
