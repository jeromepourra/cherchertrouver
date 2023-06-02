<?php

namespace Forms;

use Form;
use Models\Users;
use Session;

class MyEmailForm extends Form {

    private const FIELDS = [
        "change-email-email" => true,
        "change-email-password" => true
    ];

    private const EMAIL_MIN_LEN = 1;
    private const EMAIL_MAX_LEN = 64;

    private Users $userModel;

    public function check(Users $oUserModel) {

        $this->userModel = $oUserModel;
        $this->initialize(self::FIELDS);

        $this->checkSends();
        $this->checkFulls();

        if ($this->success) {

            $aSessionUser = Session::userGet();
            $aUserModel = $this->userModel->getFromId($aSessionUser["_id"]);

            $this->checkContent("change-email-email", self::EMAIL_MIN_LEN, self::EMAIL_MAX_LEN);
            $this->checkEmail();
            $this->checkDifferent($aUserModel);
            $this->checkPassword($aUserModel);

        }

        if ($this->success) {
            $this->response->resetFieldsValue();
            $this->response->pushSuccess("form-email", "Votre email a été modifié");
        } else {
            $this->response->pushError("form-email", "Une erreur est survenue, veuillez vérifier les champs");
        }

        return $this->success;

    }

    private function checkEmail() {
        $sData = $_POST["change-email-email"];
        if (!filter_var($sData, FILTER_VALIDATE_EMAIL)) {
            $this->success = false;
            $this->response->pushError("change-email-email", "Cette adresse email n'est pas valide");
        }
    }

    private function checkDifferent($aUserModel) {
        if ($_POST["change-email-email"] === $aUserModel["email"]) {
            $this->success = false;
            $this->response->pushError("change-email-email", "La nouvelle adresse email doit être différente de l'ancienne");
        }
    }

    private function checkPassword($aUserModel) {
        if (!password_verify($_POST["change-email-password"], $aUserModel["password"])) {
            $this->success = false;
            $this->response->pushError("change-email-password", "Mot de passe incorrect");
        }
    }

}