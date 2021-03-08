<?php

namespace core;

class Cli
{
    private static $intance = null;

    private $uri = null;
    private $param = [];

    public static function create()
    {
        if (!self::$intance) {
            self::$intance = new self();
        }
        return self::$intance;
    }

    public function uri()
    {
        return $this->uri;
    }

    public function param()
    {
        return $this->param;
    }

    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    public function setParam($param)
    {
        $this->param = $param;
        return $this;
    }
}