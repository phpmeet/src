<?php
/**
 * Created by PhpStorm.
 * Date: 2019/11/26
 * Time: 17:22
 */

namespace core;

class Controller
{
    private $var = [];

    public function __construct()
    {

    }

    protected function __filter()
    {

    }

    public function run()
    {
        $this->initialize();
    }

    public function initialize()
    {
    }

    public function assign($name, $val)
    {
        $this->var[$name] = $val;
    }

    public function display($fileName = '')
    {
        Template::getInstance()->load($this->var, $fileName)->driver();
    }
}