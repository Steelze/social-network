<?php
namespace app\Model;

use app\DB;

class Trend
{
    private $_db;

    public function __construct() {
        $this->_db = DB::getInstance();
    }

    public function save($trend)
    {
        return $this->check($trend) ? $this->update($trend) : $this->create($trend);

    }
    
    private function check($trend)
    {
        $data = $this->_db->select('trends', ['id'], [
            'trend' => $trend,
        ]);
        
        return ($data->exists()) ? true : false;
    }
    
    private function create($trend)
    {
        $this->_db->insert('trends', [
            'trend' => $trend,
        ]);
    }
    
    private function update($trend)
    {
        $this->_db->raw("UPDATE trends SET hit = hit + 1 WHERE trend = '$trend'");
    }
    
    public function get()
    {
        return $this->_db->raw("SELECT * FROM trends ORDER BY hit DESC LIMIT 10")->get();
    }
}