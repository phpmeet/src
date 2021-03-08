<?php

namespace app\admin;

use core\Controller;

class FuController extends Controller
{

    public function index(){
        //$_SERVER['REMOTE_ADDR'];
        $this->display();
    }
}