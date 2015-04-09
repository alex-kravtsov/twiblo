<?php

class TwibloControllerTwitter extends TwibloController {

    public function update($options=array() ){
        $twitter_model = $this->getModel('twitter');
        if(!$twitter_model->updateTwitterStatus() ){
            echo "\033[0;31mStatus update FAILED. See error log for more details\0330m\n";
            return;
        }
        echo "\033[0;32mStatus updated\033[0m\n";
    }

}
