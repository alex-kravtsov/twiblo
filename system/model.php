<?php

abstract class TwibloModel {
    
    public static function getInstance($model_name){
        $model_file = TWIBLO_ROOT . "/models/" . $model_name . ".php";
        if(!is_file($model_file) ) throw new Exception("Invalid model name");
        require_once $model_file;
        $model_classname = "TwibloModel" . $model_name;
        return new $model_classname;
    }

}
