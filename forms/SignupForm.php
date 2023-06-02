<?php

namespace Forms;

use Constants;
use DateTime;
use Form;
use Models\Users;
use RouterDictionnary;

class SignupForm extends Form {

    private const FIELDS = [
        "pseudo" => true,
        "firstname" => true,
        "lastname" => true,
        "email" => true,
        "phone" => true,
        "birthday" => true,
        "password" => true,
        "password-conf" => true,
        "bio" => false,
        "check-major" => true,
        "check-cgu" => true
    ];

    private const PSEUDO_MIN_LEN = 2;
    private const PSEUDO_MAX_LEN = 32;
    private const PSEUDO_REGEX = "/^[A-Za-z]*$/";
    private const PSEUDO_REGEX_ERROR = "Uniquement des caractères alphabétique, pas d'accents ni d'espace";

    private const PASSWORD_MIN_LEN = 8;
    private const PASSWORD_MAX_LEN = 60;
    private const PASSWORD_REGEX = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\-_*-+=!?.@#$%\s])[A-Za-z\d\-_*-+=!?.@#$%\s]*$/";
    private const PASSWORD_REGEX_ERROR = "Au moins 1 lettre minuscule, 1 lettre majuscule, 1 chiffre, 1 caractère spécial (-_*-+=!?.@#$%)";

    private const FIRSTNAME_MIN_LEN = 2;
    private const FIRSTNAME_MAX_LEN = 32;

    private const LASTNAME_MIN_LEN = 2;
    private const LASTNAME_MAX_LEN = 32;

    private const NAME_REGEX = "/^[A-Za-zÀ-ÖØ-öø-ÿ\-\s]*$/";
    private const NAME_REGEX_ERROR = "Uniquement des caractères alphabétique, des accents, des tirets, des espaces";

    private const EMAIL_MIN_LEN = 1;
    private const EMAIL_MAX_LEN = 64;

    private const BIO_MIN_LEN = 0;
    private const BIO_MAX_LEN = 256;

    private Users $userModel;

    public function check(Users $oUserModel) {

        $this->userModel = $oUserModel;
        $this->initialize(self::FIELDS);

        $this->checkSends();
        $this->checkFulls();

        if ($this->success) {

            $this->checkContent("pseudo", self::PSEUDO_MIN_LEN, self::PSEUDO_MAX_LEN, self::PSEUDO_REGEX, self::PSEUDO_REGEX_ERROR);
            $this->checkContent("password", self::PASSWORD_MIN_LEN, self::PASSWORD_MAX_LEN, self::PASSWORD_REGEX, self::PASSWORD_REGEX_ERROR);
            $this->checkContent("firstname", self::FIRSTNAME_MIN_LEN, self::FIRSTNAME_MAX_LEN, self::NAME_REGEX, self::NAME_REGEX_ERROR);
            $this->checkContent("lastname", self::LASTNAME_MIN_LEN, self::LASTNAME_MAX_LEN, self::NAME_REGEX, self::NAME_REGEX_ERROR);
            $this->checkContent("email", self::EMAIL_MIN_LEN, self::EMAIL_MAX_LEN);
            $this->checkEmail();
            $this->checkPhone();
            $this->checkBirthday();
            $this->checkPasswordConf();
            $this->checkContent("bio", self::BIO_MIN_LEN, self::BIO_MAX_LEN);
            $this->checkCheckbox("check-major");
            $this->checkCheckbox("check-cgu");
            $this->checkPseudoExists();
            $this->checkEmailExists();

        }

        if ($this->success) {
            $this->response->resetFieldsValue();
            $this->response->pushSuccess("form", "Bienvenue <span>" . $_POST["firstname"] . " " . $_POST["lastname"] . "</span>, vous avez été inscrit avec succès. <br> Un email de confirmation vous a été envoyé à l'adresse indiqué. Merci de bien vouloir confirmer votre adresse email.");
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

    private function checkPhone() {
        $sData = $_POST["phone"];
        $nDataLen = strlen($sData);
        if (!is_numeric($sData) || $nDataLen !== 10) {
            $this->success = false;
            $this->response->pushError("phone", "Ce numéro de téléphone n'est pas valide");
        }
    }

    private function checkBirthday() {
        $sFormat = "Y-m-d";
        $sData = $_POST["birthday"];
        $oDateTime = DateTime::createFromFormat($sFormat, $sData);
        if (!$oDateTime || $oDateTime->format($sFormat) ==! $sData) {
            $this->success = false;
            $this->response->pushError("birthday", "Doit être une date valide");
        } else {
            if ($oDateTime->getTimestamp() > time()) {
                $this->success = false;
                $this->response->pushError("birthday", "Wow! <br> Quoi?! <br> Mais oui!... <br> ... j'ai compris. <br> C'est donc ça! <br> La machine à voyager dans le temps existe bel et bien!");
            } else {
                $oDateInterval = date_diff($oDateTime, date_create());
                if ($oDateInterval->y < Constants::SIGNUP_REQUIRED_AGE) {
                    $this->success = false;
                    $this->response->pushError("birthday", "Vous devez être majeur pour vous inscrire");
                }
            }
        }
    }

    private function checkPasswordConf() {
        if ($_POST["password"] !== $_POST["password-conf"]) {
            $this->success = false;
            $this->response->pushError("password-conf", "Les mots de passes ne correspondent pas");
        }
    }

    private function checkPseudoExists() {
        if ($this->userModel->pseudoExists($_POST["pseudo"])) {
            $this->success = false;
            $this->response->pushError("pseudo", "Cet identifiant est déjà utilisé");
        }
    }

    private function checkEmailExists() {
        if ($this->userModel->emailExists($_POST["email"])) {
            $this->success = false;
            $this->response->pushError("email", "Cette adresse email est déjà utilisé");
        }
    }

}