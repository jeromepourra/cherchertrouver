<?php

namespace Controllers;

use Constants;
use Controller;
use Models\Users;
use Models\Banishments;
use Forms\SigninForm;
use FrontData;
use Router;
use RouterDictionnary;
use Session;

class Signin extends Controller {

    private Users $usersModel;
    private Banishments $banishmentsModel;
    private SigninForm $signinForm;

    public function __construct($aActions, $aQuery) {
        $this->actions = $aActions;
        $this->query = $aQuery;
        $this->minActions = 0;
        $this->maxActions = 0;
        $this->acceptedActions = [null];
        $this->acceptedMethods = ["GET", "POST"];
        $this->initialize();
    }

    public function run() {
        if (!Session::userConnected()) {
            $sFunc = "on" . $this->requestedMethod;
            $this->$sFunc();
        } else {
            $this->on404();
        }
    }

    private function onGET() {
        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - Connexion",
            "head-desc" => "Connectez vous à votre espace",
            "page-title" => "Connexion"
        ]);
        $this->render(RouterDictionnary::getView($this->controller));
    }

    private function onPOST() {

        $this->usersModel = $this->loadModel("Users");
        $this->banishmentsModel = $this->loadModel("Banishments");
        $this->signinForm = $this->loadForm("SigninForm");
        
        $bFormSuccess = $this->signinForm->check($this->usersModel, $this->banishmentsModel);
        $aFormResponse = $this->signinForm->getResponse();

        if ($bFormSuccess) {
            $this->usersModel->updateConnectionDate($aFormResponse["data"]["user"]["_id"]);
            Session::userConnect($aFormResponse["data"]["user"]);
            $aUser = Session::userGet();
            Session::___tmp___setModal("Connexion", "Bonjour <span class='fw-bold'>" . $aUser["firstname"] . " " . $aUser["lastname"] . "</span> ! <br> Vous êtes connecté au compte <span class='fw-bold'>" . $aUser["pseudo"] . " !");
            Router::location(RouterDictionnary::getURL());
        }

        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - Connexion",
            "page-title" => "Connexion",
            "form" => [
                "response" => $aFormResponse
            ]
        ]);
        $this->render(RouterDictionnary::getView($this->controller));

    }

}