<?php
require_once '../require.php';
use app\Hash;
use app\Input;
use app\Redirect;
use app\Session;
use app\Validation;
use app\Auth\Login;

if (Input::exists()) {
    if (Input::get('login')) {
        $validation = new Validation();
        $validate = $validation->check(
            [
                'email' => [
                    'required' => true,
                    'email' => true,
                ],
                'password' => [
                    'required' => true,
                    'min' => 6,
                ],
            ]
        );
        if (!$validate->passed()) {
            Session::flash('errors', $validation->errors());
            Redirect::to('register');
        }

        $auth = new Login();
        $auth->login(Input::get('email'), Input::raw('password'));
    }
}

Redirect::to('index');