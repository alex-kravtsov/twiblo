<?php

class TwibloDbo {

    private static $_instance = null;

    private $mysqli = null;
    private $_prefix = '';

    private function __construct(){
    }

    public static function getInstance(){
        if(empty(self::$_instance) ) self::$_instance = new TwibloDbo();
        return self::$_instance;
    }

    public function escape($string){
        return $this->mysqli->real_escape_string($string);
    }

    private function fillArray($resource){
        $objects = array();
        while($objects[] = $resource->fetch_object() );
        array_pop($objects);
        $resource->close();
        return $objects;
    }

    public function select($query){
        $query = $this->_applyPrefix($query);
        if(!$resource = $this->mysqli->query($query) ){
            $message = "MySQL Error: #" . $this->mysqli->errno . " " . $this->mysqli->error;
            throw new Exception($message);
        }//end if
        return $this->fillArray($resource);
    }

    private function fillAssocArray($resource){
        $rows = array();
        while($rows[] = $resource->fetch_assoc() );
        array_pop($rows);
        $resource->close();
        return $rows;
    }

    public function selectAssoc($query){
        $query = $this->_applyPrefix($query);
        if(!$resource = $this->mysqli->query($query) ){
            $message = "MySQL Error: #" . $this->mysqli->errno . " " . $this->mysqli->error;
            throw new Exception($message);
        }//end if
        return $this->fillAssocArray($resource);
    }

    public function selectOne($query){
        $query = $this->_applyPrefix($query);
        $a = $this->select($query);
        if(count($a) > 0) return $a[0];
        return false;
    }

    public function selectSingle($query){
        $query = $this->_applyPrefix($query);
        $a = $this->select($query);
        if(count($a) == 0) return false;
        if(count($a) == 1) return $a[0];
        throw new Exception("multiple result");
    }

    public function insert($query){
        $query = $this->_applyPrefix($query);
        if(!$this->mysqli->query($query) ){
            $message = "MySQL Error: #" . $this->mysqli->errno . " " . $this->mysqli->error;
            throw new Exception($message);
        }//end if
        return $this->mysqli->insert_id;
    }

    public function init($options){

        if(!empty($this->mysqli) ) return;

        if(empty($options) or !is_array($options) ) throw new Exception('Invalid options.');

        $host = $options['db_host'];
        $user = $options['db_user'];
        $password = $options['db_password'];
        $dbname = $options['db_name'];
        $charset = $options['db_charset'];
        $this->_prefix = $options['db_prefix'];

        $this->mysqli = new mysqli($host, $user, $password, $dbname);

        if(mysqli_connect_error() ){
            $message = "MySQL Error: #" . mysqli_connect_errno() . " " . mysqli_connect_error();
            throw new Exception($message);
        }//end if

        if(!$this->mysqli->set_charset($charset) ){
            $message = "MySQL Error: #" . $this->mysqli->errno . " " . $this->mysqli->error;
            throw new Exception($message);
        }//end if

        $query = "SET NAMES " . $charset;

        $this->update($query);

    }

    public function delete($query){
        $query = $this->_applyPrefix($query);
        if(!$this->mysqli->query($query) ){
            $message = "MySQL Error: #" . $this->mysqli->errno . " " . $this->mysqli->error;
            throw new Exception($message);
        }//end if
        return $this->mysqli->affected_rows;
    }

    public function update($query){
        $query = $this->_applyPrefix($query);
        if(!$this->mysqli->query($query) ){
            $message = "MySQL Error: #" . $this->mysqli->errno . " " . $this->mysqli->error;
            throw new Exception($message);
        }//end if
        return $this->mysqli->affected_rows;
    }

    public function exec($query){
        $query = $this->_applyPrefix($query);
        if(!$this->mysqli->query($query) ){
            $message = "MySQL Error: #" . $this->mysqli->errno . " " . $this->mysqli->error;
            throw new Exception($message);
        }//end if
    }
    
    private function _applyPrefix($query){
        return str_replace('#__', $this->_prefix, $query);
    }

}
