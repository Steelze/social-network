<?php
namespace app;

class Router {
    public static function route(String $route = null, Array $params = [], $suffix = true)
    {
        if ($route) {
            $route = PROOT.implode('/', explode('.', $route));
            if ($suffix) {
                $route .= '.php';
            }
            if (count($params)) {
                $i = 0;
                foreach ($params as $key => $value) {
                    if ($i === 0) {
                        $route .= '?'.$key.'='.$value;
                    } else {
                        $route .= '&'.$key.'='.$value;
                    }
                    $i++;
                }
            }
            return $route;
        }
    }
}