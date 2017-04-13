<?php
date_default_timezone_set('Asia/Tokyo');

define('ROOT_PATH', realpath(dirname(__FILE__)));
require_once('env_var.php');

$includes = array(ROOT_PATH . '/Lib/db', ROOT_PATH . '/Lib/util', ROOT_PATH . '/Lib/mvc', ROOT_PATH . '/Lib/igo-php-0.1.7/lib', ROOT_PATH . '/Model');
$incPath = implode(PATH_SEPARATOR, $includes);
set_include_path(get_include_path() . PATH_SEPARATOR . $incPath);

function __autoload($className){
    if (stream_resolve_include_path($className . ".php")) {
        require_once $className . ".php";
    }
}

$dispatcher = new Dispatcher();
$dispatcher->dispatch();
