<?php

namespace Forms;

use Constants;
use Form;
use Models\Users;
use Session;

class MyContactForm extends Form {

    private const FIELDS = [
        "change-contact-favori" => true
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

            $this->checkExist();
            $this->checkDifferent($aUserModel);

        }

        if ($this->success) {
            $this->response->resetFieldsValue();
            $this->response->pushSuccess("form-contact", "Votre méthode de contact favorite a été modifié");
        } else {
            $this->response->pushError("form-contact", "Une erreur est survenue, veuillez vérifier les champs");
        }

        return $this->success;

    }

    private function checkDifferent($aUserModel) {
        $sNew = $_POST["change-contact-favori"];
        $sOld = $aUserModel["contact_favori"];
        if ($sNew === $sOld) {
            $this->success = false;
            $this->response->pushError("change-contact-favori", "Le nouveau contact favori doit être différent de l'ancien");
        }
    }

    private function checkExist() {
        $nData = (int) $_POST["change-contact-favori"];
        if (!array_key_exists($nData, Constants::CONTACTS_FAVORI)) {
            $this->success = false;
            $this->response->pushError("change-contact-favori", "Ce contact favori n'existe pas");
        }
    }

}