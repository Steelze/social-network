<?php
namespace app\Auth;

use app\DB;
use app\Model\User;
use app\Hash;
use app\Session;
use app\Redirect;
use app\Config;

/**
 * undocumented class
 */
class Login
{
    private $_db;
    public function __construct() {
        $this->_db = DB::getInstance();
    }

    public function login(String $email, $password)
    {
        $user = new User();
        $data = $user->find($email);
        if($data) {
            if ($this->checkPassword($password, $data->password)) {
                Session::put(Config::get('user'), $data);
                Redirect::to('index');
            }
        }
        Session::flash('errors', ['Credentials does not exist']);
        Redirect::to('register');
    }

    private function checkPassword($password, $hash)
    {
        return (Hash::check($password, $hash)) ? true : false;
    }

}
