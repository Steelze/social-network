<?php
namespace app;

class Session {
    public static function exists(String $key)
    {
        return (isset($_SESSION[$key])) ? true : false;
    }

    public static function get(String $key)
    {
        return $_SESSION[$key];
    }

    public static function put(String $key, $value)
    {
        return $_SESSION[$key] = $value;
    }

    public static function delete(String $key)
    {
        if (self::exists($key)) {
            unset($_SESSION[$key]);
        }
    }
}