<?php
namespace app;

/**
 * undocumented class
 */
class Layouts
{
    public static function includes(String $path = null)
    {
        if ($path) {
            $path = implode('/', explode('.', $path));
            $path = 'includes/'.$path.'.php';
            return $_SERVER['DOCUMENT_ROOT'].PROOT.$path;
        }
    }
}
