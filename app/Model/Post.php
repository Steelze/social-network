<?php
namespace app\Model;

use app\DB;
use app\Auth\Auth;

class Post
{
    private $_db;
    // private $_post = null;

    public function __construct() {
        $this->_db = DB::getInstance();
    }

    public function check($value, $column = null)
    {
        $data = $this->_db->select('posts', ['id'], ['id' => $value]);
        return ($data->exists()) ? true : false;
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

        $stopWords = "a about above across after again against all almost alone along already
            also although always among am an and another any anybody anyone anything anywhere are 
            area areas around as ask asked asking asks at away b back backed backing backs be became
            because become becomes been before began behind being beings best better between big 
            both but by c came can cannot case cases certain certainly clear clearly come could
            d did differ different differently do does done down down downed downing downs during
            e each early either end ended ending ends enough even evenly ever every everybody
            everyone everything everywhere f face faces fact facts far felt few find finds first
            for four from full fully further furthered furthering furthers g gave general generally
            get gets give given gives go going good goods got great greater greatest group grouped
            grouping groups h had has have having he her here herself high high high higher
            highest him himself his how however i im if important in interest interested interesting
            interests into is it its itself j just k keep keeps kind knew know known knows
            large largely last later latest least less let lets like likely long longer
            longest m made make making man many may me member members men might more most
            mostly mr mrs much must my myself n necessary need needed needing needs never
            new new newer newest next no nobody non noone not nothing now nowhere number
            numbers o of off often old older oldest on once one only open opened opening
            opens or order ordered ordering orders other others our out over p part parted
            parting parts per perhaps place places point pointed pointing points possible
            present presented presenting presents problem problems put puts q quite r
            rather really right right room rooms s said same saw say says second seconds
            see seem seemed seeming seems sees several shall she should show showed
            showing shows side sides since small smaller smallest so some somebody
            someone something somewhere state states still still such sure t take
            taken than that the their them then there therefore these they thing
            things think thinks this those though thought thoughts three through
            thus to today together too took toward turn turned turning turns two
            u under until up upon us use used uses v very w want wanted wanting
            wants was way ways we well wells went were what when where whether
            which while who whole whose why will with within without work
            worked working works would x y year years yet you young younger
            youngest your yours z lol haha omg hey ill iframe wonder else like 
            hate sleepy reason for some little yes bye choose";

        //Convert stop words into array - split at white space
        $stopWordsArray = array_map('strtolower', preg_split("/[\s,]+/", $stopWords));

        //Remove all punctionation
        $no_punctuation = preg_replace("/[^a-zA-Z 0-9]+/", "", $body);

        //Predict whether user is posting a url. If so, do not check for trending words
        if(strpos($no_punctuation, "height") === false && strpos($no_punctuation, "width") === false && strpos($no_punctuation, "http") === false && strpos($no_punctuation, "youtube") === false) {
            //Convert users post (with punctuation removed) into array - split at white space
            $keywords = preg_split("/[\s,]+/", $no_punctuation);
            $trend = new Trend();
            foreach($keywords as $value){
                if(!in_array(strtolower($value), $stopWordsArray))  {
                    $trend->save($value);
                }
            }
        }

        //if recepient, send notification
        if ($recepient) {
            $notif = new Notification();
            $notif->create($returned_id, $recepient, 'profile');
        }
    }
    
    public function delete($id)
    {
        $this->_db->delete('posts', [
            'id' => $id,
        ]);
    }

    public function getSingle($id)
    {
        $id = (int)$id;
        $data = $this->_db->raw("SELECT posts.*, users.fname, users.lname, users.avatar, users.username FROM posts INNER JOIN users ON posts.user_id = users.id WHERE posts.deleted_at IS NULL AND posts.id = $id AND posts.deleted_at IS NULL AND users.deleted_at IS NULL")->first();

        $data->comment = $this->_db->raw("SELECT count(id) AS num FROM comments WHERE post_id = ?", [$data->id])->first()->num;
        $data->like = $this->_db->raw("SELECT count(id) AS num FROM likes WHERE post_id = ?", [$data->id])->first()->num;
        $data->hasLiked = ($this->_db->select('likes', [], ['post_id' => $data->id, 'user_id' => Auth::user()->id])->count()) ? true : false;
        $comments = new Comment();
        $data->comments = $comments->timeline($data->id);
        if ($data->recepient) {
            $user = new User($data->recepient);
            $data->recepient = $user->getUser();
        }

        return $data;
    }

    public function newsfeed($start, $limit = 7)
    {
        $auth = new User();
        $id = (int)$auth->getUser()->id;
        $friends = $auth->friends();
        //If user has friends....
        if (count($friends)) {
            $placeholder = "OR users.id IN (".rtrim(str_repeat('?, ', count($friends)), ', ').")";
        } else {
            $placeholder = '';
        }
        //Add auth user to begining of friend array for sql binding
        array_unshift($friends, $id);
        //...
        $data = $this->_db->raw("SELECT posts.*, users.fname, users.lname, users.avatar, users.username FROM posts INNER JOIN users ON posts.user_id = users.id WHERE posts.deleted_at IS NULL AND posts.user_id = ? $placeholder AND posts.deleted_at IS NULL AND users.deleted_at IS NULL ORDER BY posts.created_at DESC LIMIT $start, $limit", $friends)->get();

        foreach ($data as $value) {
            $value->comment = $this->_db->raw("SELECT count(id) AS num FROM comments WHERE post_id = ?", [$value->id])->first()->num;
            $value->like = $this->_db->raw("SELECT count(id) AS num FROM likes WHERE post_id = ?", [$value->id])->first()->num;
            $value->hasLiked = ($this->_db->select('likes', [], ['post_id' => $value->id, 'user_id' => Auth::user()->id])->count()) ? true : false;
            $comments = new Comment();
            $value->comments = $comments->timeline($value->id);
            if ($value->recepient) {
                $user = new User($value->recepient);
                $value->recepient = $user->getUser();
            }
        }
        return $data;
    }
    
    public function timeline($user, $start, $limit = 7)
    {
        $auth = new User($user);
        $id = (int)$auth->getUser()->id;
        $data = $this->_db->raw("SELECT posts.*, users.fname, users.lname, users.avatar, users.username FROM posts INNER JOIN users ON posts.user_id = users.id WHERE posts.deleted_at IS NULL AND posts.user_id = ? OR posts.recepient = ? AND posts.deleted_at IS NULL AND users.deleted_at IS NULL ORDER BY posts.created_at DESC LIMIT $start, $limit", [$id, $id])->get();

        foreach ($data as $value) {
            $value->comment = $this->_db->raw("SELECT count(id) AS num FROM comments WHERE post_id = ?", [$value->id])->first()->num;
            $value->like = $this->_db->raw("SELECT count(id) AS num FROM likes WHERE post_id = ?", [$value->id])->first()->num;
            $value->hasLiked = ($this->_db->select('likes', [], ['post_id' => $value->id, 'user_id' => Auth::user()->id])->count()) ? true : false;
            $comments = new Comment();
            $value->comments = $comments->timeline($value->id);
            
            if ($value->recepient) {
                if ($value->recepient != $id) {
                    $user = new User($value->recepient);
                    $value->recepient = $user->getUser();
                } else {
                    $value->recepient = NULL;
                }
                
            } 
        }
        return $data;
    }
}
