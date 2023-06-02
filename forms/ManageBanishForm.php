<?php

namespace Forms;

use Form;

class ManageBanishForm extends Form {

    private $FIELDS = [
        "form-id" => true
    ];

    private const REASON_MIN_LEN = 2;
    private const REASON_MAX_LEN = 1024;

    public function check() {

        if (isset($_POST["form-id"])) {
            $this->FIELDS += ["reason-" . $_POST["form-id"] => true];
        }

        $this->initialize($this->FIELDS);

        $this->checkSends();
        $this->checkFulls();

        if ($this->success) {
            $this->checkContent("reason-" . $_POST["form-id"], self::REASON_MIN_LEN, self::REASON_MAX_LEN);
        }

        if ($this->success) {
            $this->response->resetFieldsValue();
            $this->response->pushSuccess("form-banish-" . $_POST["form-id"], "L'utilisateur a été banni");
        } else {
            $this->response->pushError("form-banish-" . $_POST["form-id"], "Une erreur est survenue, veuillez vérifier les champs");
        }

        return $this->success;

    }

}