<?php
require_once '../require.php';
use app\Input;
use app\Token;
use app\Redirect;
use app\Validation;
use app\Model\User;

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        if (Input::get('ignore')) {
            $user = new User();
            $user->ignoreFriend(Input::get('id'));
            Redirect::to('friend-requests');
        }
    }
}

Redirect::to('index');