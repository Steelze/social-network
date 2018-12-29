<?php
require_once '../require.php';
use app\Input;
use app\Redirect;
use app\Session;
use app\Validation;
use app\Token;
use app\Model\Post;
use app\Model\Comment;

if (Input::exists()) {
    if (Input::get('new-comment')) {
        if (Token::check(Input::get('token'))) {
            $validation = new Validation();
            $validate = $validation->check(
                [
                    'post-id' => [
                        'required' => true,
                    ],
                    'comment' => [
                        'required' => true,
                    ],
                    [
                        'comment' => [
                            'required' => 'Are you not thinking of anything',
                        ]
                    ]
                ]
            );
            if (!$validate->passed()) {
                // Session::flash('errors', $validation->errors());
                Redirect::to('index');
            }
            
            $comment = new Comment();
            $comment->create(Input::get('post-id'), Input::get('comment'));
            // Session::flash('msg', 'New Comment Added');
            Redirect::to('index');
        }
    }
}

Redirect::to('index');