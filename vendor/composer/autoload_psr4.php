<?php

// autoload_psr4.php @generated by Composer

$vendorDir = dirname(__DIR__);
$baseDir = dirname($vendorDir);

return array(
    'ThemePlate\\Core\\' => array($vendorDir . '/themeplate/core'),
    'ThemePlate\\' => array($vendorDir . '/themeplate/enqueue', $vendorDir . '/themeplate/logger/src', $vendorDir . '/themeplate/page', $vendorDir . '/themeplate/settings'),
    'Psr\\Log\\' => array($vendorDir . '/psr/log/Psr/Log'),
    'Psr\\Http\\Message\\' => array($vendorDir . '/psr/http-factory/src', $vendorDir . '/psr/http-message/src'),
    'Psr\\Http\\Client\\' => array($vendorDir . '/psr/http-client/src'),
    'PBWebDev\\CardanoPress\\ISPO\\' => array($baseDir . '/src'),
    'Monolog\\' => array($vendorDir . '/monolog/monolog/src/Monolog'),
    'GuzzleHttp\\Psr7\\' => array($vendorDir . '/guzzlehttp/psr7/src'),
    'GuzzleHttp\\Promise\\' => array($vendorDir . '/guzzlehttp/promises/src'),
    'GuzzleHttp\\' => array($vendorDir . '/guzzlehttp/guzzle/src'),
    'CardanoPress\\' => array($vendorDir . '/cardanopress/framework/src'),
);
