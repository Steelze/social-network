<?php
require_once 'require.php';
use app\Hash;
use app\Input;
use app\Redirect;
use app\Session;
use app\Validation;
use app\Auth\Register;

if (Input::exists()) {
    if (Input::get('register')) {
        Session::put('register', true);
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
                'reg_email' => [
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
            Session::flash('errors', $validation->errors());
            Redirect::to('register');
        }

        $auth = new Register();
        $auth->register(
            [
                'fname' => ucwords(Input::get('fname')),
                'lname' => ucwords(Input::get('lname')),
                'username' => strtolower(Input::get('username')),
                'email' => Input::get('reg_email'),
                'password' => Hash::make(Input::raw('password')),
            ]
        );
        Session::flash('msg', 'Registration  successful. Login to continue');
        Redirect::to('register');
    }
}