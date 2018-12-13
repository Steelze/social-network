<?php
namespace app\Auth;

use app\DB;
use Exception;

/**
 * undocumented class
 */
class Register
{
    private $_db;
    public function __construct() {
        $this->_db = DB::getInstance();
    }

    public function register(Array $data)
    {
        $this->_db->insert('users', $data);
    }
}
