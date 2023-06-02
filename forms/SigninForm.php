<?php

namespace Forms;

use Constants;
use DateFormat;
use Form;
use Models\Banishments;
use Models\Users;

class SigninForm extends Form {

    private const FIELDS = [
        "name" => true,
        "password" => true
    ];

    private Users $userModel;
    private Banishments $banishmentsModel;

    public function check(Users $oUserModel, Banishments $oBanishmentsModel) {

        $this->userModel = $oUserModel;
        $this->banishmentsModel = $oBanishmentsModel;
        $this->initialize(self::FIELDS);

        $this->checkSends();
        $this->checkFulls();
        
        // si le check précedent s'est bien déroulé, alors
        if ($this->success) {
            $aUserModel = $this->checkName();
            // ...
            if ($this->success) {
                $this->checkPassword($aUserModel);
                // ...
                if ($this->success) {
                    $this->checkValidAccount($aUserModel);
                }
            }
        }

        if ($this->success) {
            $this->response->putData("user", $aUserModel);
            $this->response->resetFieldsValue();
            $this->response->pushSuccess("form", "Bienvenue <span class='fw-bold'>" . $aUserModel["pseudo"] . "</span> vous êtes désormais connecté !");
        } else {
            $response = $this->response->get();
            if (!isset($response["messages"]["errors"]["form"])) {
                $this->response->pushError("form", "Connexion refusée");
            }
        }

        return $this->success;

    }

    private function checkName() {
        $aUserModel = $this->userModel->getFromIdentifiant($_POST["name"]);
        if (empty($aUserModel)) {
            $this->success = false;
            if (Constants::DEV_MODE) {
                $this->response->pushError("name", "Identifiant incorrect");
            }
            $this->response->pushError("form", "Identifiant ou mot de passe incorrect");
        } else {
            return $aUserModel;
        }
    }

    private function checkPassword($aUserModel) {
        if (!password_verify($_POST["password"], $aUserModel["password"])) {
            $this->success = false;
            if (Constants::DEV_MODE) {
                $this->response->pushError("password", "Mot de passe incorrect");
            }
            $this->response->pushError("form", "Identifiant ou mot de passe incorrect");
        }
    }

    private function checkValidAccount($aUserModel) {
        if ($aUserModel["banned"]) {
            $this->success = false;
            $aBanishment = $this->banishmentsModel->getFromUserId($aUserModel["_id"]);
            if (!empty($aBanishment)) {
                $this->response->pushError("form", "Votre compte à été banni le " . DateFormat::format($aBanishment["date"]) . " <br> Raison(s) : " . $aBanishment["reason"]);
            } else {
                $this->response->pushError("form", "Votre compte à été banni");
            }
        }
    }

}