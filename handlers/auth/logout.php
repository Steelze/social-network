<?php
require_once '../require.php';
use app\Input;
use app\Redirect;
use app\Auth\Auth;
use app\Token;

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        Auth::logout();
    }
}

Redirect::to('index');