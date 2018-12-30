<?php
require_once '../require.php';
use app\Input;
use app\Token;
use app\Redirect;
use app\Validation;
use app\Model\User;

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        if (Input::get('add')) {
            $user = new User();
            $user->removeFriend(Input::get('id'));
            Redirect::to(Input::get('username'), [], false);
        }
    }
}

Redirect::to('index');