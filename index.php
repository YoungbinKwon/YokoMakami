<?php

define('ROOT_PATH', realpath(dirname(__FILE__)));

$includes = array(ROOT_PATH . '/Lib/db/db.php', ROOT_PATH . '/Lib/util', ROOT_PATH . '/Lib/mvc', ROOT_PATH . '/Model');
$incPath = implode(PATH_SEPARATOR, $includes);
set_include_path(get_include_path() . PATH_SEPARATOR . $incPath);

var_dump(ROOT_PATH);
var_dump(scandir(ROOT_PATH));
exit();

function __autoload($className){
    require_once $className . ".php";
}

$dispatcher = new Dispatcher();
$dispatcher->dispatch();