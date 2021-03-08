<?php
/**
 * Created by PhpStorm.
 * Date: 2019/12/12
 * Time: 18:09
 */

namespace app\admin;

use core\Driver;
use http\Env\Request;

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
        echo "admin before<br/>";
    }

    public function init()
    {
        echo "admin init<br/>";
    }

    public function after()
    {
        echo "admin after<br/>";
    }
}