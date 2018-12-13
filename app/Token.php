<?php
namespace app;

class Token {
    public static function check(String $token)
    {
        return (hash_equals(self::getToken(), $token));
    }

    private static function exists()
    {
        return (!Session::exists() || empty(Session::get(Config::get('token')))) ? true : false;
    }

    private static function generate()
    {
        return bin2hex(random_bytes(32));
    }

    public static function getToken()
    {
        return (self::exists()) ? Session::get(Config::get('token')) : self::setToken();
    }
    
    public static function setToken()
    {
        if (!self::exists()) {
            $token = self::generate();
            Session::put(Config::get('token'), $token);
            return $token;
        }
    }
}