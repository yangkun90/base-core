<?php
/* * @Author: 杨坤  */

namespace YkCmsTp5\exception;

use think\exception\Handle;
use think\facade\Log;
use think\facade\Request;
use think\facade\Env;

class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $error_code;

    //需要返回客户端当前请求的URL路径

    /**
     * @param \Exception $e
     * @return \think\Response|\think\response\Json
     */
    public function render(\Exception $e)
    {
        if ($e instanceof BaseException) {
            //如果是属于自定义的异常
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->error_code = $e->error_code;
        } else {
            if (config('app_debug')) {
                return parent::render($e);
            } else {
                $this->code = 500;
                $this->msg = '服務器內部錯誤，不想告訴你';
                $this->error_code = 999;
                $this->recordErrorLog($e);
            }
        }

        $result = [
            'code' => $this->error_code,
            'message' => $this->msg,
            'request' => Request::method().' '.Request::url()
        ];
        return json($result, $this->code);
    }

    private function recordErrorLog(\Exception $e)
    {
        Log::init([
            'type' => 'File',
            'path' => Env::get('root_path').'/runtime/log_error',
            'apart_level' => ['error'],
            'max_files' =>  30,
            'close' => false
        ]);

        Log::write($e->getMessage(), 'error');
        log::close();

    }
}