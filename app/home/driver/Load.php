<?php

namespace app\home;

use core\Driver;

class Load extends Driver
{
    /**
     * 执行顺序  before => init => constroller =>after
     */

    public function __construct()
    {
        parent::__construct();
    }


    public function before()
    {
       // echo "before<br/>";
    }

    public function init()
    {
       // echo "init<br/>";
    }

    public function after()
    {
       // echo "after<br/>";
    }
}