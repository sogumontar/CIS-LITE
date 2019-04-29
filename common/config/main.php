<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'menuControl' => [
            'class' => 'common\components\MenuControl',
            'containerElementId' => '#main-content',
        ],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'appendTimestamp' => true,
            // 'linkAssets' => true,
        ],  
        'debugger' => [
            'class' => 'common\components\Debugger',
        ],
        'appConfig' => [
            'class' => 'common\components\AppConfig',
            'isDebugging' => false,
        ],
        'userConfig' => [
            'class' => 'common\components\UserConfigManager',
            'isDebugging' => false,
        ],  
        'formatter' => [
            // 'dateFormat' => 'dd m yyyy',
            'decimalSeparator' => '.',
            'currencyCode' => 'IDR',
        ],
    ],
];
