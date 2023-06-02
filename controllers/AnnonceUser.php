<?php

namespace Controllers;

use Constants;
use Controller;
use Models\Annonces;
use Models\AnnoncesPictures;
use Models\Categories;
use Models\Rates;
use Models\Users;
use RouterDictionnary;
use Session;

class AnnonceUser extends Controller {

    private Users $usersModel;
    private Rates $ratesModel;
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
        $this->ratesModel = $this->loadModel("Rates");
        $this->categoriesModel = $this->loadModel("Categories");
        $this->annoncesModel = $this->loadModel("Annonces");
        $this->annoncesPicturesModel = $this->loadModel("AnnoncesPictures");

        $this->setAnnoncesData($aUserModel, $bOwner);
        
        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - " . ($bOwner ? "Vos annonces" : "Annonces de " . $aUserModel["pseudo"]),
            "head-desc" => "Voir le profil ainsi que toutes les annonces de " . $aUserModel["pseudo"],
            "page-title" => ($bOwner ? "Vos annonces" : "Annonces de " . $aUserModel["pseudo"]),
            "script" => [
                WEB_ROOT . "/views/public/js/rate.js"
            ],
            "annonce" => [
                "categories" => $this->categoriesModel->getAll()
            ]
        ]);
        $this->render(RouterDictionnary::getView($this->controller));
    }

    private function setRateData($sUserId) {

        $aRate = $this->ratesModel->getRateAvgFromUser($sUserId);
        $aCountRate = $this->ratesModel->getRateCountFromUser($sUserId);

        if (Session::userGetId() != $sUserId) {
            $bCanRate = !$this->ratesModel->exists(Session::userGetId(), $sUserId);
        } else {
            $bCanRate = false;
        }

        $aMyRate = $this->ratesModel->getMyRate(Session::userGetId(), $sUserId);
        if (!empty($aMyRate)) {
            $nMyRate = (int) $aMyRate[0];
        } else {
            $nMyRate = 0;
        }

        $this->data->set([
            "rate" => [
                "value" => $aRate,
                "count" => $aCountRate,
                "can-rate" => $bCanRate,
                "your-rate" => $nMyRate
            ]
        ]);

    }

    private function setAnnoncesData(&$aUserModel, &$bOwner) {

        $sActionUserId = $this->actions[0];
        $aUserModel = $this->usersModel->getFromId($sActionUserId);

        if (!empty($aUserModel) && (!$aUserModel["banned"] || Session::userGetRole() > 0)) {

            $bOwner = false;
            $sSessionUserId = Session::userGetId();
            $this->setRateData($aUserModel["_id"]);

            if ($sSessionUserId == $aUserModel["_id"]) {
                $bOwner = true;
                $this->setAnnoncesModel($aAnnoncesModel, $aUserModel, "getFromUser");
            } else {
                $this->setAnnoncesModel($aAnnoncesModel, $aUserModel, "getOnlineFromUser");
            }

            $aAnnoncesData = [];

            foreach ($aAnnoncesModel as $aAnnonce) {
                $aCategoryModel = $this->categoriesModel->getFromId($aAnnonce["category_id"]);
                $aAnnoncesPictureModel = $this->annoncesPicturesModel->getOneFromAnnonce($aAnnonce["_id"]);
                array_push($aAnnoncesData, ["annonce" => $aAnnonce, "picture" => $aAnnoncesPictureModel, "category" => $aCategoryModel["name"]]);
            }

            $this->data->set([
                "annonce" => [
                    "owner" => $bOwner,
                    "user" => $aUserModel,
                    "annonces" => $aAnnoncesData
                ]
            ]);

        } else {
            $this->on404();
        }

    }

    private function setAnnoncesModel(&$aAnnoncesModel, $aUserModel, $sMethod) {

        if (isset($this->query["sort"])) {
            switch ($this->query["sort"]) {
                case "categorie":
                    $aAnnoncesModel = $this->annoncesModel->$sMethod($aUserModel["_id"], "category_id ASC");
                    break;
                case "recent":
                    $aAnnoncesModel = $this->annoncesModel->$sMethod($aUserModel["_id"], "date DESC");
                    break;
                case "ancien";
                    $aAnnoncesModel = $this->annoncesModel->$sMethod($aUserModel["_id"], "date ASC");
                    break;
                case "prixcroissant":
                    $aAnnoncesModel = $this->annoncesModel->$sMethod($aUserModel["_id"], "price ASC");
                    break;
                case "prixdecroissant":
                    $aAnnoncesModel = $this->annoncesModel->$sMethod($aUserModel["_id"], "price DESC");
                    break;
                default:
                    $aAnnoncesModel = $this->annoncesModel->$sMethod($aUserModel["_id"], "date DESC");
            }
        } else {
            $aAnnoncesModel = $this->annoncesModel->$sMethod($aUserModel["_id"], "date DESC");
        }

    }

}