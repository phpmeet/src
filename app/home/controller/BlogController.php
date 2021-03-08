<?php

namespace app\home;

use core\Controller;
use core\Request;

class BlogController extends Controller
{
    public function detail($id,$type,$sex,ConfigMdl $configMdl,Request $request)
    {
        var_dump($id."-".$type."-".$sex);
        var_dump($request->get('age'));
        echo "this is blog detail<br/>";
        //$res = ConfigMdl::getOne(['id'=>35]);
        $res = $configMdl->getCount(['id'=>35]);
        var_dump($res);die;
    }
}