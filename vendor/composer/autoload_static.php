<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7bd527fb436e0fe17546e04c9bcc7937
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'DiDom\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'DiDom\\' => 
        array (
            0 => __DIR__ . '/..' . '/imangazaliev/didom/src/DiDom',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7bd527fb436e0fe17546e04c9bcc7937::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7bd527fb436e0fe17546e04c9bcc7937::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7bd527fb436e0fe17546e04c9bcc7937::$classMap;

        }, null, ClassLoader::class);
    }
}