<?php
namespace app\home;

use core\Controller;
use core\Template;

class IndexController extends Controller{
    public function index(){
        $html = "<body><div style='font-size:20px;font-weight:500;text-align: center'>phpmeet</div></body>";
        echo $html;
        die;
    }

}
