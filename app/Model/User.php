<?php
namespace app\Model;

use app\DB;


class User
{
    private $_db;

    public function __construct() {
        $this->_db = DB::getInstance();
    }

    public function find($value)
    {
        $data = $this->_db->select('users', [], ['email' => $value])->first();
        return ($data) ? $data : false;
    }
}
