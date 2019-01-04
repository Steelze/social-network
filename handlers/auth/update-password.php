<?php
require_once '../require.php';
use app\Hash;
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
    if (Input::get('update-password')) {
        $validation = new Validation();
        $validate = $validation->check(
            [
                'old_password' => [
                    'required' => true,
                    'min' => 6,
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
            ]
        );
        if (!$validate->passed()) {
            Session::flash('errors', $validation->errors());
            Redirect::to('settings');
        }
        
        $user = new User();
        $data = $user->find(Auth::user()->id, 'id');
        if (!Hash::check(Input::get('old_password'), $data->password)) {
            Session::flash('msg', 'Invalid password');
            Redirect::to('settings');
        }
        $_db = DB::getInstance();
        $_db->update('users',
            [
                'password' => Hash::make(Input::raw('password')),
            ],
            [
                'id' => Auth::user()->id,
            ]
        );
        Session::flash('msg', 'Password Updated Successfully');
        Redirect::to('settings');
    }
}

Redirect::to('index');