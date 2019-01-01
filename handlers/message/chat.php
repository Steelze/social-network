<?php
require_once '../require.php';
use app\Input;
use app\Token;
use app\Redirect;
use app\Validation;
use app\Model\User;
use app\Model\Message;

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        if (Input::get('send')) {
            $validation = new Validation();
            $validate = $validation->check(
                [
                    'id' => [
                        'required' => true,
                    ],
                    'username' => [
                        'required' => true,
                    ],
                    'message' => [
                        'required' => true,
                    ],
                ]
            );
            if (!$validate->passed()) {
                // Session::flash('errors', $validation->errors());
                Redirect::to('messages', ['u' => Input::get('username')]);
            }
            $message = new Message();
            $message->create(Input::get('id'), Input::get('message'));
            Redirect::to('messages', ['u' => Input::get('username')]);
        }
    }
}

Redirect::to('index');