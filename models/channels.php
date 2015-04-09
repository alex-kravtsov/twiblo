<?php

class TwibloModelChannels extends TwibloModel {

    public function getChannels(){
        $dbo = TwibloFactory::getDbo();
        $query = "SELECT * FROM `#__channels`";
        return $dbo->select($query);
    }

    public function getActiveChannels(){
        $dbo = TwibloFactory::getDbo();
        $query = "SELECT * FROM `#__channels` WHERE `enabled` = 1";
        return $dbo->select($query);
    }

}
