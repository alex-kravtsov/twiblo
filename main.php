<?php

define('TWIBLO_ROOT', dirname(__FILE__) );

require_once TWIBLO_ROOT . "/config/config.php";
require_once TWIBLO_ROOT . "/system/factory.php";

try {

    if(!date_default_timezone_set(TwibloConfig::TIMEZONE) ) throw new Exception("Invalid timezone identifier");
    if(!mb_internal_encoding(TwibloConfig::INTERNAL_ENCODING) ) throw new Exception("Cannot set internal encoding");
    if(!mb_regex_encoding(TwibloConfig::INTERNAL_ENCODING) ) throw new Exception("Cannot set regex encoding");

    $router = TwibloFactory::getRouter();
    $router->execute($argv);

}
catch(Exception $e){
    echo "Error:\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "Trace:\n";
    echo $e->getTraceAsString();
    exit(1);
}

exit(0);
