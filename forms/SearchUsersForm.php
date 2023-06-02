<?php

namespace Forms;

use AnnonceState;
use Constants;
use Form;
use Models\Categories;

class SearchUsersForm extends Form {

    private const METHOD = "GET";
    private const FIELDS = [
        "sort" => true,
        "role" => true,
        "key-word" => true,
        "state" => true
    ];

    public function check() {

        $this->initialize(self::FIELDS, [], self::METHOD);

        $this->checkFulls();

        $this->checkRole();

        if ($this->success) {
            // NO RESET
        } else {
            $this->response->pushError("form", "Une erreur est survenue, veuillez vérifier les champs");
        }

        return $this->success;

    }

    private function checkRole() {
        if (!array_key_exists((int) $_GET["role"], Constants::USER_ROLES)) {
            $this->success = false;
            $this->response->pushError($_GET["role"], "Ce rôle n'existe pas");
        }
    }

}