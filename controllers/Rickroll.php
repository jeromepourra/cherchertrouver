<?php

namespace Controllers;

use Constants;
use Controller;
use RouterDictionnary;

class Rickroll extends Controller {

    public function __construct($aActions, $aQuery) {
        $this->actions = $aActions;
        $this->query = $aQuery;
        $this->minActions = 0;
        $this->maxActions = 0;
        $this->acceptedActions = [null];
        $this->acceptedMethods = ["GET"];
        $this->initialize();
    }

    public function run() {
        $sFunc = "on" . $this->requestedMethod;
        $this->$sFunc();
    }

    private function onGET() {
        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - Rickroll !",
            "head-desc" => "You've been rolled ! :)",
            "script" => [
                WEB_ROOT . "/views/public/js/play-rick.js"
            ],
        ]);
        $this->render(RouterDictionnary::getView($this->controller));
    }

}