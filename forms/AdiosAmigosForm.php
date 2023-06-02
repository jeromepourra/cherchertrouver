<?php

namespace Forms;

use Form;
use Models\Users;
use Session;

class AdiosAmigosForm extends Form {

    private const FIELDS = [
        "remove-account-password" => true,
        "remove-account-check" => true
    ];

    private Users $userModel;

    public function check(Users $oUserModel) {

        $this->userModel = $oUserModel;
        $this->initialize(self::FIELDS);

        $this->checkSends();
        $this->checkFulls();

        if ($this->success) {

            $aSessionUser = Session::userGet();
            $aUserModel = $this->userModel->getFromId($aSessionUser["_id"]);

            $this->checkPassword($aUserModel);
            $this->checkCheckbox("remove-account-check");

        }

        if ($this->success) {
            $this->response->resetFieldsValue();
            $this->response->pushSuccess("form-account", "Votre compte a été supprimé");
        } else {
            $this->response->pushError("form-account", "Une erreur est survenue, veuillez vérifier les champs");
        }

        return $this->success;

    }

    private function checkPassword($aUserModel) {
        if (!password_verify($_POST["remove-account-password"], $aUserModel["password"])) {
            $this->success = false;
            $this->response->pushError("remove-account-password", "Mot de passe incorrect");
        }
    }

}