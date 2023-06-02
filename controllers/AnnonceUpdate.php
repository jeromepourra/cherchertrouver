<?php

namespace Controllers;

use AnnonceState;
use Constants;
use Controller;
use FormFileManager;
use FormResponse;
use Forms\AnnonceUpdateForm;
use Models\Annonces;
use Models\AnnoncesPictures;
use Models\Categories;
use Router;
use RouterDictionnary;
use Session;

class AnnonceUpdate extends Controller {

    private Categories $categoriesModel;
    private Annonces $annonceModel;
    private AnnoncesPictures $annoncesPicturesModel;

    private AnnonceUpdateForm $annonceUpdateForm;

    public function __construct($aActions, $aQuery) {
        $this->actions = $aActions;
        $this->query = $aQuery;
        $this->minActions = 1;
        $this->maxActions = 3;
        $this->acceptedActions = [
            "/^[0-9]+$/" => [
                "retirer-image" => [
                    "/^[0-9]+$/" => null
                ]
            ]
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

        $this->categoriesModel = $this->loadModel("Categories");
        $this->annonceModel = $this->loadModel("Annonces");
        $this->annoncesPicturesModel = $this->loadModel("AnnoncesPictures");

        $sActionAnnonceId = $this->actions[0];
        $aAnnonceModel = $this->annonceModel->getFromId($sActionAnnonceId);

        if (!empty($aAnnonceModel)) {

            $sSessionUserId = Session::userGetId();

            if ($sSessionUserId == $aAnnonceModel["user_id"]) {

                switch (count($this->actions)) {
                    case 1:
                        break;
                    case 3:
                        $aResponse = $this->removePicture($aAnnonceModel);
                        break;
                    default:
                        $this->on404();
                }
                
                $aAnnoncesPicturesModel = $this->annoncesPicturesModel->getFromAnnonce($aAnnonceModel["_id"]);
                $aAnnonceData = [
                    "annonce" => $aAnnonceModel,
                    "pictures" => $aAnnoncesPicturesModel
                ];

                $this->data->set([
                    "head-title" => Constants::WEB_SITE_NAME . " - Modifier votre annonce",
                    "head-desc" => "Modifiez une annonce que vous avez recemment posté",
                    "page-title" => "Modifier votre annonce",
                    "annonce" => [
                        "categories" => $this->categoriesModel->getAll(),
                        "annonce" => $aAnnonceData
                    ]
                ]);
                $this->render(RouterDictionnary::getView($this->controller));

            } else {
                $this->on404();
            }

        } else {
            $this->on404();
        }
        
    }

    private function onPOST() {

        $this->categoriesModel = $this->loadModel("Categories");
        $this->annonceModel = $this->loadModel("Annonces");
        $this->annoncesPicturesModel = $this->loadModel("AnnoncesPictures");

        $this->annonceUpdateForm = $this->loadForm("AnnonceUpdateForm");

        $sActionAnnonceId = $this->actions[0];
        $aAnnonceModel = $this->annonceModel->getFromId($sActionAnnonceId);

        if (!empty($aAnnonceModel)) {

            $sSessionUserId = Session::userGetId();

            if ($sSessionUserId == $aAnnonceModel["user_id"]) {
                
                $bFormSuccess = $this->annonceUpdateForm->check($aAnnonceModel["_id"], $this->categoriesModel, $this->annonceModel, $this->annoncesPicturesModel);
                $aFormResponse = $this->annonceUpdateForm->getResponse();
                $aFormUpdateFields = $this->annonceUpdateForm->getUpdateFields();

                if ($bFormSuccess) {
                    $this->update($aFormUpdateFields, $aAnnonceModel);
                    $aAnnonceModel = $this->annonceModel->getFromId($sActionAnnonceId);
                    $aAnnoncesPicturesModel = $this->annoncesPicturesModel->getFromAnnonce($aAnnonceModel["_id"]);
                } else {
                    $aAnnoncesPicturesModel = $this->annoncesPicturesModel->getFromAnnonce($aAnnonceModel["_id"]);
                }

                $aAnnonceData = [
                    "annonce" => $aAnnonceModel,
                    "pictures" => $aAnnoncesPicturesModel
                ];

                $this->data->set([
                    "head-title" => Constants::WEB_SITE_NAME . " - Modifier votre annonce",
                    "head-desc" => "Modifiez une annonce que vous avez recemment posté",
                    "page-title" => "Modifier votre annonce",
                    "annonce" => [
                        "categories" => $this->categoriesModel->getAll(),
                        "annonce" => $aAnnonceData
                    ],
                    "form" => [
                        "response" => $aFormResponse
                    ]
                ]);
                $this->render(RouterDictionnary::getView($this->controller));

            } else {
                $this->on404();
            }

        } else {
            $this->on404();
        }

    }

    private function update($aFormUpdateFields, $aAnnonceModel) {

        $sAnnonceId = $aAnnonceModel["_id"];

        foreach ($aFormUpdateFields as $sField) {
            switch ($sField) {
                case "category":
                    $this->annonceModel->updateCategory($_POST["category"], $sAnnonceId);
                    break;
                case "title":
                    $this->annonceModel->updateTitle($_POST["title"], $sAnnonceId);
                    break;
                case "price":
                    $this->annonceModel->updatePrice($_POST["price"], $sAnnonceId);
                    break;
                case "description":
                    $this->annonceModel->updateDescription($_POST["description"], $sAnnonceId);
                    break;
                case "new-pictures":
                    $oUploader = new FormFileManager();
                    $oUploader->uploadAnnoncePictures("new-pictures", $sAnnonceId, $this->annoncesPicturesModel);
                    break;
            }
        }

        $this->annonceModel->updateState(AnnonceState::ANNONCE_STATE_PENDING, $sAnnonceId);

    }

    private function removePicture() {
        
        $oResponse = new FormResponse();
        $sActionAnnonceId = $this->actions[0];
        $sActionPictureId = $this->actions[2];
        $nPicturesCount = $this->annoncesPicturesModel->getCountFromAnnonce($sActionAnnonceId);
        $aAnnoncesPicturesModel = $this->annoncesPicturesModel->getFromAnnonceWhereId($sActionAnnonceId, $sActionPictureId);

        if ($nPicturesCount > 1) {
            if (!empty($aAnnoncesPicturesModel)) {
                $this->annoncesPicturesModel->removeFromId($aAnnoncesPicturesModel["_id"]);
                $oFormFileManager = new FormFileManager();
                $oFormFileManager->removeAnnoncePicture($sActionAnnonceId, $aAnnoncesPicturesModel["_id"] . "." . $aAnnoncesPicturesModel["extension"]);
            } else {
                $oResponse->pushError("pictures", "Cette image n'existe pas");
            }
        } else {
            $oResponse->pushError("pictures", "Ce champ doit contenir au moins 1 image");
        }

        $this->data->set([
            "form" => [
                "response" => $oResponse->get()
            ]
        ]);

    }

}