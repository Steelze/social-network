<?php
require_once '../require.php';

use app\Config;
use app\Input;
use app\Redirect;
use app\Session;
use app\Validation;
use app\Auth\Auth;
use app\Model\User;
use app\DB;

if (!Auth::check()) {
    Redirect::to('register');
}

if (Input::exists()) {
    if (Input::get('update-profile')) {
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
                    'unique' => 'users:username:'.Auth::user()->id,
                ],
                'email' => [
                    'required' => true,
                    'email' => true,
                    'unique' => 'users:email:'.Auth::user()->id,
                ],
            ],
            [
                'fname' => [
                    'required' => 'First Name is a Required Field',
                ],
                'lname' => [
                    'required' => 'Last Name is a Required Field',
                ],
            ]
        );
        if (!$validate->passed()) {
            Session::flash('errors', $validation->errors());
            Redirect::to('settings');
        }

        $_db = DB::getInstance();
        $_db->update('users',
            [
                'fname' => ucwords(Input::get('fname')),
                'lname' => ucwords(Input::get('lname')),
                'username' => strtolower(Input::get('username')),
                'email' => Input::get('email'),
            ],
            [
                'id' => Auth::user()->id,
            ]
        );
        $user = new User();
        Session::put(Config::get('user'), $user->getUser());
        Session::flash('msg', 'Profile Updated Successfully');
        Redirect::to('settings');
    }
}

Redirect::to('index');