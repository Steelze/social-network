<?php

use app\Hash;
use app\Input;
use app\Validation;
use app\Auth\Register;

if (Input::exists()) {
    if (Input::get('register')) {
        $validation = new Validation();
        $validate = $validation->check(
            [
                'fname' => [
                    'required' => true,
                ],
                'lname' => [
                    'required' => true,
                ],
                'username' => [
                    'required' => true,
                    'unique' => 'users:username',
                ],
                'email' => [
                    'required' => true,
                    'email' => true,
                    'unique' => 'users:email',
                ],
                'password' => [
                    'required' => true,
                    'min' => 6,
                ],
                'password_confirmation' => [
                    'required' => true,
                    'matches' => 'password',
                ],
            ],
            [
                'fname' => [
                    'required' => 'First Name is a Required Field',
                ],
            ]
        );
        if (!$validate->passed()) {
            // var_dump($validation->errors());
            // Redirect back to register
        } else {
            $auth = new Register();
            $auth->register(
                [
                    'fname' => Input::get('fname'),
                    'lname' => Input::get('lname'),
                    'username' => Input::get('username'),
                    'email' => Input::get('email'),
                    'password' => Hash::make(Input::raw('password')),
                ]
            );
            // Redirect back to register
        }
    }
}