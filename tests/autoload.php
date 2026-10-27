<?php
// ensure we get report on all possible php errors
error_reporting(-1);

define('YII_ENV', 'test');
defined('YII_DEBUG') or define('YII_DEBUG', true);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

\Yii::setAlias('@runtime', __DIR__ . '/runtime');

new \yii\console\Application([
    'id' => 'queue-mailer-tests',
    'basePath' => dirname(__DIR__),
    'vendorPath' => dirname(__DIR__) . '/vendor',
    'runtimePath' => __DIR__ . '/runtime',
    'components' => [
        'log' => [
            'targets' => [],
        ],
    ],
]);
