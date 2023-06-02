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

class Conversation extends Controller {

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

        $sFunc = "on" . $this->requestedMethod;
        $this->$sFunc();

    }

    private function onGET() {

        $this->usersModel = $this->loadModel("Users");
        $this->messagesModel = $this->loadModel("Messages");
        $this->annoncesModel = $this->loadModel("Annonces");
        $this->conversationsModel = $this->loadModel("Conversations");

        $this->setMessagesData($aUser, $aAnnonce, $aMessages, $sUserId);

        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - Conversation",
            "head-desc" => "Consultez vos messages avec " . $aUser["pseudo"],
        ]);
        $this->render(RouterDictionnary::getView($this->controller));

        $this->setUnreadMessages($aMessages);
        
    }

    private function onPOST() {

        $this->usersModel = $this->loadModel("Users");
        $this->messagesModel = $this->loadModel("Messages");
        $this->annoncesModel = $this->loadModel("Annonces");
        $this->conversationsModel = $this->loadModel("Conversations");

        $this->messageSendForm = $this->loadForm("MessageSendForm");

        $this->setMessagesData($aUser, $aAnnonce, $aMessages, $sUserId);

        $this->messageSendForm = $this->loadForm("MessageSendForm");
        $bFormSuccess = $this->messageSendForm->check();
        $aFormResponse = $this->messageSendForm->getResponse();

        if ($bFormSuccess) {
            $this->messagesModel->create([
                $this->actions[0],
                Session::userGetId(),
                $_POST["content"],
                date("Y-m-d H:i:s", time())
            ]);
            $this->conversationsModel->updateLastMessageDate($this->actions[0]);
            $this->conversationsModel->updateLastMessageUser(Session::userGetId(), $this->actions[0]);
            Session::___tmp___setModal("Message", "Votre message a été envoyé");
            Router::location(RouterDictionnary::buildURL($this->controller, $this->actions));
        }

        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - Conversation",
            "head-desc" => "Consultez vos messages avec " . $aUser["pseudo"],
            "form" => [
                "response" => $aFormResponse
            ]
        ]);
        $this->render(RouterDictionnary::getView($this->controller));

        $this->setUnreadMessages($aMessages);

    }

    private function setMessagesData(&$aUser, &$aAnnonce, &$aMessages, &$sUserId) {

        $sMessageIdAction = $this->actions[0];
        $sUserId = Session::userGetId();
        $aConversation = $this->conversationsModel->getFromIdWhereUser($sMessageIdAction, $sUserId);

        $aConversationData = [];
        if (!empty($aConversation)) {

            if ($aConversation["user_1_id"] != $sUserId) {
                $aUser = $this->usersModel->getFromId($aConversation["user_1_id"]);
            } else {
                $aUser = $this->usersModel->getFromId($aConversation["user_2_id"]);
            }

            $aAnnonce = $this->annoncesModel->getFromId($aConversation["annonce_id"]);
            $aMessages = $this->messagesModel->getAllFromConversation($aConversation["_id"]);
            $aConversationData = ["conversation" => $aConversation, "annonce" => $aAnnonce, "messages" => $aMessages, "with-user" => $aUser];

        } else {
            $this->on404();
        }

        $this->data->set([
            "conversation" => $aConversationData
        ]);

    }

    private function setUnreadMessages($aMessages) {
        foreach ($aMessages as $aMessage) {
            if (Session::userGetId() != $aMessage["user_id"] && $aMessage["unread"]) {
                $this->messagesModel->updateUnread($aMessage["_id"]);
            }
        }
    }

}