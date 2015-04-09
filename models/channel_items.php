<?php

class TwibloModelChannel_Items extends TwibloModel {

    public function updateChannelItems($channel_items, $channel_id){
        $this->_captureCache();
        $this->_cleanCache();
        $this->_cacheItems($channel_items);
        $this->_removeOutdatedItemsFromStore($channel_id);
        $this->_removeDuplicatesFromCache($channel_id);
        $this->_pushItemsFromCacheToStore();
        $this->_cleanCache();
        $this->_releaseCache();
    }

    private function _captureCache(){
        $db = TwibloFactory::getDbo();
        $query = "LOCK TABLES `#__channel_items_cache` WRITE, `#__channel_items` WRITE";
        $db->exec($query);
    }

    private function _cacheItems($channel_items){
        $db = TwibloFactory::getDbo();
        foreach($channel_items as $channel_item){
            $query = "INSERT INTO `#__channel_items_cache` SET
                `id` = NULL,
                `channel_id` = " . ($channel_item->channel_id ? $channel_item->channel_id : 'NULL') . ",
                `guid` = " . ($channel_item->guid ? "'" . $db->escape($channel_item->guid) . "'" : 'NULL') . ",
                `title` = " . ($channel_item->title ? "'" . $db->escape($channel_item->title) . "'" : 'NULL') . ",
                `description` = " . ($channel_item->description ? "'" . $db->escape($channel_item->description) . "'" : 'NULL') . ",
                `link` = " . ($channel_item->link ? "'" . $db->escape($channel_item->link) . "'" : 'NULL') . ",
                `pubDate` = " . ($channel_item->pubDate ? "'" . $db->escape($channel_item->pubDate) . "'" : 'NULL') . ",
                `enclosure_url` = " . ($channel_item->enclosure_url ? "'" . $db->escape($channel_item->enclosure_url) . "'" : 'NULL') . ",
                `enclosure_type` = " . ($channel_item->enclosure_type ? "'" . $db->escape($channel_item->enclosure_type) . "'" : 'NULL') . ",
                `enclosure_length` = " . ($channel_item->enclosure_length ? $channel_item->enclosure_length : 'NULL') . ",
                `tweet_id` = NULL
            ";
            $db->insert($query);
        }
    }

    private function _removeOutdatedItemsFromStore($channel_id){
        $db = TwibloFactory::getDbo();
        $query = "DELETE FROM `#__channel_items` WHERE
            `channel_id` = " . $channel_id . " AND
            `guid` NOT IN (SELECT `guid` FROM `#__channel_items_cache`)
        ";
        $db->delete($query);
    }

    private function _removeDuplicatesFromCache($channel_id){
        $db = TwibloFactory::getDbo();
        $query = "DELETE FROM `#__channel_items_cache` WHERE
            `guid` IN (SELECT `guid` FROM `#__channel_items` WHERE `channel_id` = " . $channel_id . ")
        ";
        $db->delete($query);
    }

    private function _pushItemsFromCacheToStore(){
        $db = TwibloFactory::getDbo();
        $query = "INSERT INTO `#__channel_items` SELECT * FROM `#__channel_items_cache`";
        $db->exec($query);
    }

    private function _cleanCache(){
        $db = TwibloFactory::getDbo();
        $query = "DELETE FROM `#__channel_items_cache`";
        $db->exec($query);
    }

    private function _releaseCache(){
        $db = TwibloFactory::getDbo();
        $query = "UNLOCK TABLES";
        $db->exec($query);
    }

}
