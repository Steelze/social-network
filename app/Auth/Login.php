<?php
namespace app\Auth;

use app\DB;
use app\Model\User;
use app\Hash;
use app\Session;
use app\Redirect;
use app\Config;
use app\Token;

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
                Session::put('loggedIn', true);
                Token::setToken();
                Redirect::to('index');
            }
        }
        Session::flash('errors', ['Credentials does not exist']);
        Redirect::to('register');
    }

    public function checkPassword($password, $hash)
    {
        return (Hash::check($password, $hash)) ? true : false;
    }

}
