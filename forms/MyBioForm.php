<?php

namespace Forms;

use Form;
use Models\Users;
use Session;

class MyBioForm extends Form {

    private const FIELDS = [
        "change-bio-bio" => false
    ];

    private const BIO_MIN_LEN = 0;
    private const BIO_MAX_LEN = 256;

    private Users $userModel;

    public function check(Users $oUserModel) {

        $this->userModel = $oUserModel;
        $this->initialize(self::FIELDS);

        $this->checkSends();
        $this->checkFulls();
        
        if ($this->success) {
            
            $sUserId = Session::userGetId();
            $aUserModel = $this->userModel->getFromId($sUserId);
            
            $this->checkContent("change-bio-bio", self::BIO_MIN_LEN, self::BIO_MAX_LEN);
            $this->checkDifferent($aUserModel);

        }

        if ($this->success) {
            $this->response->resetFieldsValue();
            $this->response->pushSuccess("form-bio", "Votre bio a été modifié");
        } else {
            $this->response->pushError("form-bio", "Une erreur est survenue, veuillez vérifier les champs");
        }

        return $this->success;

    }

    private function checkDifferent($aUserModel) {
        $sNew = $_POST["change-bio-bio"];
        $sOld = $aUserModel["bio"];
        if ($sNew === $sOld) {
            $this->success = false;
            $this->response->pushError("change-bio-bio", "La nouvelle bio doit être différente de l'ancienne");
        }
    }

}