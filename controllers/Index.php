<?php

namespace Controllers;

use Constants;
use Controller;
use Models\Categories;
use RouterDictionnary;

class Index extends Controller {

    private Categories $categoriesModel;

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
        $this->categoriesModel = $this->loadModel("Categories");
        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - Accueil",
            "head-desc" => "",
            "page-title" => "Accueil",
            "script" => [
                WEB_ROOT . "/views/public/js/no-empty-get.js"
            ],
            "annonce" => [
                "categories" => $this->categoriesModel->getAll()
            ]
        ]);
        $this->render(RouterDictionnary::getView($this->controller));
    }

}