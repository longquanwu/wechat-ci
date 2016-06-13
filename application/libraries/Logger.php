<?php
/**
 * Logger.php
 * User: wlq314@qq.com
 * Date: 16/6/13 Time: 11:32
 */

class Logger{

    public function __construct(array $loggerInfo){
        Seaslog::setbasepath($loggerInfo['basepath']);
        SeasLog::setLogger($loggerInfo['logger']);
    }

    /**
     * @param $message
     * @param array $content
     * @param string $module
     */
    public function debug($message, array $content = [], $module = ''){
        return SeasLog::debug("\n".$message, $content, $module);
    }

    /**
     * @param $message
     * @param array $content
     * @param string $module
     */
    public function info($message, array $content = [], $module = ''){
        return SeasLog::info("\n".$message, $content, $module);
    }

    /**
     * @param $message
     * @param array $content
     * @param string $module
     */
    public function notice($message, array $content = [], $module = ''){
        return SeasLog::notice("\n".$message, $content, $module);
    }

    /**
     * @param $message
     * @param array $content
     * @param string $module
     */
    public function warning($message, array $content = [], $module = ''){
        return SeasLog::warning("\n".$message, $content, $module);
    }

    /**
     * @param $message
     * @param array $content
     * @param string $module
     */
    public function error($message, array $content = [], $module = ''){
        return SeasLog::error("\n".$message, $content, $module);
    }

    /**
     * @param $message
     * @param array $content
     * @param string $module
     */
    public function critical($message, array $content = [], $module = ''){
        return SeasLog::critical("\n".$message, $content, $module);
    }

    /**
     * @param $message
     * @param array $content
     * @param string $module
     */
    public function alert($message, array $content = [], $module = ''){
        return SeasLog::alert("\n".$message, $content, $module);
    }

    /**
     * @param $message
     * @param array $content
     * @param string $module
     */
    public function emergency($message, array $content = [], $module = ''){
        return SeasLog::emergency("\n".$message, $content, $module);
    }

    /**
     * @param $level
     * @param $message
     * @param array $content
     * @param string $module
     */
    public function log($level, $message, array $content = [], $module = ''){
        return SeasLog::log($level, "\n".$message, $content, $module);
    }

}