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
                $arr[$key] = $this->_db->raw("SELECT message FROM messages WHERE (sender = $key AND recepient = $id) OR (sender = $id AND recepient = $key) AND deleted_at IS NULL ORDER BY created_at DESC LIMIT 1")->first()->message;
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
        ],
        [
            'recepient' => $auth,
            'sender' => $id,
        ]);
        $data = $this->_db->raw("SELECT * FROM messages WHERE (sender = $auth AND recepient = $id) OR (sender = $id AND recepient = $auth) AND deleted_at IS NULL ORDER BY created_at ASC")->get();
        return $data;
    }
}
