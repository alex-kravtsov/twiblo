<?php

class TwibloModelTwitter extends TwibloModel {

    const UPDATE_METHOD_TWIT = 0;
    const UPDATE_METHOD_RETWIT = 1;

    public function updateTwitterStatus(){
        $log = TwibloFactory::getLog();
        $log->writeMessage("Attempt to update twitter status");
        $interval = $this->_getDelayInterval();
        $log->writeMessage("Wait " . number_format( ($interval / 60), 2) . " minutes");
        $this->_applyDelay($interval);
        $method = $this->_getUpdateMethod();
        switch($method){
            case self::UPDATE_METHOD_TWIT:
                $log->writeMessage("Use update method TWIT");
                if(!$twit = $this->_getTwitFromRandomChannelItem() ){
                    $log->writeError("Cannot get twit from channel item");
                    return false;
                }
                $log->writeMessage("Selected channel item #" . $twit->id . ": " . $twit->title);
                if($twit = $this->_updateRemoteTwit($twit) ){
                    $log->writeMessage("Remote update OK");
                    $this->_updateLocalTwit($twit);
                    return true;
                }
                break;
            case self::UPDATE_METHOD_RETWIT:
                $log->writeMessage("Use update method RETWIT");
                break;
            default:
                $log->writeError("Invalid update method");
                return false;
        }
        return false;
    }

    private function _getDelayInterval(){
        return mt_rand(0, TwibloConfig::MAX_TWITTER_UPDATE_DELAY * 60);
    }

    private function _applyDelay($interval){
        sleep($interval);
    }

    private function _getUpdateMethod(){
        //TODO: Develop algorithm to set 'twit' or 'retwit' update method from some number sequence (from settings)
        return 0;
    }

    private function _getTwitFromRandomChannelItem(){
        $db = TwibloFactory::getDbo();
        $query = "SELECT * FROM `#__channel_items` WHERE `tweet_id` IS NULL ORDER BY RAND() LIMIT 1";
        return $db->selectSingle($query);
    }

    private function _updateRemoteTwit($twit){
        $log = TwibloFactory::getLog();
        $connection = TwibloFactory::getTwitter();
        $response = $connection->updateStatus($twit);
        if(!empty($response->errors) ){
            foreach($response->errors as $error){
                $log->writeError("Twitter connection error: #" . $error->code . " " . $error->message);
            }
            return false;
        }
        $twit->tweet_id = $response->id_str;
        return $twit;
    }

    private function _updateLocalTwit($twit){
        $db = TwibloFactory::getDbo();
        $query = "UPDATE `#__channel_items` SET `tweet_id` = '" . $twit->tweet_id . "' WHERE `id` = " . $twit->id;
        $db->update($query);
    }

}
