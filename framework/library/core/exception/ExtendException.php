<?php
/**
 * Created by PhpStorm.
 * Date: 2019/11/26
 * Time: 19:07
 */

namespace core\exception;

use Throwable;

class ExtendException extends \Exception
{

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}