<?php

abstract class TwibloFactory {

    public static function getDbo(){
        require_once TWIBLO_ROOT . "/system/dbo.php";
        $options = array();
        $options['db_host'] = TwibloConfig::DB_HOST;
        $options['db_user'] = TwibloConfig::DB_USER;
        $options['db_password'] = TwibloConfig::DB_PASSWORD;
        $options['db_name'] = TwibloConfig::DB_NAME;
        $options['db_charset'] = TwibloConfig::DB_CHARSET;
        $options['db_prefix'] = TwibloConfig::DB_PREFIX;
        $dbo = TwibloDbo::getInstance();
        $dbo->init($options);
        return $dbo;
    }

    public static function getLog(){
        require_once TWIBLO_ROOT . "/system/log.php";
        return TwibloLog::getInstance();
    }

    public static function getRouter(){
        require_once TWIBLO_ROOT . "/system/router.php";
        return TwibloRouter::getInstance();
    }

    public static function getTwitter(){
        require_once TWIBLO_ROOT . "/system/twitter.php";
        return TwibloTwitter::getInstance();
    }

}
