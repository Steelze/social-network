<?php
namespace app\Model;

use app\DB;
use app\Auth\Auth;
use app\Router;


class Notification
{
    private $_db;

    public function __construct() {
        $this->_db = DB::getInstance();
    }

    public function create($id, $recepient, $type)
    {
        switch ($type) {
            case 'comment':
                $message = Auth::user()->fname.' commented on your post';
                break;
            case 'comment_others':
                $message = Auth::user()->fname.' commented on a post you commented on';
                break;
            case 'like':
                $message = Auth::user()->fname.' liked on your post';
                break;
            case 'profile':
                $message = Auth::user()->fname.' wrote on your timeline';
                break;
        }
        $link = Router::route('post', ['id' => $id]);
        $this->_db->insert('notifications', [
            'sender' => Auth::user()->id,
            'recepient' => $recepient,
            'message' => $message,
            'link' => $link,
        ]);
    }
    
    public function getNotifications($var = false)
    {
        $id = Auth::user()->id;
        if ($var) {
            $data = $this->_db->raw("SELECT sender, message, opened, link, created_at FROM notifications WHERE recepient = $id AND deleted_at IS NULL ORDER BY created_at DESC");
        } else {
            $data = $this->_db->raw("SELECT sender, message, opened, link, created_at FROM notifications WHERE recepient = $id AND opened = 0 AND deleted_at IS NULL ORDER BY created_at DESC");
        }
        
        return ($data->exists()) ? $data->get() : [];
    }

    public function unreadCount()
    {
        return $this->_db->raw("SELECT count(id) AS num FROM notifications WHERE recepient = ? AND viewed = ? AND deleted_at IS NULL", [Auth::user()->id, 0])->first()->num;
    }

    public function allViewed()
    {
        $auth = Auth::user()->id;
        $this->_db->update('notifications', [
            'viewed' => 1,
        ],
        [
            'recepient' => $auth,
        ]);
    }
    
    public function allOpened()
    {
        
        $auth = Auth::user()->id;
        $this->_db->update('notifications', [
            'viewed' => 1,
            'opened' => 1,
        ],
        [
            'recepient' => $auth,
        ]);
    }
    
    public function opened($id)
    {
        $auth = Auth::user()->id;
        $data = $this->_db->raw("UPDATE notifications SET viewed = 1, opened = 1 WHERE recepient = ? AND link LIKE ?", ["$auth", "%=$id"]);
    }
}
