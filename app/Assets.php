<?php
namespace app;

/**
 * undocumented class
 */
class Assets
{
    public static function url(String $path)
    {
        return PROOT.'assets/'.$path;
    }
}
