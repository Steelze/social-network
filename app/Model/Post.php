<?php
namespace app\Model;

use app\DB;
use app\Auth\Auth;


class Post
{
    private $_db;
    private $_user = null;

    public function __construct() {
        $this->_db = DB::getInstance();
    }

    public function find($value, $column = null)
    {
        $column = ($column) ? $column : 'email';
        $data = $this->_db->select('posts', [], [$column => $value])->first();
        return ($data) ? $data : false;
    }

    public function create($body, $recepient = null)
    {
        $this->_db->insert('posts', [
            'body' => $body,
            'recepient' => $recepient,
            'user_id' => Auth::user()->id,
        ]);

        //if recepient, send notification
    }
}
