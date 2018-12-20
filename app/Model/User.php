<?php
namespace app\Model;

use app\DB;
use app\Auth\Auth;


class User
{
    private $_db;
    private $_user = null;

    public function __construct($user = null) {
        $this->_db = DB::getInstance();
        if ($user) {
            if (is_numeric($user)) {
                $this->_user = $this->find($user, 'id');
            } else {
                $this->_user = $this->find($user, 'username');
            }
        } else {
            $this->_user = $this->find(Auth::user()->id, 'id');
        }

    }

    public function find($value, $column = null)
    {
        $column = ($column) ? $column : 'email';
        $data = $this->_db->select('users', [], [$column => $value])->first();
        return ($data) ? $data : false;
    }

    public function getFullName()
    {
        if ($this->_user) {
            return $this->_user->fname.' '.$this->_user->lname;
        }
        return;
    }

    public function postsCount()
    {
        return $this->_db->raw("SELECT count(id) AS num FROM posts WHERE user_id = ?", [Auth::user()->id])->first()->num;
    }

    public function getUser()
    {
        return $this->_user;
    }
}
