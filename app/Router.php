<?php
namespace app;

class Router {
    public static function route(String $route = null)
    {
        if ($route) {
            $route = implode('/', explode('.', $route));
            return PROOT.$route.'.php';
        }
    }
}