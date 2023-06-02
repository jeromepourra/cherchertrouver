<?php

namespace Controllers;

use AnnonceState;
use Constants;
use Controller;
use Models\Annonces;
use Models\AnnoncesPictures;
use Models\Categories;
use Models\Users;
use RouterDictionnary;
use Session;

class Annonce extends Controller {

    private Users $usersModel;
    private Categories $categoriesModel;
    private Annonces $annoncesModel;
    private AnnoncesPictures $annoncesPicturesModel;

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
        $sFunc = "on" . $this->requestedMethod;
        $this->$sFunc();
    }

    private function onGET() {

        $this->usersModel = $this->loadModel("Users");
        $this->categoriesModel = $this->loadModel("Categories");
        $this->annoncesModel = $this->loadModel("Annonces");
        $this->annoncesPicturesModel = $this->loadModel("AnnoncesPictures");

        $this->setAnnonceData($aUserModel, $aAnnonceModel, $aCategoryModel, $bOwner);
        
        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - " . ($bOwner ? "Vos annonce" : "Annonce de " . $aUserModel["pseudo"]),
            "head-desc" => "Annonce de " . $aUserModel["pseudo"] . " dans la catégorie " . $aCategoryModel["name"] . " : " . $aAnnonceModel["title"],
            "page-title" => ($bOwner ? "Votre annonce" : "Annonce de " . $aUserModel["pseudo"]),
            "annonce" => [
                "categories" => $this->categoriesModel->getAll()
            ]
        ]);
        $this->render(RouterDictionnary::getView($this->controller));
    }

    private function setAnnonceData(&$aUserModel, &$aAnnonceModel, &$aCategoryModel, &$bOwner) {

        $sActionAnnonceId = $this->actions[0];
        $aAnnonceModel = $this->annoncesModel->getFromId($sActionAnnonceId);

        if (!empty($aAnnonceModel)) {

            $bOwner = false;
            $sSessionUserId = Session::userGetId();
            $aUserModel = $this->usersModel->getFromId($aAnnonceModel["user_id"]);

            if ($sSessionUserId == $aAnnonceModel["user_id"]) {
                $bOwner = true;
            } else {
                if (!Session::userGetRole() > 0 && (!AnnonceState::isStateOnline($aAnnonceModel["state"]) || $aUserModel["banned"])) {
                    $this->on404();
                }
            }

            $aCategoryModel = $this->categoriesModel->getFromId($aAnnonceModel["category_id"]);
            $aAnnoncesPicturesModel = $this->annoncesPicturesModel->getFromAnnonce($aAnnonceModel["_id"]);
            $aAnnonceData = [
                "annonce" => $aAnnonceModel,
                "pictures" => $aAnnoncesPicturesModel,
                "category" => $aCategoryModel["name"],
            ];

            $this->data->set([
                "annonce" => [
                    "owner" => $bOwner,
                    "user" => $aUserModel,
                    "annonce" => $aAnnonceData
                ]
            ]);

        } else {
            $this->on404();
        }

    }

}