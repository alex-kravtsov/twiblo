<?php

class TwibloModelChannel extends TwibloModel {

    public function getChannelXMLSource($rss_feed_url){
        $command = 'curl -s ' . $rss_feed_url;
        return `$command`;
    }

    public function getChannelFromXML($xml, $rss_feed_url){
        $doc = new DOMDocument();
        if(!$doc->loadXML($xml) ) return false;
        $xpath = new DOMXPath($doc);
        $channel = new stdClass();
        $channel->title = $xpath->query('/rss/channel/title')->item(0)->nodeValue;
        if(empty($channel->title) ) return false;
        $channel->link = $xpath->query('/rss/channel/link')->item(0)->nodeValue;
        $channel->description = $xpath->query('/rss/channel/description')->item(0)->nodeValue;
        $channel->rss_link = $rss_feed_url;
        $channel->enabled = 0;
        return $channel;
    }

    public function saveChannel($channel){
        $dbo = TwibloFactory::getDbo();
        $query = "INSERT INTO ";
        if($channel->id) $query = "UPDATE ";
        $query .= "
            `#__channels` SET
                `title` = " . (!empty($channel->title) ? "'" . $dbo->escape($channel->title) . "'" : 'NULL') . ",
                `link` = " . (!empty($channel->link) ? "'" . $dbo->escape($channel->link) . "'" : 'NULL') . ",
                `description` = " . (!empty($channel->description) ? "'" . $dbo->escape($channel->description) . "'" : 'NULL') . ",
                `rss_link` = " . (!empty($channel->rss_link) ? "'" . $dbo->escape($channel->rss_link) . "'" : 'NULL') . ",
                `enabled` = " . ($channel->enabled ? 1 : 0) . "
        ";
        if($channel->id){
            $query .= "WHERE `id` = " . $channel->id;
            $dbo->update($query);
            return $channel->id;
        }
        return $dbo->insert($query);
    }

    public function doesRssFeedExist($rss_feed_url){
        $dbo = TwibloFactory::getDbo();
        $query = "SELECT `id` FROM `#__channels` WHERE `rss_link` = '" . $dbo->escape($rss_feed_url) . "'";
        if(!$channel = $dbo->selectSingle($query) ) return null;
        return $channel->id;
    }

    public function doesChannelExist($channel_id){
        $dbo = TwibloFactory::getDbo();
        $query = "SELECT COUNT(*) AS `total` FROM `#__channels` WHERE `id` = " . $channel_id;
        $rs = $dbo->selectSingle($query);
        if($rs->total == 1) return true;
        return false;
    }

    public function activateChannel($channel_id){
        $dbo = TwibloFactory::getDbo();
        $query = "UPDATE `#__channels` SET `enabled` = 1 WHERE `id` = " . $channel_id;
        $dbo->update($query);
    }

    public function isChannelActive($channel_id){
        $dbo = TwibloFactory::getDbo();
        $query = "SELECT `enabled` FROM `#__channels` WHERE `id` = " . $channel_id;
        $rs = $dbo->selectSingle($query);
        if($rs->enabled == 0) return false;
        return true;
    }

    public function updateChannel($channel_id){
        $log = TwibloFactory::getLog();
        $channel = $this->getChannel($channel_id);
        $log->writeMessage("Attempt to update channel " . $channel->rss_link);
        $xml = $this->getChannelXMLSource($channel->rss_link);
        if(empty($xml) ){
            $log->writeError("Channel update failed. Cannot read XML from RSS link " . $channel->rss_link);
            return false;
        }
        $channel_items = $this->getChannelItemsFromXML($xml, $channel_id);
        if(empty($channel_items) ){
            $log->writeError("Channel update failed. Cannot find channel items in XML source of " . $channel->rss_link);
            return false;
        }
        $log->writeMessage("Updating channel " . $channel->rss_link . ". " . count($channel_items) . " channel items loaded from XML");
        $channel_items_model = TwibloModel::getInstance('channel_items');
        $channel_items_model->updateChannelItems($channel_items, $channel_id);
        return true;
    }

    public function getChannel($channel_id){
        $dbo = TwibloFactory::getDbo();
        $query = "SELECT * FROM `#__channels` WHERE `id` = " . $channel_id;
        return $dbo->selectSingle($query);
    }

    public function getChannelItemsFromXML($xml, $channel_id){
        $doc = new DOMDocument();
        if(!$doc->loadXML($xml) ) return false;
        $xpath = new DOMXPath($doc);
        $items_node_list = $xpath->query('/rss/channel/item');
        if($items_node_list->length == 0) return false;
        $items = array();
        foreach($items_node_list as $item_node){
            $item = new stdClass();
            $item->id = null;
            $item->channel_id = $channel_id;
            $item->guid = $xpath->query('guid', $item_node)->item(0)->nodeValue;
            $item->title = $xpath->query('title', $item_node)->item(0)->nodeValue;
            $item->description = $xpath->query('description', $item_node)->item(0)->nodeValue;
            $item->link = $xpath->query('link', $item_node)->item(0)->nodeValue;
            $pubDate = $xpath->query('pubDate', $item_node)->item(0)->nodeValue;
            $pubDate = DateTime::createFromFormat('D, d M Y H:i:s e', $pubDate);
            $item->pubDate = $pubDate->format('Y-m-d H:i:s');
            $enclosures = $xpath->query('enclosure', $item_node);
            if($enclosures->length != 0){
                $enclosure = $enclosures->item(0);
                $item->enclosure_url = $enclosure->getAttribute('url');
                $item->enclosure_type = $enclosure->getAttribute('type');
                $item->enclosure_length = $enclosure->getAttribute('length');
            }
            else {
                $item->enclosure_url = null;
                $item->enclosure_type = null;
                $item->enclosure_length = null;
            }
            $item->tweet_id = null;
            $items[] = $item;
        }
        return $items;
    }

}
