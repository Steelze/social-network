<?php
require_once 'init.php';

use app\Config;
use app\Session;

if (Session::exists(Config::get('user'))) {
    dd(Session::get(Config::get('user')));
}