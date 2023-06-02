<?php

namespace Controllers;

use Constants;
use Controller;
use Models\Messages;
use Session;

class Layout404 extends Controller {

    private Messages $messagesModel;

    public function __construct() {
        $this->initialize(true);
    }

    public function run() {

        if (Session::userConnected()) {
            // $this->messagesModel = $this->loadModel("Messages");
            // $sUserId = Session::userGetId();
            // $bUnread = $this->messagesModel->haveUnreadedFromUser($sUserId);

            // $this->data->set([
            //     "message" => [
            //         "unread" => $bUnread
            //     ]
            // ]);
        }

        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - Whoops !"
        ]);

    }

}