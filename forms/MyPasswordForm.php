<?php

namespace Forms;

use Form;
use Models\Users;
use Session;

class MyPasswordForm extends Form {

    private const FIELDS = [
        "change-password-old" => true,
        "change-password-new" => true,
        "change-password-conf" => true
    ];

    private const PASSWORD_MIN_LEN = 8;
    private const PASSWORD_MAX_LEN = 60;
    private const PASSWORD_REGEX = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\-_*-+=!?.@#$%\s])[A-Za-z\d\-_*-+=!?.@#$%\s]*$/";
    private const PASSWORD_REGEX_ERROR = "Au moins 1 lettre minuscule, 1 lettre majuscule, 1 chiffre, 1 caractère spécial (-_*-+=!?.@#$%)";

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
            $this->checkContent("change-password-new", self::PASSWORD_MIN_LEN, self::PASSWORD_MAX_LEN, self::PASSWORD_REGEX, self::PASSWORD_REGEX_ERROR);
            $this->checkDifferent();
            $this->checkPasswordConf();

        }

        if ($this->success) {
            $this->response->resetFieldsValue();
            $this->response->pushSuccess("form-password", "Votre mot de passe a été modifié");
        } else {
            $this->response->pushError("form-password", "Une erreur est survenue, veuillez vérifier les champs");
        }

        return $this->success;

    }

    private function checkPassword($aUserModel) {
        if (!password_verify($_POST["change-password-old"], $aUserModel["password"])) {
            $this->success = false;
            $this->response->pushError("change-password-old", "Mot de passe incorrect");
        }
    }

    private function checkDifferent() {
        if ($_POST["change-password-new"] === $_POST["change-password-old"]) {
            $this->success = false;
            $this->response->pushError("change-password-new", "Le nouveau mot de passe doit être différent de l'ancien");
        }
    }

    private function checkPasswordConf() {
        if ($_POST["change-password-new"] !== $_POST["change-password-conf"]) {
            $this->success = false;
            $this->response->pushError("change-password-conf", "Les mots de passes ne correspondent pas");
        }
    }

}