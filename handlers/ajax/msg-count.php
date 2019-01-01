<?php
require_once '../require.php';

use app\Input;
use app\Token;
use app\Model\Post;
use app\Redirect;
use app\Model\Message;

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $message = new Message();
        $message->allViewed();
    }
} else {
    Redirect::to('index');
}