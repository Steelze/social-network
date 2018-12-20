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
        $column = ($column) ? $column : 'id';
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

    public function timeline($start, $limit = 7)
    {
        $data = $this->_db->raw("SELECT posts.*, users.fname, users.lname, users.avatar, users.username FROM posts INNER JOIN users ON posts.user_id = users.id WHERE posts.deleted_at IS NULL AND users.deleted_at IS NULL ORDER BY posts.created_at DESC LIMIT $start, $limit")->get();
        foreach ($data as $value) {
            if ($value->recepient) {
                $user = new User($value->recepient);
                $value->recepient = $user->getUser();
            }
        }
        return $data;
    }
}
