<?php
    require_once "vendor/autoload.php";
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
        'user' => 'user',
        'token' => 'token',
        'trim-exception' => [
            'password',
            'password_confirmation',
        ],
        "PUSHER_APP_ID" => 696751,
        "PUSHER_APP_KEY" => "59e09d88562498656e92",
        "PUSHER_APP_SECRET" => "91a04ba775cd48387c18",
        "PUSHER_APP_CLUSTER" => "mt1",
        "PUSHER_APP_USE_TLS" => true,
    ];

    function dd($var = null)
    {
        var_dump($var);
        die();
    }

    function sanitize($data ){
	    return htmlentities(trim($data), ENT_QUOTES,"UTF-8" );
    }
    