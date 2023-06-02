<?php

namespace Controllers;

use Controller;
use FormFileManager;
use Models\Annonces;
use Models\Categories;
use Router;
use RouterDictionnary;
use Session;

class AnnonceDelete extends Controller {

    private Categories $categoriesModel;
    private Annonces $annoncesModel;

    public function __construct($aActions, $aQuery) {
        $this->actions = $aActions;
        $this->query = $aQuery;
        $this->minActions = 1;
        $this->maxActions = 1;
        $this->acceptedActions = [
            "/^[0-9]+$/" => null
        ];
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
        $this->annoncesModel = $this->loadModel("Annonces");
        $this->categoriesModel = $this->loadModel("Categories");
        $this->remove();
    }

    private function remove() {

        $sActionAnnonceId = $this->actions[0];
        $aAnnonceModel = $this->annoncesModel->getFromId($sActionAnnonceId);

        if (!empty($aAnnonceModel)) {

            $sSessionUserId = Session::userGetId();
            $aCategoryModel = $this->categoriesModel->getFromId($aAnnonceModel["category_id"]);

            if ($sSessionUserId == $aAnnonceModel["user_id"]) {
                $this->annoncesModel->remove($aAnnonceModel["_id"]);
                $oFormFileManager = new FormFileManager();
                $oFormFileManager->removeAnnoncePictures($aAnnonceModel["_id"]);
                Session::___tmp___setModal("Annonce", "Votre annonce <span class='fw-bold'>" . $aAnnonceModel["title"] . "</span> dans la categorie <span class='fw-bold'>" . $aCategoryModel["name"] . "</span> a été retiré");
                Router::location(RouterDictionnary::getURL("AnnonceUser") . "/" . $sSessionUserId);
            } else {
                $this->on404();
            }

        } else {
            $this->on404();
        }

    }

}