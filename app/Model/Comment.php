<?php
namespace app\Model;

use app\DB;
use app\Auth\Auth;


class Comment
{
    private $_db;

    public function __construct() {
        $this->_db = DB::getInstance();
    }

    public function create($post_id, $comment)
    {
        $this->_db->insert('comments', [
            'comment' => $comment,
            'post_id' => $post_id,
            'user_id' => Auth::user()->id,
        ]);
    }

    public function timeline($id)
    {
        $data = $this->_db->raw("SELECT comments.*, users.fname, users.lname, users.avatar, users.username FROM comments INNER JOIN users ON comments.user_id = users.id  WHERE comments.post_id = $id  AND comments.deleted_at IS NULL AND users.deleted_at IS NULL ORDER BY comments.created_at DESC")->get();
        foreach ($data as $value) {
            if ($value->recepient) {
                $user = new User($value->recepient);
                $value->recepient = $user->getUser();
            }
        }
        return $data;
    }
}
