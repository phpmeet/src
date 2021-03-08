<?php
/**
 * Created by PhpStorm.
 * Date: 2019/11/22
 * Time: 17:19
 */

namespace app\admin;

use core\Controller;
use core\Request;
use core\Template;

class IndexController extends BaseController
{
    use IndexTrait;

    public function filter()
    {

    }

    public function login()
    {
        echo "login";
        die;
    }

    public function index()
    {
        var_dump(getClientIp());
        var_dump(isWap());
        Template::loadTemplate('header');
        Template::loadTemplate('header.index');
        $param = Request::getInstance()->get();
        $this->assign('game', 1);
        $this->assign('name', 2);
        $this->assign('Site', 3);
        $this->assign('tip', 4);
        $this->assign('Tip', 5);
        $this->assign('user', 7);
        $this->assign('fu', [9,10]);
        $this->display();
    }

    public function detail(){
        echo "this is detail";
    }
}