<?php
// Реализована автозагрузка классов

use application\core\Route;

spl_autoload_register(function($class) {
	$path = str_replace('\\', '/', $class . '.php');

	if (file_exists($path)) {
		require $path;
	}
});

$router = new Route();
$router->run();

