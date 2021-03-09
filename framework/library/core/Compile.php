<?php
/**
 * Created by PhpStorm.
 * Date: 2019/11/26
 * Time: 17:22
 */

namespace core;


class Compile
{

    private static $stance = null;

    private $template = [];

    private function __construct()
    {
        $this->template = Config::getInstance()->get('app.template');
    }

    public static function getInstance()
    {
        if (!self::$stance) {
            self::$stance = new self();
        }
        return self::$stance;
    }

    public function bootstrap($file, $cacheFile)
    {
        $data = File::getInstance()->read($file);
        $data = $this->_parse($data);
        File::getInstance()->write($cacheFile, $data);
    }

    private function _parse($string)
    {
        $string = $this->parseTag($string);
        $string = $this->parseParamTag($string);
        $string = $this->parseResource($string);
        $string = $this->parseFinal($string);
        return $string;
    }

    public function parseTag($string)
    {
        preg_match_all('/' . $this->template['pre'] . '(\$.*?)' . $this->template['suffix'] . '/', $string, $match);
        if (isset($match) && $match[0]) {
            foreach ($match[1] as $key => $item) {
                if (strpos($item, '.') !== false) {
                    $res = preg_replace("/\.(\w+)/is", '[\'$1\']$2', $item);
                } else {
                    $res = $item;
                }
                $string = str_replace($this->template['pre'] . $item . $this->template['suffix'], $this->getPhpStr($res, 1), $string);
            }
        }
        return $string;
    }

    public function parseParamTag($string)
    {
        preg_match_all('/' . $this->template['pre'] . '([if|foreach|switch|elseif|else|case].*?)' . $this->template['suffix'] . '/', $string, $match);
        if (isset($match[0]) && $match[0]) {
            foreach ($match[1] as $key => $item) {
                $tag = preg_match('/(if|foreach|switch|elseif|else|case).*/is', $item, $tagMatch);
                switch ($tagMatch[1]) {
                    case 'if':
                        $res = preg_replace('/([^if|^foreach|^switch|^else|^case])(.*)/is', '($2){', $item);
                        break;
                    case 'case':
                        $res = preg_replace('/^case(.*)/is', 'case $1:', $item);
                        break;
                    case 'elseif':
                        $res = preg_replace('/(elseif)(.*)/is', '}elseif($2){', $item);
                        break;
                    case 'else':
                        $res = '}else{';
                        break;
                    default:
                        $res = preg_replace('/([^if|^foreach|^switch|^else|^case])(.*)/is', '($2){', $item);
                }
                $string = str_replace($this->template['pre'] . $item . $this->template['suffix'], $this->getPhpStr($res), $string);
            }
        }
        return $string;
    }

    public function parseResource($string)
    {
        preg_match_all('/' . $this->template['pre'] . ':module(.*?)' . $this->template['suffix'] . '/', $string, $match);
        if (isset($match[0]) && $match[0]) {
            foreach ($match[0] as $key => $item) {
                $string = str_replace($item, $this->getPhpStr('echo $this->resourceModule' . $match[1][$key],1), $string);
            }
        }
        return $string;
    }

    public function parseFinal($string)
    {
        $string = str_replace($this->template['pre'] . '/foreach' . $this->template['suffix'], $this->getPhpStr('}'), $string);
        $string = str_replace($this->template['pre'] . '/if' . $this->template['suffix'], $this->getPhpStr('}'), $string);
        $string = str_replace($this->template['pre'] . '/case' . $this->template['suffix'], $this->getPhpStr('break;'), $string);
        $string = str_replace($this->template['pre'] . '/switch' . $this->template['suffix'], $this->getPhpStr('}'), $string);
        return $string;
    }

    public function getPhpStr($string, $type = 0)
    {
        $str = '<?php ' . $string;
        if ($type == 1) {
            $str .= ";";
        }
        $str .= '?>';
        return $str;
    }
}