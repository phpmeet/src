<?php
/**
 * Created by PhpStorm.
 * Date: 2020/7/20
 * Time: 17:25
 */

namespace core\exception;

class ThrowableError extends \ErrorException
{

    public function __construct(\Throwable $error)
    {
        if ($error instanceof \ParseError) {
            $message  = "Parse error:" . $error->getMessage();
            $severity = E_PARSE;
        } elseif ($error instanceof \TypeError) {
            $message  = "Type error:" . $error->getMessage();
            $severity = E_RECOVERABLE_ERROR;
        } else {
            $message  = "Fatal error:" . $error->getMessage();
            $severity = E_ERROR;
        }
        parent::__construct(
            $message,
            $error->getCode(),
            $severity,
            $error->getFile(),
            $error->getLine()
        );
        $this->setTrace($error->getTrace());
    }

    protected function setTrace($trace)
    {
        $traceReflector = new \ReflectionProperty('Exception','trace');
        $traceReflector->setAccessible(true);
        $traceReflector->setValue($this,$trace);
    }
}