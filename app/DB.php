<?php
namespace app;

use PDO;
use app\Config;
//Connect to parent Database
class DB
{
    // use QueryBuilder;
    private static $_instance = null;
    private $_pdo, $_query, $_results, $_error = false, $_count = 0;
    private function __construct()
    {
        try {
            $this->_pdo = new PDO('mysql:host='.Config::get('mysql.host').';dbname='.Config::get('mysql.db'), Config::get('mysql.username'), Config::get('mysql.password'));
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }

    private function query($sql, $params = [])
    {
        $this->_error = false;
        if ($this->_query = $this->_pdo->prepare($sql)) {
            if (count($params)) {
                $x = 1;
                foreach ($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            if ($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }
        return $this;
    }

    public function first()
    {
        return $this->_results[0];
    }

    public function get()
    {
        return $this->_results;
    }

    public function select($table, $columns = [], $params = [])
    {
        $column = '*';
        $values = [];
        $holder = null;
        $column = (count($columns)) ? implode(', ', $columns) : '*';
        if (count($params)) {
            $holder = 'WHERE ';
            $values = array_values($params);
            foreach ($params as $key => $value) {
                $holder .= $key.' =  ?, AND ';
            }
            $holder = rtrim($holder, ', AND ');
        }
        $sql = "SELECT {$column} FROM {$table} {$holder}";
        return $this->query($sql, $values);
    }
}
    