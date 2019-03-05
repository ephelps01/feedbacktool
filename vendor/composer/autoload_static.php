<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb595473b5cb64a030e284a49e6ef1e4b
{
    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'Ecd\\Feedbacktool\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Ecd\\Feedbacktool\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb595473b5cb64a030e284a49e6ef1e4b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb595473b5cb64a030e284a49e6ef1e4b::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}