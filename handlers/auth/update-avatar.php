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
use app\Router;

if (!Auth::check()) {
    Redirect::to('register');
}

if (Input::exists()) {
    if (Input::get('update-avatar')) {
        $validation = new Validation();
        $validate = $validation->check(
            [
                'avatar' => [
                    'file' => true,
                    'size' => 2,
                    'ext' => 'jpeg:jpg:png',
                ],
            ],
            [
                'avatar' => [
                    'file' => 'File is Required',
                ],
            ]
        );
        if (!$validate->passed()) {
            Session::flash('errors', $validation->errors());
            Redirect::to('settings');
        }
        // $file_name = uniqid();
        $tmp = explode('.', $_FILES['avatar']['name']);
        $ext = '.'.strtolower(end($tmp));
        $file_name = Auth::user()->id;
        $route = ROOT.'/assets/images/avatar/';
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $route.$file_name.$ext)) {
            # code...
            $_db = DB::getInstance();
            $_db->update('users',
                [
                    'avatar' => 'assets/images/avatar/'.$file_name.$ext,
                ],
                [
                    'id' => Auth::user()->id,
                ]
            );
            $user = new User();
            Session::put(Config::get('user'), $user->getUser());    
            Session::flash('msg', 'Avatar Updated Successfully');
            Redirect::to('settings');
        }
    }
}

Redirect::to('index');