<?php

namespace Controllers;

use Constants;
use Controller;
use FormFileManager;
use Forms\AnnoncePostForm;
use Models\Annonces;
use Models\AnnoncesPictures;
use Models\Categories;
use Router;
use RouterDictionnary;
use Session;

class AnnoncePost extends Controller {

    private Categories $categoriesModel;
    private Annonces $annoncesModel;
    private AnnoncesPictures $annoncesPicturesModel;

    private AnnoncePostForm $annoncePostForm;

    public function __construct($aActions, $aQuery) {
        $this->actions = $aActions;
        $this->query = $aQuery;
        $this->minActions = 0;
        $this->maxActions = 0;
        $this->acceptedActions = [null];
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

        $this->categoriesModel = $this->loadModel("Categories");

        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - Déposer une annonce",
            "head-desc" => "Déposez une nouvelle annonce de matériel informatique sur notre site",
            "page-title" => "Déposer une annonce",
            "annonce" => [
                "categories" => $this->categoriesModel->getAll()
            ]
        ]);
        $this->render(RouterDictionnary::getView($this->controller));

    }

    private function onPOST() {

        $sUserId = Session::userGetId();
        
        $this->categoriesModel = $this->loadModel("Categories");
        $this->annoncesModel = $this->loadModel("Annonces");
        $this->annoncesPicturesModel = $this->loadModel("AnnoncesPictures");

        $this->annoncePostForm = $this->loadForm("AnnoncePostForm");
        $bFormSuccess = $this->annoncePostForm->check($this->categoriesModel);
        $aFormResponse = $this->annoncePostForm->getResponse();

        if ($bFormSuccess) {

            $aAnnonce = $this->annoncesModel->create([
                $sUserId,
                $_POST["category"],
                $_POST["title"],
                $_POST["price"],
                $_POST["description"],
                date("Y-m-d H:i:s", time())
            ]);

            $oUploader = new FormFileManager();
            $oUploader->uploadAnnoncePictures("pictures", $aAnnonce["_id"], $this->annoncesPicturesModel);
            Session::___tmp___setModal("Annonce", "Votre annonce a bien été enregistrée, et est actuellement en attente de validation par nos équipes. <br> Vous pouvez consulter l'état de vos annonces <a href='" . RouterDictionnary::getURL("AnnonceUser") . "/" . Session::userGetId() . "'>ici</a>.");
            Router::location(RouterDictionnary::getURL($this->controller));
            
        }

        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - Déposer une annonce",
            "head-desc" => "Déposez une nouvelle annonce de matériel informatique sur notre site",
            "page-title" => "Déposer une annonce",
            "form" => [
                "response" => $aFormResponse
            ],
            "annonce" => [
                "categories" => $this->categoriesModel->getAll()
            ]
        ]);
        $this->render(RouterDictionnary::getView($this->controller));

    }

}