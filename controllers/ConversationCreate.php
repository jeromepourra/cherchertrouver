<?php

namespace Controllers;

use AnnonceState;
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
        // vérification du champ message du formulaire
        $bFormSuccess = $this->messageSendForm->check();
        $aFormResponse = $this->messageSendForm->getResponse();

        if ($bFormSuccess) {

            // création de la conversation en base de données
            $aConversation = $this->conversationsModel->create([
                $aAnnonce["_id"],
                Session::userGetId(),
                $aUser["_id"],
                date("Y-m-d H:i:s", time()),
                Session::userGetId()
            ]);

            // création du message envoyé dans la base de données
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

        // la clef primaire de l'annonce provenant de l'URL
        $sAnnonceAction = $this->actions[0];
        // l'annonce récuperer en base de données
        $aAnnonce = $this->annoncesModel->getFromId($sAnnonceAction);

        // si l'annonce existe et que son statut est en ligne, alors
        if (!empty($aAnnonce) && $aAnnonce["state"] == AnnonceState::ANNONCE_STATE_ONLINE) {

            // récupérer l'utilisateur en base de données
            $aUser = $this->usersModel->getFromId($aAnnonce["user_id"]);

            // si l'utilisateur existe et qu'il n'est pas l'utilisateur connecté
            // et qu'il n'est pas banni, alors
            if (!empty($aUser) && $aUser["_id"] != Session::userGetId() && !$aUser["banned"]) {

                // prépare la data à envoyé à la view
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