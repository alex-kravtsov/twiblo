<?php

class TwibloModelLog extends TwibloModel {

    public function startLogCapture(){
        $db = TwibloFactory::getDbo();
        $query = "LOCK TABLES `#__log_message_types` READ, `#__log_messages` READ";
        $db->exec($query);
    }

    public function endLogCapture(){
        $db = TwibloFactory::getDbo();
        $query = "UNLOCK TABLES";
        $db->exec($query);
    }

    public function getMessagesCount(){
        return $this->_getRecordsCountByType('message');
    }

    public function getErrorsCount(){
        return $this->_getRecordsCountByType('error');
    }

    public function getWarningsCount(){
        return $this->_getRecordsCountByType('warning');
    }

    public function getRecordsTotal(){
        $db = TwibloFactory::getDbo();
        $query = "SELECT COUNT(*) AS `total` FROM `#__log_messages`";
        $rs = $db->selectSingle($query);
        return $rs->total;
    }

    private function _getRecordsCountByType($type){
        switch($type){
        case 'message':
        case 'warning':
        case 'error':
            break;
        default:
            throw new Exception("Invalid log message type");
        }
        $db = TwibloFactory::getDbo();
        $query = "SELECT DISTINCT COUNT(`#__log_messages`.`id`) AS `total`
            FROM `#__log_messages` JOIN `#__log_message_types`
            ON `#__log_message_types`.`id` = `#__log_messages`.`type_id`
            WHERE `#__log_message_types`.`type` = '" . $type . "'
        ";
        $rs = $db->selectSingle($query);
        return $rs->total;
    }

    public function cleanRecordsBeforeDate($strdate){
        $db = TwibloFactory::getDbo();
        $query = "DELETE FROM `#__log_messages` WHERE `record_date` < '" . $strdate . "'";
        $db->exec($query);
        $query = "SELECT ROW_COUNT() AS `c`";
        $rs = $db->selectSingle($query);
        return $rs->c;
    }

}
