<?php

declare(strict_types=1);

require_once(__DIR__ . '/../vendor/autoload.php');

require(__DIR__ . '/config_loading_include.php');

\Fc2blog\Config::read('user.php'); // User用の環境設定読み込み

\Fc2blog\Debug::log('Controller Action', false, 'system', __FILE__, __LINE__);

list($className, $methodName) = getRouting();
$controller = new $className($methodName);

\Fc2blog\Debug::output($controller); // Debug用の出力
