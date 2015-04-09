<?php

class TwibloControllerChannels extends TwibloController {

    public function show_list($options=array() ){
        extract($options);
        $channels_model = $this->getModel('channels');
        $channels = $channels_model->getChannels();
        if(empty($channels) ){
            echo "\033[0;33mWe have no channels in the database\033[0m\n";
            return;
        }
        foreach($channels as $channel){
            echo "\033[0;36mId:\033[0m " . $channel->id . "\n";
            if(!empty($filter) ){
                switch($filter){
                case 'rss_link':
                    echo "\033[0;36mRSS Link:\033[0m " . $channel->rss_link . "\n";
                }
            }
            else {
                echo "\033[0;36mTitle:\033[0m " . $channel->title . "\n";
                echo "\033[0;36mLink:\033[0m " . $channel->link . "\n";
                echo "\033[0;36mDescription:\033[0m " . $channel->description . "\n";
                echo "\033[0;36mRSS Link:\033[0m " . $channel->rss_link . "\n";
            }
            echo "\033[0;36mActive:\033[0m " . ($channel->enabled ? 'Yes' : 'No') . "\n\n";
        }
    }

    public function update(){
        $channels_model = $this->getModel('channels');
        $active_channels = $channels_model->getActiveChannels();
        if(empty($active_channels) ){
            echo "\033[0;33mWe have no active channels in the database\033[0m\n";
            return;
        }
        $channel_model = $this->getModel('channel');
        foreach($active_channels as $channel){
            echo "\033[0;33mAttempt to update channel #" . $channel->id . " " . $channel->rss_link . "\033[0m\n";
            if($channel_model->updateChannel($channel->id) ){
                echo "\033[0;32mChannel updated OK\033[0m\n";
            }
            else {
                echo "\033[0;31mChannel update FAILED. See log for more information\033[0m\n";
            }
        }
    }

    public function show_statistics($options=array() ){
        $now = new DateTime();
        $channels_statistics_model = $this->getModel('channels_statistics');
        $channels_statistics_model->startCapture();
        $c_active_channels = $channels_statistics_model->getActiveChannelsCount();
        $c_total_items = $channels_statistics_model->getTotalItemsCount();
        $c_posted_items = $channels_statistics_model->getPostedItemsCount();
        $d_newest_item = $channels_statistics_model->getNewestItemDate();
        $d_oldest_item = $channels_statistics_model->getOldestItemDate();
        $channels_statistics_model->endCapture();
        echo "Channels statistics on " . $now->format('Y-m-d H:i:s') . "\n";
        echo "\033[0;34mActive channels:\033[0m " . $c_active_channels . "\n";
        echo "\033[0;34mTotal items:\033[0m " . $c_total_items . "\n";
        echo "\033[0;34mPosted items:\033[0m " . $c_posted_items . "\n";
        echo "\033[0;34mUtilization percent:\033[0m " . round( ($c_posted_items / $c_total_items) * 100) . "%\n";
        echo "\033[0;34mNewest item:\033[0m " . $d_newest_item . "\n";
        echo "\033[0;34mOldest item:\033[0m " . $d_oldest_item . "\n";
    }

}
