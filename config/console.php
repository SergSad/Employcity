<?php

use app\application\request\ClientRequestService;
use app\domain\NewsRepositoryInterface;
use app\domain\NewsServiceInterface;
use app\repository\NewsARRepository;
use app\services\NewsRbkService;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
    'container' => [
        'definitions' => [
            NewsRepositoryInterface::class => NewsARRepository::class,
            DOMDocument::class => DOMDocument::class,
            ClientInterface::class => Client::class,
        ],
        'singletons' => [
            ClientRequestService::class => function ($container, $params, $config): ClientRequestService {
                return new ClientRequestService($container->get(ClientInterface::class));
            },
            NewsServiceInterface::class => function ($container, $params, $config): NewsServiceInterface {
                return new NewsRbkService(
                    $container->get(NewsRepositoryInterface::class),
                    $container->get(ClientRequestService::class),
                    $container->get(DOMDocument::class),
                );
            }
        ]
    ]
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
