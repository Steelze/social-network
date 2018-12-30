<?php
require_once '../require.php';

use app\Input;
use app\Token;
use app\Validation;
use app\Model\Post;
use app\Redirect;
use app\Auth\Auth;

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validation = new Validation();
        $validate = $validation->check(
            [
                'id' => [
                    'required' => true,
                ],
                'post' => [
                    'required' => true,
                ],
                'username' => [
                    'required' => true,
                ],
            ]
        );
        if (!$validate->passed()) {
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json; charset=UTF-8');
            die('Oops an error occured');
        }
        $post = new Post();
        if (Auth::user()->id === Input::get('id')) {
            $post->create(Input::get('post'));
        } else {
            $post->create(Input::get('post'), Input::get('id'));
        }
        
        // header('HTTP/1.1 200 Content Ok');
        // header('Content-Type: application/json; charset=UTF-8');
        echo 'Saved';
    }
} else {
    Redirect::to('index');
}