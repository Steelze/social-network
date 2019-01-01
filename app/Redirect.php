<?php
namespace app;

class Redirect {
    public static function to($location = null, Array $params = [], $suffix = true)
    {
        if ($location) {
            if (is_numeric($location)) {
                switch ($location) {
                    case '404':
                    header('HTTP/1.1 404 Not Found');
                    include_once Layouts::includes('errors.404');
                    exit;
                    break;
                }
            }
            $location = PROOT.implode('/', explode('.', $location));
            if ($suffix) {
                $location .= '.php';
            }
            if (count($params)) {
                $i = 0;
                foreach ($params as $key => $value) {
                    if ($i === 0) {
                        $location .= '?'.$key.'='.$value;
                    } else {
                        $location .= '&'.$key.'='.$value;
                    }
                    $i++;
                }
            }
            header('Location: '.$location);
            exit;
            
        }
        # code...
    }
}