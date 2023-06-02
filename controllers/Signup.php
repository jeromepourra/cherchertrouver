<?php

namespace Controllers;

use Constants;
use Controller;
use EmailKey;
use Forms\SignupForm;
use Mailer;
use Models\EmailsConfirmations;
use Models\Users;
use RouterDictionnary;
use Session;

class Signup extends Controller {

    private Users $usersModel;
    private EmailsConfirmations $emailsConfsModel;
    private SignupForm $signupForm;

    public function __construct($aActions, $aQuery) {
        $this->actions = $aActions;
        $this->query = $aQuery;
        $this->minActions = 0;
        $this->maxActions = 0;
        $this->acceptedActions = [null];
        $this->acceptedMethods = ["GET", "POST"];
        $this->initialize();
    }

    public function run() {
        if (!Session::userConnected()) {
            $sFunc = "on" . $this->requestedMethod;
            $this->$sFunc();
        } else {
            $this->on404();
        }
    }

    private function onGET() {
        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - Inscription",
            "head-desc" => "Vous inscrire à notre site vous permettra de poster de nouvelles annonces, ainsi que de contacter nos utilisateurs",
            "page-title" => "Inscription"
        ]);
        $this->render(RouterDictionnary::getView($this->controller));
    }

    private function onPOST() {

        $this->usersModel = $this->loadModel("Users");
        $this->emailsConfsModel = $this->loadModel("EmailsConfirmations");
        $this->signupForm = $this->loadForm("SignupForm");
        $bFormSuccess = $this->signupForm->check($this->usersModel);

        if ($bFormSuccess) {
            $sEmailKey = EmailKey::generate();
            $aUser = $this->usersModel->create([
                $_POST["pseudo"],
                $_POST["firstname"],
                $_POST["lastname"],
                strtolower($_POST["email"]),
                $_POST["phone"],
                $_POST["birthday"],
                $_POST["bio"],
                password_hash($_POST["password"], PASSWORD_BCRYPT, Constants::PASSWORD_HASH_OPTIONS),
                date("Y-m-d H:i:s", time())
            ]);
            $this->emailsConfsModel->create([
                $aUser["_id"],
                $sEmailKey
            ]);
            $this->sendEmail($sEmailKey, $aUser);
        }

        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - Inscription",
            "page-title" => "Inscription",
            "form" => [
                "response" => $this->signupForm->getResponse()
            ]
        ]);
        $this->render(RouterDictionnary::getView($this->controller));

    }

    private function sendEmail($sKey, $aUser) {
        require ROOT . "/mailer/Mailer.php";
        Mailer::send(
            "confirmation",
            Constants::EMAIL_NO_REPLY,
            Constants::WEB_SITE_NAME,
            $aUser["email"], 
            $aUser["firstname"] . " " . $aUser["lastname"],
            "Confirmation d'inscription",
            [
                "user" => $aUser,
                "key" => $sKey
            ]
        );
    }

}