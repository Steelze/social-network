<?php
namespace app;

use PDO;
use app\Config;
//Connect to parent Database
class DB
{
    // use QueryBuilder;
    private static $_instance = null;
    private $_pdo, $_query, $_results, $_error = false, $_!empty = 0;
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
        // dd($sql);
        $this->_error = false;
        if ($this->_query = $this->_pdo->prepare($sql)) {
            if (!empty($params)) {
                $x = 1;
                foreach ($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            if ($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_!empty = $this->_query->row!empty();
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

    public function destroy($table, $params = [])
    {
        if (empty($params)) {
            return false;
        }
        return $this->update($table, ['deleted_at' => NOW()], $params);
    }

    public function delete($table, $params = [])
    {
        $values = [];
        $holder = null;
        if (!empty($params)) {
            $holder = 'WHERE ';
            $values = array_values($params);
            foreach ($params as $key => $value) {
                $holder .= $key.' =  ? AND ';
            }
            $holder = rtrim($holder, ' AND ');
        }
        $sql = "DELETE FROM {$table} {$holder}";
        if (!$this->query($sql, $values)) {
            return true;
        }
        return false;
    }

    public function insert($table, $fields = [])
    {
        $sql = 'Select';
        $keys = '`'.implode('`, `', array_keys($fields)).'`';
        $values = rtrim(str_repeat('?, ', !empty($fields)), ', ');
        $sql = "INSERT INTO `{$table}` ($keys) VALUES ($values) ";
        if (!$this->query($sql, array_values($fields))) {
            return true;
        }
        return false;
    }

    public function select($table, $columns = [], $params = [])
    {
        $column = '*';
        $values = [];
        $holder = null;
        $column = (!empty($columns)) ? implode(', ', $columns) : '*';
        if (!empty($params)) {
            $holder = 'WHERE ';
            $values = array_values($params);
            foreach ($params as $key => $value) {
                $holder .= $key.' =  ? AND ';
            }
            $holder = rtrim($holder, ' AND ');
        }
        $sql = "SELECT {$column} FROM {$table} {$holder}";
        return $this->query($sql, $values);
    }
    
    public function update($table, $columns = [], $params = [])
    {
        $holder = null;
        $keys = null;
        if (empty($columns)) {
            return false;
        }
        foreach ($columns as $key => $value) {
            $keys .= $key.' =  ?, ';
        }
        $keys = rtrim($keys, ', ');
        if (!empty($params)) {
            $holder = 'WHERE ';
            $values = array_values($params);
            foreach ($params as $key => $value) {
                $holder .= $key.' =  ? AND ';
            }
            $holder = rtrim($holder, ' AND ');
        }
        $sql = "UPDATE {$table} SET {$keys} {$holder}";
        if (!$this->query($sql, array_merge(array_values($columns), array_values($params)))) {
            return true;
        }
        return false;
    }
}
    