<?php
namespace app;

class Input
{
    public static function exists(String $method = 'post')
    {
        switch ($method) {
        case 'post':
            return (!empty($_POST)) ? true : false;
                break;
            
        case 'get':
            return (!empty($_GET)) ? true : false;
                break;
            
        default:
            return false;
                break;
        }
    }
    
    public static function get(String $value)
    {
        if (isset($_POST[$value])) {
            return sanitize($_POST[$value]);
        } elseif (isset($_GET[$value])) {
            return sanitize($_GET[$value]);
        } else {
            return '';
        }
    }

    public static function raw(String $value)
    {
        if (isset($_POST[$value])) {
            return $_POST[$value];
        } elseif (isset($_GET[$value])) {
            return $_GET[$value];
        } else {
            return '';
        }
    }

    public static function old(String $value)
    {
        $data = "";
        if (Session::exists($value)) {
            $data = Session::get(($value));
            Session::delete($value);
        }
        return $data;
    }
    
    public static function old_exist(String $value)
    {
        return (Session::exists($value)) ? true : false;
    }
}
