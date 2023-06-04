<?php

namespace Controllers;

use Constants;
use Controller;
use Models\Annonces;
use Models\Conversations;
use Models\Messages;
use Models\Users;
use Router;
use RouterDictionnary;
use Session;

class MyConversations extends Controller {

    private Users $usersModel;
    private Messages $messagesModel;
    private Annonces $annoncesModel;
    private Conversations $conversationsModel;

    public function __construct($aActions, $aQuery) {
        $this->actions = $aActions;
        $this->query = $aQuery;
        $this->minActions = 0;
        $this->maxActions = 0;
        $this->acceptedActions = [null];
        $this->acceptedMethods = ["GET"];
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

        // charge tous les models utilisés
        $this->usersModel = $this->loadModel("Users");
        $this->messagesModel = $this->loadModel("Messages");
        $this->annoncesModel = $this->loadModel("Annonces");
        $this->conversationsModel = $this->loadModel("Conversations");

        $this->setConversationsData();
        
        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - Mes discussion",
            "head-desc" => "Consulter vos différentes conversations avec les utilisateurs du site",
            "page-title" => "Mes discussion"
        ]);
        $this->render(RouterDictionnary::getView($this->controller));

    }

    private function setConversationsData() {

        // identifiant de l'utilisateur connecté
        $sUserId = Session::userGetId();
        // toutes les conversations de l'utilisateur
        $aConversations = $this->conversationsModel->getFromUser($sUserId);
        
        // prépare un tableau de données qui sera envoyé à la view
        $aConversationsData = [];
        if (!empty($aConversations)) {
            // pour chaque conversation
            foreach ($aConversations as $aConversation) {
                // récupère les informations du second utilisateur
                if ($aConversation["user_1_id"] != $sUserId) {
                    $aUser = $this->usersModel->getFromId($aConversation["user_1_id"]);
                } else {
                    $aUser = $this->usersModel->getFromId($aConversation["user_2_id"]);
                }
                // défini le pseudo de l'utilisateur qui à posté le dernier message
                if ($aConversation["last_message_user"] != $sUserId) {
                    $sLastMessageUser = $aUser["pseudo"];
                } else {
                    $sLastMessageUser = Session::userGetPseudo();
                }
                // récupère le nombre de message non-lus sur la conversation
                $nUnread = $this->messagesModel->getUnreadFromConversation($aConversation["_id"], $sUserId);
                // récupère l'annonce associée
                $aAnnonce = $this->annoncesModel->getFromId($aConversation["annonce_id"]);
                // ajoute les données dans le tableau qui sera renvoyé à la view
                array_push($aConversationsData, ["unread" => $nUnread, "conversation" => $aConversation, "annonce" => $aAnnonce, "with-user" => $aUser, "last-message-user" => $sLastMessageUser]);
            }
        }

        $this->data->set([
            "conversations" => $aConversationsData
        ]);

    }

}