<?php
namespace app\Model;

use app\DB;
use app\Auth\Auth;


class Like
{
    private $_db;

    public function __construct() {
        $this->_db = DB::getInstance();
    }

    public function like($post_id)
    {
        $this->_db->insert('likes', [
            'post_id' => $post_id,
            'user_id' => Auth::user()->id,
        ]);
    }
    
    public function unlike($post_id)
    {
        $this->_db->destroy('likes', [
            'post_id' => $post_id,
            'user_id' => Auth::user()->id,
        ]);
    }
}
