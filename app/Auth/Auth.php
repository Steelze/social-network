<?php
namespace app\Auth;

use app\Session;
use app\Config;


class Auth
{
    public static function check()
    {
       if (Session::exists(Config::get('user')) && Session::exists('loggedIn') && Session::get('loggedIn') === true) {
           return true;
       }
       return false;
    }

    public static function user()
    {
        if (self::check()) {
            return Session::get(Config::get('user'));
        }
        return false;
    }

    public static function logout()
    {
        return Session::destroy();
    }
}
