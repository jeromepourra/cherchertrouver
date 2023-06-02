<?php

namespace Controllers;

use AnnonceState;
use Constants;
use Controller;
use Models\Rates;
use Models\Users;
use Router;
use RouterDictionnary;
use Session;

class RateUser extends Controller {

    private Users $userModel;
    private Rates $ratesModel;

    public function __construct($aActions, $aQuery) {
        $this->actions = $aActions;
        $this->query = $aQuery;
        $this->minActions = 4;
        $this->maxActions = 4;
        $this->acceptedActions = [
            "user" => [
                "/^[0-9]+$/" => [
                    "rate" => [
                        "/^[0-9]+$/" => null
                    ]
                ]
            ]
        ];
        $this->acceptedMethods = ["GET"];
        $this->initialize();
    }

    public function run() {

        if (!Session::userConnected()) {
            Router::location(RouterDictionnary::buildURL("Signin"));
        }

        if (!Session::userGetVerified()) {
            Router::location(RouterDictionnary::buildURL("EmailConfNeeded"));
        }

        $sFunc = "on" . $this->requestedMethod;
        $this->$sFunc();
        
    }

    private function onGET() {

        $this->userModel = $this->loadModel("Users");
        $this->ratesModel = $this->loadModel("Rates");

        $sActionUser = $this->actions[1];
        $nActionRate = (int) $this->actions[3];

        $aUser = $this->userModel->getFromId($sActionUser);

        if (!empty($aUser) && Session::userGetId() != $aUser["_id"] && !$aUser["banned"]) {

            $bExists = $this->ratesModel->exists(Session::userGetId(), $aUser["_id"]);

            if (!$bExists && $nActionRate >= Constants::RATE_VAL_MIN && $nActionRate <= Constants::RATE_VAL_MAX) {
                
                $this->ratesModel->create([
                    Session::userGetId(),
                    $aUser["_id"],
                    $nActionRate
                ]);

                Router::location(Router::getReferer());

            } else {
                $this->on404();
            }

        } else {
            $this->on404();
        }

    }

}