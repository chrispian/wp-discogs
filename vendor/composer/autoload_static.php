<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6cc7fe57bb30c32c45eb6a0d0904ac55
{
    public static $files = array (
        'ad155f8f1cf0d418fe49e248db8c661b' => __DIR__ . '/..' . '/react/promise/src/functions_include.php',
    );

    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'React\\Promise\\' => 14,
        ),
        'G' => 
        array (
            'GuzzleHttp\\Subscriber\\Oauth\\' => 28,
            'GuzzleHttp\\Stream\\' => 18,
            'GuzzleHttp\\Ring\\' => 16,
            'GuzzleHttp\\Command\\Guzzle\\' => 26,
            'GuzzleHttp\\Command\\' => 19,
            'GuzzleHttp\\' => 11,
        ),
        'C' => 
        array (
            'CHB_WP_Discogs\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'React\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/promise/src',
        ),
        'GuzzleHttp\\Subscriber\\Oauth\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/oauth-subscriber/src',
        ),
        'GuzzleHttp\\Stream\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/streams/src',
        ),
        'GuzzleHttp\\Ring\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/ringphp/src',
        ),
        'GuzzleHttp\\Command\\Guzzle\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/guzzle-services/src',
        ),
        'GuzzleHttp\\Command\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/command/src',
        ),
        'GuzzleHttp\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/guzzle/src',
        ),
        'CHB_WP_Discogs\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'D' => 
        array (
            'Discogs' => 
            array (
                0 => __DIR__ . '/..' . '/ricbra/php-discogs-api/lib',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6cc7fe57bb30c32c45eb6a0d0904ac55::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6cc7fe57bb30c32c45eb6a0d0904ac55::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit6cc7fe57bb30c32c45eb6a0d0904ac55::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
