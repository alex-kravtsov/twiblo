<?php

class TwibloControllerLog extends TwibloController {

    public function show_statistics($options=array() ){
        $log_model = $this->getModel('log');   
        $log_model->startLogCapture();
        $now = new DateTime();
        $cmessages = $log_model->getMessagesCount();
        $cwarnings = $log_model->getWarningsCount();
        $cerrors = $log_model->getErrorsCount();
        $ctotal = $log_model->getRecordsTotal();
        $log_model->endLogCapture();
        echo "Log statistics on " . $now->format('Y-m-d H:i:s') . "\n";
        echo "\033[0;32mMessages:\033[0m " . $cmessages . "\n";
        echo "\033[0;33mWarnings:\033[0m " . $cwarnings . "\n";
        echo "\033[0;31mErrors:\033[0m " . $cerrors . "\n";
        echo "\033[0;34mTotal:\033[0m " . $ctotal . "\n";
    }

    public function clean_outdated($options=array() ){
        $keep_log_days = TwibloConfig::KEEP_LOG_DAYS;
        $now = new DateTime();
        $now_timestamp = $now->getTimestamp();
        $keep_timestamp = $now_timestamp - (60 * 60 * 24 * $keep_log_days);
        $keep_date = $now->setTimestamp($keep_timestamp);
        $log_model = $this->getModel('log');
        $cremoved = $log_model->cleanRecordsBeforeDate($keep_date->format('Y-m-d H:i:s') );
        echo "\033[0;32m" . $cremoved . " outdated log records was successfully removed\033[0m\n";
    }

}
