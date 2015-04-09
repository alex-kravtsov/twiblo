<?php

class TwibloRouter {

    private static $_instance = null;

    public function execute($argv){
        $route = $this->_extractRoute($argv);
        $controller_file = TWIBLO_ROOT . "/controllers/" . $route->controller . ".php";
        if(!is_file($controller_file) ) throw new Exception("Invalid controller");
        require_once TWIBLO_ROOT . "/system/controller.php";
        require_once TWIBLO_ROOT . "/system/model.php";
        require_once $controller_file;
        $controller_class = "TwibloController" . $route->controller;
        $controller = new $controller_class();
        $controller->{$route->action}($route->options);
    }

    private function _extractRoute($argv){
        $route = new stdClass();
        $route->options = array();
        $task = null;
        for($i = 1; $i < count($argv); $i++){
            if(preg_match("/--([^=[:blank:]]+)=([^=[:blank:]]+)/i", $argv[$i], $matches) ){
                if(empty($task) && $matches[1] == 'task') $task = $matches[2];
                else $route->options[$matches[1]] = $matches[2];
            }
        }
        if(empty($task) || !preg_match('/([^\.]+)\.([^\.]+)/', $task, $matches) ) throw new Exception("Invalid task");
        $route->controller = $matches[1];
        $route->action = $matches[2];
        return $route;
    }

    public static function getInstance(){
        if(empty(self::$_instance) ) self::$_instance = new TwibloRouter();
        return self::$_instance;
    }

    private function __construct(){
    }

}
