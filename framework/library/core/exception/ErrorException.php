<?php
/**
 * Created by PhpStorm.
 * Date: 2019/11/26
 * Time: 18:48
 */

namespace core\exception;


class ErrorException extends Exception
{

    public function __construct($code, $message, $file, $line)
    {
        $this->code = $code;
        $this->message = $message;
        $this->file = $file;
        $this->line = $line;
    }
}