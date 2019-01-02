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

    public function find($value, $column = null)
    {
        $column = ($column) ? $column : 'id';
        $data = $this->_db->select('comments', [], [$column => $value])->first();
        return ($data) ? $data : false;
    }

    public function findAll($value, $column = null)
    {
        $column = ($column) ? $column : 'id';
        $data = $this->_db->select('comments', [], [$column => $value]);
        return ($data->exists()) ? $data->get() : false;
    }

    public function create($post_id, $comment)
    {
        $this->_db->insert('comments', [
            'comment' => $comment,
            'post_id' => $post_id,
            'user_id' => Auth::user()->id,
        ]);
        $post = new Post();
        $data = $post->find($post_id);
        $notif = new Notification();
        if ((int)$data->user_id !== (int)Auth::user()->id) {
            $notif->create($post_id, $data->user_id, 'comment');
        }
        $id = Auth::user()->id;
        $comments = $this->_db->raw("SELECT DISTINCT user_id FROM comments WHERE post_id = $post_id AND user_id <> $id AND user_id <>  $data->user_id AND deleted_at IS NULL")->get();
        foreach ($comments as $key) {
            $notif->create($post_id, $key->user_id, 'comment_others');
        }
    }

    public function timeline($id)
    {
        $data = $this->_db->raw("SELECT comments.*, users.fname, users.lname, users.avatar, users.username FROM comments INNER JOIN users ON comments.user_id = users.id  WHERE comments.post_id = $id  AND comments.deleted_at IS NULL AND users.deleted_at IS NULL ORDER BY comments.created_at ASC")->get();
        foreach ($data as $value) {
            if ($value->recepient) {
                $user = new User($value->recepient);
                $value->recepient = $user->getUser();
            }
        }
        return $data;
    }
}
