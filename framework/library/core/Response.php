<?php
/**
 * Created by PhpStorm.
 * Date: 2020/7/20
 * Time: 13:49
 */

namespace core;

class Response
{
    protected $code = 200;

    protected $header = [];

    protected $data;

    protected $content = null;

    private function __construct($code, $data)
    {
        $this->code = $code;
        $this->data = $data;
    }

    public static function create($code, $data)
    {
        return new self($code, $data);
    }

    public function data($data)
    {
        $this->data = $data;
        return $this;
    }

    public function code($code)
    {
        $this->code = $code;
        return $this;
    }

    public function header($key, $val)
    {
        $this->header[$key] = $val;
        return $this;
    }

    public function send()
    {
        http_response_code($this->code);
        foreach ($this->header as $key => $item) {
            header($key . ":" . $item);
        }
        echo $this->data;
    }

    public function result()
    {
        return $this->send();
    }

    public function redirect($uri)
    {
        if ($this->code == 301) {
            header('HTTP/1.1 301 Moved Permanently');
        } else {
            header('HTTP/1.1 302 Found');
        }
        header('Location:' . $uri);
    }
}