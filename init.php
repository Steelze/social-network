<?php
    require "vendor/autoload.php";
    session_start();
    define('DS', DIRECTORY_SEPARATOR);
    define('ROOT', __DIR__);
    //change to '/' for live
    define('PROOT', '/social-network/');

    $GLOBALS['config'] = [
        'mysql' => [
            'host' => '127.0.0.1', 
            'username' => 'root',
            'password' => '',
            'db' => 'social',
        ],
        'salt' => '$%fgrt@!h454*&t',
        'session' => 'user',
        'token' => 'token',
    ];

    function dd($var = null)
    {
        var_dump($var);
        die();
    }