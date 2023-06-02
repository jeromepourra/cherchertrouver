<?php

namespace Controllers;

use Constants;
use Controller;
use Models\EmailsConfirmations;
use Models\Users;
use RouterDictionnary;
use Session;

class EmailConf extends Controller {

    private Users $usersModel;
    private EmailsConfirmations $emailsConfsModel;

    public function __construct($aActions, $aQuery) {
        $this->actions = $aActions;
        $this->query = $aQuery;
        $this->minActions = 1;
        $this->maxActions = 1;
        $this->acceptedActions = [
            "/^[a-zA-Z0-9\-_]+$/" => null
        ];
        $this->acceptedMethods = ["GET"];
        $this->initialize();
    }

    public function run() {
        $sFunc = "on" . $this->requestedMethod;
        $this->$sFunc();
    }

    private function onGET() {

        $this->usersModel = $this->loadModel("Users");
        $this->emailsConfsModel = $this->loadModel("EmailsConfirmations");

        $sActionKey = $this->actions[0];
        $aEmailConf = $this->emailsConfsModel->getFromKey($sActionKey);

        if (!empty($aEmailConf)) {
            $aUser = $this->usersModel->getFromId($aEmailConf["user_id"]);
            if (!empty($aUser)) {
                $this->emailsConfsModel->remove($aEmailConf["user_id"]);
                $this->usersModel->updateEmailConfirmation(1, $aEmailConf["user_id"]);
                Session::userSetVerified(true);
            } else {
                $this->on404();
            }
        } else {
            $this->on404();
        }

        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - Confirmation de votre adresse email",
            "head-desc" => "Votre adresse email a été confirmée",
            "page-title" => "Confirmation de votre adresse email",
            "account" => [
                "user" => $aUser
            ]
        ]);
        $this->render(RouterDictionnary::getView($this->controller));

    }

}