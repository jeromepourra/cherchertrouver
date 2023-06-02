<?php

namespace Controllers;

use Constants;
use Controller;
use FrontData;
use Router;
use RouterDictionnary;
use Session;

class Signout extends Controller {

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
        if (Session::userConnected()) {
            $sFunc = "on" . $this->requestedMethod;
            $this->$sFunc();
        } else {
            $this->on404();
        }
    }

    private function onGET() {

        $aUser = Session::userGet();
        Session::userDisconnect();

        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - Deconnexion",
            "head-desc" => "Deconnectez vous de votre espace",
            "page-title" => "Deconnexion"
        ]);

        Session::___tmp___setModal("Deconnexion", "Vous êtes déconnecté du compte <span class='fw-bold'>" . $aUser["pseudo"] . "</span> <br> À bientôt <span class='fw-bold'>" . $aUser["firstname"] . " " . $aUser["lastname"] . "</span> !");
        Router::location(RouterDictionnary::getURL());

    }

}