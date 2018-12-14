<?php
namespace app;
/**
 * undocumented class
 */
class Hash
{
    public static function check(String $password, $hash)
    {
        return (password_verify($password, $hash)) ? true : false;
    }

    public static function make(String $value)
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }
}
