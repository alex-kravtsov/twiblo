<?php

class TwibloLog {

    private static $_instance = null;

    private $_types = null;

    public function writeMessage($msg){
        $this->_logMessage($msg, 'message');
        echo "MESSAGE: " . $msg . "\n";
    }

    public function writeError($msg){
        $this->_logMessage($msg, 'error');
        echo "ERROR: " . $msg . "\n";
    }

    public function writeWarning($msg){
        $this->_logMessage($msg, 'warning');
        echo "WARNING: " . $msg . "\n";
    }

    private function _logMessage($msg, $type){
        $message = new stdClass();
        $message->type_id = $this->_types[$type];
        $message->message = $msg;
        $message->record_date = $this->_getRecordDate();
        $this->_saveRecord($message);
    }

    private function _getRecordDate(){
        $date = new DateTime();
        return $date->format('Y-m-d H:i:s');
    }

    private function _saveRecord($record){
        $db = TwibloFactory::getDbo();
        $query = "INSERT INTO `#__log_messages` SET
            `type_id` = " . $record->type_id . ",
            `message` = '" . $db->escape($record->message) . "',
            `record_date` = '" . $record->record_date . "'
        ";
        $db->insert($query);
    }

    public static function getInstance(){
        if(!self::$_instance) self::$_instance = new TwibloLog();
        return self::$_instance;
    }

    private function __construct(){
        $db = TwibloFactory::getDbo();
        $query = "SELECT * FROM `#__log_message_types`";
        $rs = $db->select($query);
        if(empty($rs) ) throw new Exception("Cannot get log messages types");
        $this->_types = array();
        foreach($rs as $message_type) $this->_types[$message_type->type] = $message_type->id;
    }

}
