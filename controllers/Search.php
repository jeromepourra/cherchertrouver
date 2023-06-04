<?php

namespace Controllers;

use AnnonceState;
use Constants;
use Controller;
use Forms\SearchForm;
use Models\Annonces;
use Models\AnnoncesPictures;
use Models\Categories;
use Models\Users;
use RouterDictionnary;

class Search extends Controller {

    private Users $userModel;
    private Categories $categoriesModel;
    private Annonces $annoncesModel;
    private AnnoncesPictures $annoncesPicturesModel;
    private SearchForm $searchForm;

    public function __construct($aActions, $aQuery) {
        $this->actions = $aActions;
        $this->query = $aQuery;
        $this->minActions = 2;
        $this->maxActions = 2;
        $this->acceptedActions = [
            "page" => [
                "/^[0-9]+$/" => null
            ]
        ];
        $this->acceptedMethods = ["GET"];
        $this->initialize();
    }

    public function run() {
        $sFunc = "on" . $this->requestedMethod;
        $this->$sFunc();
    }

    private function onGET() {
        $this->userModel = $this->loadModel("Users");
        $this->categoriesModel = $this->loadModel("Categories");
        $this->annoncesModel = $this->loadModel("Annonces");
        $this->annoncesPicturesModel = $this->loadModel("AnnoncesPictures");
        $this->searchForm = $this->loadForm("SearchForm");
        $bFormSuccess = $this->searchForm->check($this->categoriesModel);
        $aFormResponse = $this->searchForm->getResponse();

        if ($bFormSuccess) {
            $aResearch = $this->getResearch();
            $aResults = $this->getResults($aResearch);
            $this->setPageData();
            $this->setResultsData($aResults["count"]);
            $this->setAnnoncesData($aResults["annonces"]);
        }

        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - Recherche",
            "head-desc" => "Recherchez une annonce de matériel informatique parmis nos différente catégorie, par ordre de prix, triez les résultat",
            "page-title" => "Recherche",
            "script" => [
                WEB_ROOT . "/views/public/js/no-empty-get.js"
            ],
            "annonce" => [
                "categories" => $this->categoriesModel->getAll()
            ],
            "form" => [
                "response" => $aFormResponse
            ]
        ]);
        $this->render(RouterDictionnary::getView($this->controller));
    }

    private function getResearch() {

        $aResearch = [];

        array_push($aResearch, [
            "column" => "state",
            "name" => "state",
            "value" => AnnonceState::ANNONCE_STATE_ONLINE,
            "operator" => "="
        ]);

        array_push($aResearch, [
            "column" => "banned",
            "name" => "banned",
            "value" => "0",
            "operator" => "="
        ]);

        if (isset($this->query["category"])) {
            if ($this->query["category"] != 0) {
                array_push($aResearch, [
                    "column" => "category_id",
                    "name" => "category",
                    "value" => $this->query["category"],
                    "operator" => "="
                ]);
            }
        }

        if (isset($this->query["price-min"])) {
            array_push($aResearch, [
                "column" => "price",
                "name" => "pricemin",
                "value" => $this->query["price-min"],
                "operator" => ">="
            ]);
        }

        if (isset($this->query["price-max"])) {
            array_push($aResearch, [
                "column" => "price",
                "name" => "pricemax",
                "value" => $this->query["price-max"],
                "operator" => "<="
            ]);
        }

        if (isset($this->query["key-word"])) {
            array_push($aResearch, [
                "column" => "title",
                "name" => "keyword",
                "value" => "%" . $this->query["key-word"] . "%",
                "operator" => "LIKE"
            ]);
        }

        return $aResearch;

    }

    private function getResults($aResearch) {

        $nActionPage = (int) $this->actions[1];

        if ($nActionPage < 1) {
            $this->on404();
        }

        // défini le nombre de résultat max de la requête
        $nLimit = Constants::ANNONCE_MAX_PER_PAGE;
        // défini le décalage des résultat
        $nOffset = Constants::ANNONCE_MAX_PER_PAGE * ($nActionPage - 1);

        // si l'option de tri est défini, alors
        if (isset($this->query["sort"])) {
            switch ($this->query["sort"]) {
                case "categorie":
                    // récupère le résultat de la requête
                    // $aResearch = le tableau de condition des champs du formulaire
                    // category_id ASC = redéfini l'ordre des résultats obtenu
                    $aResults = $this->annoncesModel->getFromResearch($aResearch, "category_id ASC", $nLimit, $nOffset);
                    break;
                case "recent":
                    $aResults = $this->annoncesModel->getFromResearch($aResearch, "date DESC", $nLimit, $nOffset);
                    break;
                case "ancien";
                    $aResults = $this->annoncesModel->getFromResearch($aResearch, "date ASC", $nLimit, $nOffset);
                    break;
                    // etc...
                case "prixcroissant":
                    $aResults = $this->annoncesModel->getFromResearch($aResearch, "price ASC", $nLimit, $nOffset);
                    break;
                case "prixdecroissant":
                    $aResults = $this->annoncesModel->getFromResearch($aResearch, "price DESC", $nLimit, $nOffset);
                    break;
                default:
                    $aResults = $this->annoncesModel->getFromResearch($aResearch, "date DESC", $nLimit, $nOffset);
            }
        } else {
            $aResults = $this->annoncesModel->getFromResearch($aResearch, "date DESC", $nLimit, $nOffset);
        }

        return $aResults;

    }

    private function setPageData() {
        $this->data->set([
            "annonce" => [
                "page" => (int) $this->actions[1]
            ]
        ]);
    }

    private function setResultsData($nCount) {

        // total de toutes les annonces
        $nTotResults = $nCount;
        // total de toutes les pages
        $nTotPages = (int) ceil($nTotResults / Constants::ANNONCE_MAX_PER_PAGE);
        // si total aucune annonces n'est trouvé alors le total des page est égal à 0
        // définir à 1 par defaut
        $nTotPages = $nTotPages < 1 ? 1 : $nTotPages;

        // prépare les données qui seront envoyé à la view
        $this->data->set([
            "annonce" => [
                "total-annonces" => $nTotResults,
                "total-pages" => $nTotPages,
            ]
        ]);
    }

    private function setAnnoncesData($aAnnonces) {

        // défini un tableau vide qui sera envoyé à la view
        $aAnnoncesData = [];

        foreach ($aAnnonces as $aAnnonce) {
            // recupère le propriétaire de l'annonce
            $aUser = $this->userModel->getFromId($aAnnonce["user_id"]);
            // recupère la categorie de l'annonce
            $aCategoryModel = $this->categoriesModel->getFromId($aAnnonce["category_id"]);
            // recupère la première image de l'annonce
            $aAnnoncesPictureModel = $this->annoncesPicturesModel->getOneFromAnnonce($aAnnonce["_id"]);
            // ajoute toutes les données de l'annonce dans le tableau qui sera envoyé à la view
            array_push($aAnnoncesData, ["user" => $aUser, "annonce" => $aAnnonce, "picture" => $aAnnoncesPictureModel, "category" => $aCategoryModel["name"]]);
        }

        // prépare les données des annonces qui seront envoyé à la view
        $this->data->set([
            "annonce" => [
                "annonces" => $aAnnoncesData
            ]
        ]);

    }

}