<?php
namespace app\admin;

use core\Controller;

class BaseController extends Controller{

    public function initialize()
    {
        var_dump("initialize");
    }
}