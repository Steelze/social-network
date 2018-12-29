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
        $data = $this->_db->select('users', [], [$column => $value]);
        return ($data->exists()) ? $data->first() : false;
    }

    public function getFullName()
    {
        if ($this->_user) {
            return $this->_user->fname.' '.$this->_user->lname;
        }
        return;
    }

    public function myFriend()
    {
        $data = $this->_db->select('friends', ['friend_id'], [
            'user_id' => $this->_user->id,
            'accepted' => 1,
        ])->get();

        return array_map(function($data)
        {
            return (int)$data->friend_id;
        }, $data);
    }
    
    public function friendOfMine()
    {
        $data = $this->_db->select('friends', ['user_id'], [
            'friend_id' => $this->_user->id,
            'accepted' => 1,
        ])->get();

        return array_map(function($data)
        {
            return (int)$data->user_id;
        }, $data);
    }

    public function friends()
    {
        return array_merge($this->myFriend(), $this->friendOfMine());
    }
    
    public function isFriend($id)
    {
        return in_array($id, $this->friends());
    }

    public function friendsCount()
    {
        return count($this->friends());
    }
    
    public function postsCount()
    {
        return $this->_db->raw("SELECT count(id) AS num FROM posts WHERE user_id = ?", [Auth::user()->id])->first()->num;
    }
    
    public function likesCount()
    {
        return $this->_db->raw("SELECT count(id) AS num FROM likes WHERE user_id = ?", [Auth::user()->id])->first()->num;
    }

    public function getUser()
    {
        return $this->_user;
    }
}
