<?php

class TwibloControllerChannel extends TwibloController {

    public function add($options=array() ){
        extract($options);
        $rss_feed_url = trim($rss_feed_url);
        if(empty($rss_feed_url) ){
            echo "\033[0;31mInvalid RSS feed URL\033[0m\n";
            return;
        }
        $channel_model = $this->getModel('channel');
        if(!$xml = $channel_model->getChannelXMLSource($rss_feed_url) ){
            echo "\033[0;31mCannot get channel XML source\033[0m\n";
            return;
        }
        if(!$channel = $channel_model->getChannelFromXML($xml, $rss_feed_url) ){
            echo "\033[0;31mInvalid channel XML source\033[0m\n";
            return;
        }
        if($channel->id = $channel_model->doesRssFeedExist($rss_feed_url) ){
            echo "\033[0;33mRSS feed already exists in the database. Channel data will be updated\033[0m\n";
        }
        $channel_id = $channel_model->saveChannel($channel);
        echo "\033[0;32mChannel #" . $channel_id . " saved\033[0m\n";
    }

    public function activate($options=array() ){
        extract($options);
        if(empty($channel_id) ){
            echo "\033[0;31mInvalid channel ID\033[0m\n";
            return;
        }
        $channel_model = $this->getModel('channel');
        if(!$channel_model->doesChannelExist($channel_id) ){
            echo "\033[0;31mChannel #" . $channel_id . " does not exist\033[0m\n";
            return;
        }
        if($channel_model->isChannelActive($channel_id) ){
            echo "\033[0;33mChannel #" . $channel_id . " is already active\033[0m\n";
            return;
        }
        $channel_model->activateChannel($channel_id);
        echo "\033[0;32mChannel #" . $channel_id . " activated\033[0m\n";
    }

}
