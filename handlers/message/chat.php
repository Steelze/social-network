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
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json; charset=UTF-8');
            die('Oops an error occured');
        }
        $message = new Message();
        $data = $message->create(Input::get('id'), Input::get('message'));
        header('Content-Type: application/json; charset=UTF-8');
        header('HTTP/1.1 200 Content Ok');
        echo json_encode($data);
    }
}