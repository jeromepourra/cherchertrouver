<?php

namespace Controllers;

use Controller;
use Models\Annonces;
use Models\Conversations;
use Models\Messages;
use Session;

class Layout extends Controller {

    private Messages $messagesModel;
    private Annonces $annoncesModel;
    private Conversations $conversationsModel;

    public function __construct() {
        $this->initialize(true);
    }

    public function run() {

        if (Session::userConnected()) {

            $this->messagesModel = $this->loadModel("Messages");
            $this->annoncesModel = $this->loadModel("Annonces");
            $this->conversationsModel = $this->loadModel("Conversations");

            // nombre de messages non-lus
            $nUnread = 0;
            $sUserId = Session::userGetId();
            // récupère toutes les conversations de l'utilisateur
            $aConversations = $this->conversationsModel->getFromUser($sUserId);
            if (!empty($aConversations)) {
                // pour chaque conversations
                foreach ($aConversations as $aConversation) {
                    // récupère tous les messages non-lus qui ne sont pas envoyé par l'utilisateur connecté
                    $nUnread += $this->messagesModel->getUnreadFromConversation($aConversation["_id"], $sUserId);
                }
            }
            $this->data->set([
                "unread" => $nUnread
            ]);
        }

    }

}