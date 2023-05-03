<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1c513e6a909f69bf991de718a860a7b6
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WebPConvert\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WebPConvert\\' => 
        array (
            0 => __DIR__ . '/..' . '/rosell-dk/webp-convert/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1c513e6a909f69bf991de718a860a7b6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1c513e6a909f69bf991de718a860a7b6::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}