<?php

class TwibloModelChannels_Statistics extends TwibloModel {

    public function startCapture(){
        $db = TwibloFactory::getDbo();
        $query = "LOCK TABLES `#__channels` READ, `#__channel_items` READ";
        $db->exec($query);
    }

    public function getActiveChannelsCount(){
        $db = TwibloFactory::getDbo();
        $query = "SELECT COUNT(*) AS `c_active_channels` FROM `#__channels` WHERE `enabled` = 1";
        $rs = $db->selectSingle($query);
        return $rs->c_active_channels;
    }

    public function getTotalItemsCount(){
        $db = TwibloFactory::getDbo();
        $query = "SELECT COUNT(*) AS `c_total_items` FROM `#__channel_items`";
        $rs = $db->selectSingle($query);
        return $rs->c_total_items;
    }

    public function getPostedItemsCount(){
        $db = TwibloFactory::getDbo();
        $query = "SELECT COUNT(*) AS `c_posted_items` FROM `#__channel_items` WHERE `tweet_id` IS NOT NULL";
        $rs = $db->selectSingle($query);
        return $rs->c_posted_items;
    }

    public function getNewestItemDate(){
        $db = TwibloFactory::getDbo();
        $query = "SELECT MAX(`pubDate`) AS `d_newest_item` FROM `#__channel_items`";
        $rs = $db->selectSingle($query);
        return $rs->d_newest_item;
    }

    public function getOldestItemDate(){
        $db = TwibloFactory::getDbo();
        $query = "SELECT MIN(`pubDate`) AS `d_oldest_item` FROM `#__channel_items`";
        $rs = $db->selectSingle($query);
        return $rs->d_oldest_item;
    }

    public function endCapture(){
        $db = TwibloFactory::getDbo();
        $query = "UNLOCK TABLES";
        $db->exec($query);
    }

}
