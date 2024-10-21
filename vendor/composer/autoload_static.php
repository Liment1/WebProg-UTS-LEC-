<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit363c6dcd3f645e72771a8dacf6e58229
{
    public static $prefixLengthsPsr4 = array (
        'U' => 
        array (
            'User\\Lec\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'User\\Lec\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit363c6dcd3f645e72771a8dacf6e58229::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit363c6dcd3f645e72771a8dacf6e58229::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit363c6dcd3f645e72771a8dacf6e58229::$classMap;

        }, null, ClassLoader::class);
    }
}
