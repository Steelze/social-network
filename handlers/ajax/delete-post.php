<?php
require_once '../require.php';

use app\Input;
use app\Token;
use app\Model\Post;
use app\Redirect;
use app\Auth\Auth;

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $post = new Post();
        $data = $post->find(Input::get('id'));
        if ($data->user_id === Auth::user()->id) {
            $post->delete(Input::get('id'));
        }
    }
} else {
    Redirect::to('index');
}