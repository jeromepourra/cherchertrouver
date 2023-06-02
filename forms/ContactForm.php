<?php

namespace Forms;

use Constants;
use DateTime;
use Form;

class ContactForm extends Form {

    private const FIELDS = [
        "firstname" => true,
        "lastname" => true,
        "email" => true,
        "message" => true
    ];

    private const FIRSTNAME_MIN_LEN = 2;
    private const FIRSTNAME_MAX_LEN = 32;

    private const LASTNAME_MIN_LEN = 2;
    private const LASTNAME_MAX_LEN = 32;

    private const NAME_REGEX = "/^[A-Za-zÀ-ÖØ-öø-ÿ\-\s]*$/";
    private const NAME_REGEX_ERROR = "Uniquement des caractères alphabétique, des accents, des tirets, des espaces";

    private const EMAIL_MIN_LEN = 1;
    private const EMAIL_MAX_LEN = 64;

    private const MESSAGE_MIN_LEN = 3;
    private const MESSAGE_MAX_LEN = 1024;

    public function check() {

        $this->initialize(self::FIELDS);

        $this->checkSends();
        $this->checkFulls();

        if ($this->success) {

            $this->checkContent("firstname", self::FIRSTNAME_MIN_LEN, self::FIRSTNAME_MAX_LEN, self::NAME_REGEX, self::NAME_REGEX_ERROR);
            $this->checkContent("lastname", self::LASTNAME_MIN_LEN, self::LASTNAME_MAX_LEN, self::NAME_REGEX, self::NAME_REGEX_ERROR);
            $this->checkContent("email", self::EMAIL_MIN_LEN, self::EMAIL_MAX_LEN);
            $this->checkEmail();
            $this->checkContent("message", self::MESSAGE_MIN_LEN, self::MESSAGE_MAX_LEN);

        }

        if ($this->success) {
            $this->response->resetFieldsValue();
            $this->response->pushSuccess("form", "Merci, nous avons bien reçu votre message");
        } else {
            $this->response->pushError("form", "Une erreur est survenue, veuillez vérifier les champs");
        }

        return $this->success;

    }

    private function checkEmail() {
        $sData = $_POST["email"];
        if (!filter_var($sData, FILTER_VALIDATE_EMAIL)) {
            $this->success = false;
            $this->response->pushError("email", "Cette adresse email n'est pas valide");
        }
    }

}