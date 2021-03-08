<?php

//系统版本
define("VERSION", "1.0.0");
//系统根目录
define("ROOT", dirname(__DIR__));
//应用文件存放目录
define("APP_ROOT", ROOT . "/app");
//框架目录
define("FRAME_ROOT", ROOT . "/framework");
//静态文件
define("RESOURCE_ROOT", ROOT . "/public");
//缓存目录
define("CACHE_ROOT", FRAME_ROOT . "/cache");
//配置文件目录
define("CONFIG_ROOT", FRAME_ROOT . "/config");
//公共方法目录
define("FUNCTION_ROOT", FRAME_ROOT . "/common");
//系统库文件目录
define("LIB_ROOT", FRAME_ROOT . "/library");
//核心文件目录
define("CORE_ROOT", LIB_ROOT . "/core");
//路由目录
define("ROUTE_ROOT", FRAME_ROOT . "/route");
//后缀
define("EXT", ".php");

include CORE_ROOT . "/Loader.php";

\core\Loader::register();
\core\Loader::set([
    'app'   => \core\App::getInstance(),
    'file'  => \core\File::getInstance(),
    'log'   => \core\Log::getInstance(),
    'route' => \core\Route::getInstance()
]);
\core\exception\Error::register();
if (\core\Request::getInstance()->isCli()) {
    \core\Cli::create()->setUri($argv[1])->setParam($argv);
}

\core\App::getInstance()->run();

