<?php
require_once '../require.php';

use app\Input;
use app\Token;
use app\Model\Post;
use app\Redirect;
use app\Model\Like;

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        if (Input::get('action') === 'like') {
            $like = new Like();
            $like->like(Input::get('id'));
        } elseif (Input::get('action') === 'unlike') {
            $like = new Like();
            $like->unlike(Input::get('id'));
        }
    }
} else {
    Redirect::to('index');
}