<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitbda174231f4d33cfbae64a4e63d8b6e0
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitbda174231f4d33cfbae64a4e63d8b6e0', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitbda174231f4d33cfbae64a4e63d8b6e0', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitbda174231f4d33cfbae64a4e63d8b6e0::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
