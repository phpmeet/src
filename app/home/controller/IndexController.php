<?php
namespace app\home;

use core\Controller;
use core\Template;

class IndexController extends Controller{
    public function index(){
        $html = "<body>
                  <style>
                     html,body{width: 100%;height:100%;margin: 0;padding: 0;}
                     .content{font-size:40px;font-weight:500;text-align: center;position: relative;top: 45%;}
                  </style>
                   <div class='content'>Hello Phpmeet</div>
                   <div class='content' style='font-size: 20px;margin-top: 10px'>一个由简出发的php框架</div>
                   </body>";
        echo $html;
        die;
    }

}
