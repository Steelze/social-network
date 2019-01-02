<?php
require_once '../require.php';

use app\Input;
use app\Token;
use app\Model\Post;
use app\Redirect;
use app\Model\Notification;

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $notification = new Notification();
        $notification->allViewed();
    }
} else {
    Redirect::to('index');
}