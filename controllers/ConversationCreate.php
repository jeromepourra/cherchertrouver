<?php

namespace Controllers;

use Constants;
use Controller;
use Forms\MessageSendForm;
use Models\Annonces;
use Models\Conversations;
use Models\Messages;
use Models\Users;
use Router;
use RouterDictionnary;
use Session;

class ConversationCreate extends Controller {

    private Users $usersModel;
    private Messages $messagesModel;
    private Annonces $annoncesModel;
    private Conversations $conversationsModel;

    private MessageSendForm $messageSendForm;

    public function __construct($aActions, $aQuery) {
        $this->actions = $aActions;
        $this->query = $aQuery;
        $this->minActions = 1;
        $this->maxActions = 1;
        $this->acceptedActions = [
            "/^[0-9]+$/" => null
        ];
        $this->acceptedMethods = ["GET", "POST"];
        $this->initialize();
    }

    public function run() {

        if (!Session::userConnected()) {
            Router::location(RouterDictionnary::buildURL("Signin"));
        }

        if (!Session::userGetVerified()) {
            Router::location(RouterDictionnary::buildURL("EmailConfNeeded"));
        }

        $sFunc = "on" . $this->requestedMethod;
        $this->$sFunc();

    }

    private function onGET() {

        $this->usersModel = $this->loadModel("Users");
        $this->annoncesModel = $this->loadModel("Annonces");

        $this->setConversationData($aAnnonce, $aUser);
        
        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - Envoyer un message",
            "head-desc" => "Envoyer un message à " . $aUser["pseudo"],
            "page-title" => "Envoyer un message"
        ]);
        $this->render(RouterDictionnary::getView($this->controller));
    }

    private function onPOST() {

        $this->usersModel = $this->loadModel("Users");
        $this->annoncesModel = $this->loadModel("Annonces");
        $this->messagesModel = $this->loadModel("Messages");
        $this->conversationsModel = $this->loadModel("Conversations");

        $this->setConversationData($aAnnonce, $aUser);

        $this->messageSendForm = $this->loadForm("MessageSendForm");
        $bFormSuccess = $this->messageSendForm->check();
        $aFormResponse = $this->messageSendForm->getResponse();

        if ($bFormSuccess) {

            $aConversation = $this->conversationsModel->create([
                $aAnnonce["_id"],
                Session::userGetId(),
                $aUser["_id"],
                date("Y-m-d H:i:s", time()),
                Session::userGetId()
            ]);

            $this->messagesModel->create([
                $aConversation["_id"],
                Session::userGetId(),
                $_POST["content"],
                date("Y-m-d H:i:s", time())
            ]);

            Session::___tmp___setModal("Message", "Votre message a été envoyé à <span class='fw-bold'>" . $aUser["pseudo"] . "</span>");
            Router::location(RouterDictionnary::buildURL("Conversation", [$aConversation["_id"]]));
        }
        
        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - Envoyer un message",
            "head-desc" => "Envoyer un message à " . $aUser["pseudo"],
            "page-title" => "Envoyer un message",
            "form" => [
                "response" => $aFormResponse
            ]
        ]);
        $this->render(RouterDictionnary::getView($this->controller));
    }

    private function setConversationData(&$aAnnonce, &$aUser) {

        $sAnnonceAction = $this->actions[0];
        $aAnnonce = $this->annoncesModel->getFromId($sAnnonceAction);

        if (!empty($aAnnonce)) {

            $aUser = $this->usersModel->getFromId($aAnnonce["user_id"]);

            if (!empty($aUser) && $aUser["_id"] != Session::userGetId() && !$aUser["banned"]) {

                $this->data->set([
                    "conversation" => ["annonce" => $aAnnonce, "user" => $aUser]
                ]);

            } else {
                $this->on404();
            }

        } else {
            $this->on404();
        }

    }

}