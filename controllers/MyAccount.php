<?php

namespace Controllers;

use Constants;
use Controller;
use EmailKey;
use Forms\AdiosAmigosForm;
use Forms\MyBioForm;
use Forms\MyContactForm;
use Forms\MyEmailForm;
use Forms\MyPasswordForm;
use Mailer;
use Models\EmailsConfirmations;
use Models\Users;
use Router;
use RouterDictionnary;
use Session;

class MyAccount extends Controller {

    private Users $usersModel;
    private MyBioForm $myBioForm;
    private MyContactForm $myContactForm;
    private MyEmailForm $myEmailForm;
    private MyPasswordForm $myPasswordForm;
    private AdiosAmigosForm $adiosAmigosForm;
    private EmailsConfirmations $emailsConfsModel;

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
            Router::location(RouterDictionnary::buildURL("Signin"));
        }

        $sFunc = "on" . $this->requestedMethod;
        $this->$sFunc();

    }

    private function onGET() {

        $this->usersModel = $this->loadModel("Users");

        $sUserId = Session::userGetId();
        $aUserModel = $this->usersModel->getFromId($sUserId);

        if (isset($this->query["action"])) {
            switch ($this->query["action"]) {
                case "email-send-back" :
                    if (!Session::userGetVerified()) {
                        $this->emailsConfsModel = $this->loadModel("EmailsConfirmations");
                        $sEmailKey = EmailKey::generate();
                        $this->emailsConfsModel->remove($aUserModel["_id"]);
                        $this->emailsConfsModel->create([
                            $aUserModel["_id"],
                            $sEmailKey
                        ]);
                        $this->sendEmail($sEmailKey, $aUserModel);
                        Session::userSetVerified(false);
                        Session::___tmp___setModal("Renvoi d'un email", "Un email de confirmation vous a été envoyé à l'adresse indiqué. Merci de bien vouloir confirmer votre adresse email."); 
                    } else {
                        $this->on404();
                    }
                    break;
            }
        }

        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - Mon compte",
            "head-desc" => "Modifiez les paramètres de votre profil utilisateur",
            "page-title" => "Mon compte",
            "user" => $aUserModel
        ]);
        $this->render(RouterDictionnary::getView("MyAccount"));

    }

    private function onPOST() {

        if (isset($this->query["action"])) {

            $this->usersModel = $this->loadModel("Users");

            $aUserSession = Session::userGet();
            $sUserId = Session::userGetId();
            $aUserModel = $this->usersModel->getFromId($sUserId);

            switch ($this->query["action"]) {
                case "bio":
                    $this->myBioForm = $this->loadForm("MyBioForm");
                    $bFormSuccess = $this->myBioForm->check($this->usersModel);
                    if ($bFormSuccess) {
                        $this->usersModel->updateBio($_POST["change-bio-bio"], $sUserId);
                        $aUserModel = $this->usersModel->getFromId($sUserId);
                        Session::___tmp___setModal("Changement de bio", "Votre bio a correctement été modifié");
                    }
                    $this->data->set([
                        "form" => [
                            "response" => $this->myBioForm->getResponse()
                        ]
                    ]);
                    break;
                case "contact":
                    $this->myContactForm = $this->loadForm("MyContactForm");
                    $bFormSuccess = $this->myContactForm->check($this->usersModel);
                    if ($bFormSuccess) {
                        $this->usersModel->updateContact($_POST["change-contact-favori"], $sUserId);
                        $aUserModel = $this->usersModel->getFromId($sUserId);
                        Session::___tmp___setModal("Changement de contact favori", "Votre méthode de contact favorite a correctement été modifié");
                    }
                    $this->data->set([
                        "form" => [
                            "response" => $this->myContactForm->getResponse()
                        ]
                    ]);
                    break;
                case "email":

                    $this->myEmailForm = $this->loadForm("MyEmailForm");
                    $this->emailsConfsModel = $this->loadModel("EmailsConfirmations");

                    $bFormSuccess = $this->myEmailForm->check($this->usersModel);

                    if ($bFormSuccess) {
                        $sEmailLower = strtolower($_POST["change-email-email"]);
                        $this->usersModel->updateEmail($sEmailLower, $sUserId);
                        $this->usersModel->updateEmailConfirmation(0, $sUserId);
                        $aUserModel = $this->usersModel->getFromId($sUserId);
                        $sEmailKey = EmailKey::generate();
                        $this->emailsConfsModel->remove($aUserModel["_id"]);
                        $this->emailsConfsModel->create([
                            $aUserModel["_id"],
                            $sEmailKey
                        ]);
                        $this->sendEmail($sEmailKey, $aUserModel);
                        Session::userSetVerified(false);
                        Session::___tmp___setModal("Changement d'adresse email", "Votre email a correctement été modifié. <br> Un email de confirmation vous a été envoyé à l'adresse indiqué. Merci de bien vouloir confirmer votre adresse email.");
                    }
                    $this->data->set([
                        "form" => [
                            "response" => $this->myEmailForm->getResponse()
                        ]
                    ]);
                    break;
                case "password":
                    $this->myPasswordForm = $this->loadForm("MyPasswordForm");
                    $bFormSuccess = $this->myPasswordForm->check($this->usersModel);
                    if ($bFormSuccess) {
                        $sPasswordHash = password_hash($_POST["change-password-new"], PASSWORD_BCRYPT, Constants::PASSWORD_HASH_OPTIONS);
                        $this->usersModel->updatePassword($sPasswordHash, $sUserId);
                        $aUserModel = $this->usersModel->getFromId($sUserId);
                        Session::___tmp___setModal("Changement de mot de passe", "Votre mot de passe a correctement été modifié");
                    }
                    $this->data->set([
                        "form" => [
                            "response" => $this->myPasswordForm->getResponse()
                        ]
                    ]);
                    break;
                case "remove":
                    $this->adiosAmigosForm = $this->loadForm("AdiosAmigosForm");
                    $bFormSuccess = $this->adiosAmigosForm->check($this->usersModel);
                    if ($bFormSuccess) {
                        $this->usersModel->removeUser($sUserId);
                        $aUserModel = $this->usersModel->getFromId($sUserId);
                        Session::userDisconnect();
                        Session::___tmp___setModal("Suppression de compte", "Votre compte a été supprimé. Nous sommes navrés de vous voir nous quitter... Vous pouvez cependant recréer un compte à tout moment. Au revoir <span class='fw-bold'>" . $aUserSession["firstname"] . " " . $aUserSession["lastname"] . "</span> et peut être à bientôt.");
                        Router::location(RouterDictionnary::getURL());
                    }
                    $this->data->set([
                        "form" => [
                            "response" => $this->adiosAmigosForm->getResponse()
                        ]
                    ]);
                    break;
                default:
                    $this->on404();
            }


        } else {
            $this->on404();
        }

        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - Mon compte",
            "head-desc" => "Modifiez les paramètres de votre profil utilisateur",
            "page-title" => "Mon compte",
            "user" => $aUserModel
        ]);
        $this->render(RouterDictionnary::getView("MyAccount"));
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