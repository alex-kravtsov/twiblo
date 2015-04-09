<?php

class TwibloTwitter {

    const TWIT_MAX_LENGTH = 140;

    private static $_instance = null;

    private $_connection = null;

    public function retweetStatus($tweet_id){
        $response = $this->_connection->post('statuses/retweet/' . $tweet_id);
        if(!empty($response->errors) ) throw new Exception("Cannot retweet status");
    }

    public function getUserTimeline($options=array() ){
        extract($options);
        $response = $this->_connection->get('statuses/user_timeline', array('screen_name'=>$screen_name, 'count'=>$count) );
        if(!empty($response->errors) ) throw new Exception("Cannot get user timeline");
        return $response;
    }

    public function updateStatus($twit){
        $status = $twit->title . "\n" . $twit->link;
        return $this->_connection->post("statuses/update", array('status'=>$status) );
    }

    public static function getInstance(){
        if(empty(self::$_instance) ){
            self::$_instance = new TwibloTwitter();
        }
        return self::$_instance;
    }

    private function __construct(){
        require_once TWIBLO_ROOT . "/lib/twitteroauth/autoload.php";
        $this->_connection = new Abraham\TwitterOAuth\TwitterOAuth(
            TwibloConfig::CONSUMER_KEY,
            TwibloConfig::CONSUMER_SECRET,
            TwibloConfig::ACCESS_TOKEN,
            TwibloConfig::ACCESS_TOKEN_SECRET
        );
    }

}