<?php
/**
 * Created by PhpStorm.
 * Date: 2020/7/20
 * Time: 14:41
 */

namespace core\response;

use core\Response;

class Redirect extends Response
{
    public function to($uri)
    {
        return Response::create(302, '')->redirect($uri);
    }
}