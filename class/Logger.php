<?php

class Logger
{
    static $log_dir = "logs";
    static $log_file = "log.log";
    private function __constructor(){
        //Singleton
    }
    static function log($string){
        if (!is_dir(self::$log_dir)){
            mkdir(self::$log_dir);
        }
        $file = fopen(self::$log_dir.DIRECTORY_SEPARATOR.self::$log_file,"a+");
        fwrite($file,date("Y-m-d H:i:s")." ".$string);
        fclose($file);
    }

}