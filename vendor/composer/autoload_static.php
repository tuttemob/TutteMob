<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0d3f6f35c8273e946a5d6db536201262
{
    public static $files = array (
        '34901568a1e26d13dd475cb2c85e0284' => __DIR__ . '/..' . '/zendframework/zend-form/autoload/formElementManagerPolyfill.php',
        'c65d09b6820da036953a371c8c73a9b1' => __DIR__ . '/..' . '/facebook/graph-sdk/src/Facebook/polyfills.php',
    );

    public static $prefixLengthsPsr4 = array (
        'Z' => 
        array (
            'Zend\\View\\' => 10,
            'Zend\\Validator\\' => 15,
            'Zend\\Uri\\' => 9,
            'Zend\\Stdlib\\' => 12,
            'Zend\\Session\\' => 13,
            'Zend\\ServiceManager\\' => 20,
            'Zend\\Router\\' => 12,
            'Zend\\Paginator\\' => 15,
            'Zend\\Mvc\\' => 9,
            'Zend\\ModuleManager\\' => 19,
            'Zend\\Mime\\' => 10,
            'Zend\\Mail\\' => 10,
            'Zend\\Loader\\' => 12,
            'Zend\\Json\\' => 10,
            'Zend\\InputFilter\\' => 17,
            'Zend\\Hydrator\\' => 14,
            'Zend\\Http\\' => 10,
            'Zend\\Form\\' => 10,
            'Zend\\Filter\\' => 12,
            'Zend\\EventManager\\' => 18,
            'Zend\\Escaper\\' => 13,
            'Zend\\Db\\' => 8,
            'Zend\\Config\\' => 12,
            'Zend\\ComponentInstaller\\' => 24,
            'Zend\\Authentication\\' => 20,
        ),
        'P' => 
        array (
            'Psr\\Container\\' => 14,
        ),
        'I' => 
        array (
            'Interop\\Container\\' => 18,
        ),
        'F' => 
        array (
            'Facebook\\' => 9,
        ),
        'A' => 
        array (
            'Application\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Zend\\View\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-view/src',
        ),
        'Zend\\Validator\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-validator/src',
        ),
        'Zend\\Uri\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-uri/src',
        ),
        'Zend\\Stdlib\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-stdlib/src',
        ),
        'Zend\\Session\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-session/src',
        ),
        'Zend\\ServiceManager\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-servicemanager/src',
        ),
        'Zend\\Router\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-router/src',
        ),
        'Zend\\Paginator\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-paginator/src',
        ),
        'Zend\\Mvc\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-mvc/src',
        ),
        'Zend\\ModuleManager\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-modulemanager/src',
        ),
        'Zend\\Mime\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-mime/src',
        ),
        'Zend\\Mail\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-mail/src',
        ),
        'Zend\\Loader\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-loader/src',
        ),
        'Zend\\Json\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-json/src',
        ),
        'Zend\\InputFilter\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-inputfilter/src',
        ),
        'Zend\\Hydrator\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-hydrator/src',
        ),
        'Zend\\Http\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-http/src',
        ),
        'Zend\\Form\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-form/src',
        ),
        'Zend\\Filter\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-filter/src',
        ),
        'Zend\\EventManager\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-eventmanager/src',
        ),
        'Zend\\Escaper\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-escaper/src',
        ),
        'Zend\\Db\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-db/src',
        ),
        'Zend\\Config\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-config/src',
        ),
        'Zend\\ComponentInstaller\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-component-installer/src',
        ),
        'Zend\\Authentication\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-authentication/src',
        ),
        'Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/container/src',
        ),
        'Interop\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/container-interop/container-interop/src/Interop/Container',
        ),
        'Facebook\\' => 
        array (
            0 => __DIR__ . '/..' . '/facebook/graph-sdk/src/Facebook',
        ),
        'Application\\' => 
        array (
            0 => __DIR__ . '/../..' . '/module/Application/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0d3f6f35c8273e946a5d6db536201262::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0d3f6f35c8273e946a5d6db536201262::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
