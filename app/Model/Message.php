<?php
namespace app\Model;

use app\DB;
use app\Auth\Auth;


class Message
{
    private $_db;

    public function __construct() {
        $this->_db = DB::getInstance();
    }

    public function create($id, $message)
    {
        $this->_db->insert('messages', [
            'sender' => Auth::user()->id,
            'recepient' => $id,
            'message' => $message,
        ]);
    }

    public function getConvos()
    {
        $arr = [];
        $id = Auth::user()->id;
        $data = $this->_db->raw("SELECT DISTINCT sender, recepient FROM messages WHERE sender = $id OR recepient = $id AND deleted_at IS NULL ORDER BY created_at DESC");
        // dd($data->get());
        foreach ($data->get() as $key) {
            $arr[] = (int)($key->sender !== $id ? $key->sender : $key->recepient);
        }
        $arr = array_unique($arr);
        return ($this->lastMsg($arr));
        return ($data->exists()) ? ($data->first()->sender !== $id ? $data->first()->sender : $data->first()->recepient) : false;
    }
    
    public function navConvos()
    {
        $arr = [];
        $results = [];
        $id = Auth::user()->id;
        $data = $this->_db->raw("SELECT DISTINCT sender, recepient FROM messages WHERE sender = $id OR recepient = $id AND deleted_at IS NULL ORDER BY created_at DESC LIMIT 7");

        foreach ($data->get() as $key) {
            $arr[] = (int)($key->sender !== $id ? $key->sender : $key->recepient);
        }
        $arr = array_unique($arr);

        foreach ($this->lastMsg($arr) as $key => $value) {
            $temp = $this->_db->raw("SELECT recepient, opened FROM messages WHERE  (sender = $key AND recepient = $id) AND deleted_at IS NULL OR (sender = $id AND recepient = $key) AND deleted_at IS NULL ORDER BY created_at DESC LIMIT 1")->first();
            $results[$key]['msg'] = $value;
            $results[$key]['opened'] = $temp->opened;
            $results[$key]['recepient'] = $temp->recepient;
        }
        return $results;
    }

    public function getRecent($id = null)
    {
        if (!$id) {
            $id = Auth::user()->id;
        }
        $data = $this->_db->raw("SELECT sender, recepient FROM messages WHERE sender = $id OR recepient = $id AND deleted_at IS NULL ORDER BY updated_at DESC LIMIT 1");
        
        return ($data->exists()) ? ($data->first()->sender !== $id ? $data->first()->sender : $data->first()->recepient) : false;
    }

    public function lastMsg(Array $var = [])
    {
        if (count($var)) {
            $id = Auth::user()->id;
            $arr = [];
            foreach ($var as $key) {
                $arr[$key] = $this->_db->raw("SELECT message FROM messages WHERE (sender = $key AND recepient = $id) AND deleted_at IS NULL OR (sender = $id AND recepient = $key) AND deleted_at IS NULL ORDER BY created_at DESC LIMIT 1")->first()->message;
            }
            return $arr;
        }
        return [];
    }

    public function retrieve($id)
    {
        $auth = Auth::user()->id;
        $this->_db->update('messages', [
            'opened' => 1,
            'viewed' => 1,
        ],
        [
            'recepient' => $auth,
            'sender' => $id,
        ]);
        $data = $this->_db->raw("SELECT * FROM messages WHERE (sender = ? AND recepient = ?) AND deleted_at IS NULL OR (sender = ? AND recepient = ?) AND deleted_at IS NULL ORDER BY created_at ASC", [$auth, $id, $id, $auth])->get();
        return $data;
    }

    public function unreadCount()
    {
        $arr = [];
        $data = $this->_db->raw("SELECT sender FROM messages WHERE recepient = ? AND viewed = ? AND deleted_at IS NULL", [Auth::user()->id, 0])->get();

        foreach ($data as $key) {
            $arr[] = $key->sender;
        }

        return count(array_unique($arr));
    }

    public function allViewed()
    {
        $auth = Auth::user()->id;
        $this->_db->update('messages', [
            'viewed' => 1,
        ],
        [
            'recepient' => $auth,
        ]);
    }
}
