<?php

abstract class TwibloController {

    public function getModel($model_name){
        return TwibloModel::getInstance($model_name);
    }

}
