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

    public function pendingRequests()
    {
        $data = $this->_db->select('friends', ['user_id'], [
            'friend_id' => $this->_user->id,
            'accepted' => 0,
        ])->get();

        return $data;
    }

    public function hasSentRequest($id)
    {
        $data = $this->_db->select('friends', [], [
            'friend_id' => $id,
            'user_id' => $this->_user->id,
            'accepted' => 0,
        ]);
        return ($data->exists()) ? true : false;
    }

    public function hasReceivedRequest($id)
    {
        $data = $this->_db->select('friends', [], [
            'friend_id' => $this->_user->id,
            'user_id' => $id,
            'accepted' => 0,
        ]);
        return ($data->exists()) ? true : false;
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
        return $this->_db->raw("SELECT count(id) AS num FROM posts WHERE user_id = ? AND deleted_at IS NULL", [$this->_user->id])->first()->num;
    }
    
    public function likesCount()
    {
        return $this->_db->raw("SELECT count(id) AS num FROM likes WHERE user_id = ?", [$this->_user->id])->first()->num;
    }

    public function getUser()
    {
        return $this->_user;
    }

    public function acceptFriend($id)
    {
        $id = (int) $id;
        if (!$this->isFriend($id)) {
            $this->_db->update('friends', [
                'accepted' => 1,
            ], [
                'friend_id' => $this->_user->id,
                'user_id' => $id,
            ]);
        }
    }

    public function addFriend($id)
    {
        $id = (int) $id;
        if (!$this->isFriend($id)) {
            $data = $this->_db->raw("SELECT * FROM friends WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)", [$this->_user->id, $id, $id, $this->_user->id]);
            if (!$data->exists()) {
                $this->_db->insert('friends', [
                    'user_id' => $this->_user->id,
                    'friend_id' => $id,
                ]);
            }
        }
    }

    public function ignoreFriend($id)
    {
        $id = (int) $id;
        if (!$this->isFriend($id)) {
            $this->_db->destroy('friends', [
                'friend_id' => $this->_user->id,
                'user_id' => $id,
            ]);
        }
    }
    
    public function removeFriend($id)
    {
        $id = (int) $id;
        if ($this->isFriend($id)) {
            $this->_db->raw("DELETE FROM friends WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)", [$this->_user->id, $id, $id, $this->_user->id]);
        }
    }

    public function mutual($id)
    {
        $this->_id = $id;
        $user = new User($id);
        $auth_friend = $user->friends();
        $my_friend = $this->friends();
        $data = array_intersect($auth_friend, $my_friend);
        return $data;
    }
    
    public function mutualCount($id)
    {
        return count($this->mutual($id));
    }
}
