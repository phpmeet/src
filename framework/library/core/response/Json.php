<?php
/**
 * Created by PhpStorm.
 * Date: 2020/7/20
 * Time: 14:36
 */

namespace core\response;

use core\Response;

class Json extends Response
{
    public static function render($data)
    {
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        return Response::create(200, $data)->send();
    }
}