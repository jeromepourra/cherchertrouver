<?php

namespace Controllers;

use Constants;
use Controller;
use Forms\ContactForm;
use Mailer;
use Router;
use RouterDictionnary;
use Session;

class Contact extends Controller {

    private ContactForm $contactForm;

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
        $sFunc = "on" . $this->requestedMethod;
        $this->$sFunc();
    }

    private function onGET() {
        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - Contactez-nous",
            "head-desc" => "Entrez en contact avec nous via notre formulaire ou bien directement avec notre adresse email",
            "page-title" => "Contactez-nous"
        ]);
        $this->render(RouterDictionnary::getView($this->controller));
    }

    private function onPOST() {

        $this->contactForm = $this->loadForm("ContactForm");
        $bFormSuccess = $this->contactForm->check();
        $aFormResponse = $this->contactForm->getResponse();

        if ($bFormSuccess) {
            $this->sendEmail();
            Session::___tmp___setModal("Contact", "Merci, nous avons bien reçu votre message");
        }

        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - Contact",
            "head-desc" => "Entrez en contact avec nous via notre formulaire ou bien directement avec notre adresse email",
            "page-title" => "Contact",
            "form" => [
                "response" => $aFormResponse
            ]
        ]);
        $this->render(RouterDictionnary::getView($this->controller));

    }

    private function sendEmail() {
        require ROOT . "/mailer/Mailer.php";
        Mailer::send(
            "contact",
            $_POST["email"],
            $_POST["firstname"] . " " . $_POST["lastname"],
            Constants::EMAIL_NO_REPLY,
            Constants::WEB_SITE_NAME,
            "Contact depuis le site",
            $_POST
        );
    }

}