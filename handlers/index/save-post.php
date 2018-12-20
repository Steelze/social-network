<?php
require_once '../require.php';
use app\Input;
use app\Redirect;
use app\Session;
use app\Validation;
use app\Token;
use app\Model\Post;

if (Input::exists()) {
    if (Input::get('new-post')) {
        if (Token::check(Input::get('token'))) {
            $validation = new Validation();
            $validate = $validation->check(
                [
                    'post' => [
                        'required' => true,
                    ],
                    [
                        'post' => [
                            'required' => 'Are you not thinking of anything',
                        ]
                    ]
                ]
            );
            if (!$validate->passed()) {
                Session::flash('errors', $validation->errors());
                Redirect::to('index');
            }

            $post = new Post();
            $post->create(Input::get('post'));
            Session::flash('msg', 'New Post Added');
            Redirect::to('index');
        }
    }
}

Redirect::to('index');