<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbda174231f4d33cfbae64a4e63d8b6e0
{
    public static $prefixLengthsPsr4 = array (
        'i' => 
        array (
            'iutnc\\NRV\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'iutnc\\NRV\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/classes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbda174231f4d33cfbae64a4e63d8b6e0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbda174231f4d33cfbae64a4e63d8b6e0::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitbda174231f4d33cfbae64a4e63d8b6e0::$classMap;

        }, null, ClassLoader::class);
    }
}
