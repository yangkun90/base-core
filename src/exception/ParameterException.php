<?php
/* * @Author: 杨坤  */

namespace YkCmsTp5\exception;


class ParameterException extends BaseException
{
    public $code = 400;
    public $msg = '参数错误';
    public $error_code = 10030;
}