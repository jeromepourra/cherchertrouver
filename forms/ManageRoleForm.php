<?php

namespace Forms;

use Constants;
use Form;

class ManageRoleForm extends Form {

    private $FIELDS = [
        "form-id" => true
    ];

    public function check($aUser) {

        if (isset($_POST["form-id"])) {
            $this->FIELDS += ["role-" . $_POST["form-id"] => true];
        }

        $this->initialize($this->FIELDS);

        $this->checkSends();
        $this->checkFulls();

        if ($this->success) {
            $this->checkRoleExist();
            $this->checkRoleDifferent($aUser);
            $this->checkRoleCanAttribute();
        }

        if ($this->success) {
            $this->response->resetFieldsValue();
            $this->response->pushSuccess("form-role-" . $_POST["form-id"], "L'utilisateur a changé de role");
        } else {
            $this->response->pushError("form-role-" . $_POST["form-id"], "Une erreur est survenue, veuillez vérifier les champs");
        }

        return $this->success;

    }

    private function checkRoleExist() {
        if (!array_key_exists((int) $_POST["form-id"], Constants::USER_ROLES)) {
            $this->success = false;
            $this->response->pushError("role-" . $_POST["form-id"], "Ce rôle n'existe pas");
        }
    }

    private function checkRoleDifferent($aUser) {
        if ($aUser["role"] == $_POST["role-" . $_POST["form-id"]]) {
            $this->success = false;
            $this->response->pushError("role-" . $_POST["form-id"], "Vous devez attribuer un rôle différent de l'actuel");
        }
    }

    private function checkRoleCanAttribute() {
        if (!array_key_exists((int) $_POST["form-id"], Constants::USER_ROLES)) {
            $this->success = false;
            $this->response->pushError("role-" . $_POST["form-id"], "Ce rôle n'existe pas");
        }
    }

}