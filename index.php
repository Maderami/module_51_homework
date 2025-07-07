<?php

require __DIR__ . '/vendor/autoload.php';

use Core\Configs\Config;
use Core\Lib\Router;


$twig = new Config()->getConfig();


// Маршрутизация
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$GLOBALS['request'] = $request;


switch ($request) {
    case '/':
    case '/home':
        $routeMain = new Router('MainController', 'index', 'HTML');
        $routeMain->run($twig);
        break;
    case '/remove':
        $routeMain = new Router('MainController', 'removerTags', 'HTML');
        $routeMain->run($twig);
        break;
    default:
        header('HTTP/1.0 404 Not Found');
        echo $twig->render('404.twig');
        break;
}