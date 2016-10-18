<?php

use Slim\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
//初始化一些常量
define("DS", DIRECTORY_SEPARATOR);
define("VIEW", dirname(__FILE__) .DS."view" .DS);
// DIC configuration
$container = \Zank\Application::getContainer();

// monolog
$container['logger'] = function (Container $c): \Monolog\Logger {
    $settings = $c->get('settings')['logger'];
    $logger = new \Monolog\Logger($settings['name']);
    $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));

    return $logger;
};

// Service factory for the Eloquent ORM.
$container['db'] = function (Container $c): \Illuminate\Database\Capsule\Manager {
    $settings = $c->get('settings')->get('db');
    $settings = $settings['connections'][$settings['default']];
    $capsule = new \Illuminate\Database\Capsule\Manager();
    $capsule->addConnection($settings);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

// Service aliyun oss.
$container['oss'] = function (Container $c) {
    $settings = $c->get('settings')->get('oss');

    $oss = new \Medz\Component\StreamWrapper\AliyunOSS\AliyunOSS($settings['accessKeyId'], $settings['accessKeySecret'], $settings['endpoint']);
    $oss->setBucket($settings['bucket']);
    $oss->registerStreamWrapper('oss');

    return $oss;
};


$container['view'] = function (Container $c) {
    $settings = $c->get('settings')->get('view');
    
    $view = new \Slim\Views\Twig(dirname(__FILE__) .DS. $settings['templates'], [
        //'cache' => dirname(__FILE__).'/cache'
        'cache' => false
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $c['router'],
        $c['request']->getUri()
    ));

    return $view;
};