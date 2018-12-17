<?php
namespace app;

/**
 * Handles Validation Logic
 * 
 * @category Validate
 */
class Validation
{
    private $_passed = false, $_errors = [], $_db = null;

    private function addError(String $key, $value)
    {
        // if (array_key_exists($key, $this->_errors)) {
        //     $this->_errors[$key][] = $value;
        // } else {
        //     $this->_errors[$key] = [$value];
        // }
        $this->_errors[] = $value;
    }

     /**
      * Validates given field against its rules
      *
      * @return $this
     */
    public function check(Array $params, Array $msg = [])
    {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'POST':
                $method = $_POST;
                break;
            
            case 'GET':
                $method = $_GET;
                break; 
        }
        foreach ($params as $key => $rules) {
            foreach ($rules as $rule => $rule_value) {
                $key = sanitize($key);
                $value = (!in_array($key, Config::get('trim-exception'))) ? sanitize($method[$key]) : $method[$key];
                Session::put($key, $value);
                if ($rule === 'required' && $rule_value === true && empty($value)) {
                    $this->addError($key, (array_key_exists($rule, $msg) && array_key_exists($rule, $msg[$key])) ? $msg[$key][$rule] : $this->defaultError($rule, $key));
                }
                switch ($rule) {
                    case 'min':
                        if (strlen($value) < $rule_value) {
                            $this->addError($key, (array_key_exists($rule, $msg) && array_key_exists($rule, $msg[$key])) ? $msg[$key][$rule] : $this->defaultError($rule, $key, $rule_value));
                        }
                        break;

                    case 'max':
                        if (strlen($value) > $rule_value) {
                            $this->addError($key, (array_key_exists($rule, $msg) && array_key_exists($rule, $msg[$key])) ? $msg[$key][$rule] : $this->defaultError($rule, $key, $rule_value));
                        }
                        break;
                    case 'email':
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $this->addError($key, (array_key_exists($rule, $msg) && array_key_exists($rule, $msg[$key])) ? $msg[$key][$rule] : $this->defaultError($rule, $key));
                        }
                         break;
                    case 'matches':
                        if ($method[$rule_value] !== $method[$key]) {
                            $this->addError($key, (array_key_exists($rule, $msg) && array_key_exists($rule, $msg[$key])) ? $msg[$key][$rule] : $this->defaultError($rule, $key, $rule_value));
                        }
                        break;
                    case 'unique':
                        if (!empty($value)) {
                            $rule_value = explode(':', $rule_value);
                            $table = $rule_value[0];
                            $column = $rule_value[1];
                            $db = DB::getInstance();
                            if (isset($rule_value[2])) {
                                $id = $rule_value[2];
                                $result = $db->raw("SELECT $column FROM $table WHERE id NOT IN (?) AND  $column = ?", [$id, $value])->get();
                            } else {
                                $result = $db->select($table, [$column], [$column => $value])->get();
                            }
                            if ($result) {
                                $this->addError($key, (array_key_exists($rule, $msg) && array_key_exists($rule, $msg[$key])) ? $msg[$key][$rule] : $this->defaultError($rule, $key));
                            }
                        }
                        break;
                }
            }
        }
        if (empty($this->_errors)) {
            $this->_passed = true;
        }
        return $this;
    }

    private function defaultError(String $rule, $key, $value = null)
    {
        $key = ucwords($key);
        switch ($rule) {
            case 'required':
                return "{$key} is required";
                break;
            
            case 'min':
                return "{$key} cannot be less than {$value} characters";
                break;

            case 'max':
                return "{$key} cannot be more than {$value} characters";
                break;
            
            case 'matches':
                return "{$key} must match {$value}";
                break;
            
            case 'email':
                return "Invalid email format";
                break;

            case 'unique':
                return "{$key} already exists";
                break;
        }
    }

    public function errors()
    {
        return $this->_errors;
    }

    public function passed()
    {
        return $this->_passed;
    }
}
