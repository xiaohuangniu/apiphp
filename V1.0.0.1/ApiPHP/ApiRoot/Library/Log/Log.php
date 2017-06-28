<?php
/*
 +--------------------------------------------------------------------------------------------------
 + Title        : Api框架 异常错误处理
 + Author       : 极资源首席工程师 - 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2017-06-26 12:49
 + Last-time    : 2017-06-26 12:49 + 小黄牛
 + Desc         : 主要用于压制默认的PHP异常错误机制,并将错误以状态码的形式输出到浏览器上
 +--------------------------------------------------------------------------------------------------
*/

class Log{
    
	/**
     * 应用程序初始化
	 */
	public function __construct() {}
	static public function start(){ 
		register_shutdown_function('Log::fatalError');
    	set_error_handler('Log::appError');
    	set_exception_handler('Log::appException');
    }

	/**
     * 自定义异常处理
     * @access public
     * @param mixed $e 异常对象
     */
    static public function appException($e='') {
		$e = new Exception;
        $error = array();
        $error['message']   =   $e->getMessage();
        $trace              =   $e->getTrace();
        if('E'==$trace[0]['function']) {
            $error['file']  =   $trace[0]['file'];
            $error['line']  =   $trace[0]['line'];
        }else{
            $error['file']  =   $e->getFile();
            $error['line']  =   $e->getLine();
        }
        $error['trace']     =   $e->getTraceAsString();
        // 发送404信息
        header('HTTP/1.1 404 Not Found');
        header('Status:404 Not Found');
        self::halt($error);
    }
	
	/**
     * 自定义错误处理
     * @access public
     * @param int $errno 错误类型
     * @param string $errstr 错误信息
     * @param string $errfile 错误文件
     * @param int $errline 错误行数
     * @return void
     */
    static public function appError($errno, $errstr, $errfile, $errline) {
      switch ($errno) {
          case E_ERROR:
          case E_PARSE:
          case E_CORE_ERROR:
          case E_COMPILE_ERROR:
          case E_USER_ERROR:
            ob_end_clean();
            $errorStr = "$errstr ".$errfile." 第 $errline 行.";
            self::halt($errorStr);
            break;
          default:
            $errorStr = "[$errno] $errstr ".$errfile." 第 $errline 行.";
            break;
      }
    }

	// 致命错误捕获
    static public function fatalError() {
        if ($e = error_get_last()) {
            switch($e['type']){
              case E_ERROR:
              case E_PARSE:
              case E_CORE_ERROR:
              case E_COMPILE_ERROR:
              case E_USER_ERROR:  
                ob_end_clean();
                self::halt($e);
                break;
            }
        }
    }

	/**
     * 错误输出
     * @param mixed $error 错误
     * @return void
     */
    static public function halt($error) {
        $e = array();

		# 开启调试模式则打印错误信息
        if (APP_DEBUG == true) {
            if (!is_array($error)) {
                $trace          = debug_backtrace();
                $e['message']   = $error;
                $e['file']      = $trace[0]['file'];
                $e['line']      = $trace[0]['line'];
                ob_start();
                debug_print_backtrace();
                $e['trace']     = ob_get_clean();
            } else {
                $e              = $error;
            }
			# 包含异常页面模板
        	$exceptionFile = config('LOG_TPL');
            
            include $exceptionFile;
            exit;
        }
        # 否则直接抛出json
        json(50000, '服务器错误'); 
    }
	
}