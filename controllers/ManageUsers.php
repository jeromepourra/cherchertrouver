<?php

namespace Controllers;

use Constants;
use Controller;
use Forms\ManageBanishForm;
use Forms\ManageRoleForm;
use Forms\SearchUsersForm;
use Models\Banishments;
use Models\Users;
use Router;
use RouterDictionnary;
use Session;

class ManageUsers extends Controller {

    private Users $usersModel;
    private Banishments $banishmentsModel;

    private ManageBanishForm $banishForm;
    private ManageRoleForm $roleForm;
    private SearchUsersForm $searchUsersForm;

    public function __construct($aActions, $aQuery) {
        $this->actions = $aActions;
        $this->query = $aQuery;
        $this->minActions = 0;
        $this->maxActions = 2;
        $this->acceptedActions = [
            "page" => [
                "/^[0-9]+$/" => null
            ],
            "role" => [
                "/^[0-9]+$/" => [
                    "/^[0-9]+$/"
                ]
            ],
            "bannir" => [
                "/^[0-9]+$/" => null
            ],
            null
        ];
        $this->acceptedMethods = ["GET", "POST"];
        $this->initialize();
    }

    public function run() {

        if (!Session::userConnected()) {
            Router::location(RouterDictionnary::buildURL("Signin"));
        }

        if (Session::userGetRole() < 1) {
            $this->on404();
        }

        $sFunc = "on" . $this->requestedMethod;
        $this->$sFunc();
        
    }

    private function onGET() {

        $this->usersModel = $this->loadModel("Users");
        $aUsers = $this->usersModel->getAll();

        if (!empty($this->actions)) {

            switch ($this->actions[0]) {
                case "role":
                    $this->on404();
                    break;
                case "bannir":
                    $this->on404();
                    break;
                default:
                    $this->searchUsersForm = $this->loadForm("SearchUsersForm");
                    $bFormSuccess = $this->searchUsersForm->check();
                    $aFormResponse = $this->searchUsersForm->getResponse();
                    if ($bFormSuccess) {
                        $aResearch = $this->getResearch();
                        $aResults = $this->getResults($aResearch);
                        $this->setPageData();
                        $this->setResultsData($aResults["count"]);
                        $this->setUsersData($aResults["users"]);
                    }
                    $this->data->set([
                        "form" => [
                            "response" => $aFormResponse
                        ]
                    ]);
                    break;
            }

        }

        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - " . Constants::USER_ROLES[Session::userGetRole()]["menu-name"],
            "page-title" => Constants::USER_ROLES[Session::userGetRole()]["menu-name"],
            "script" => [
                WEB_ROOT . "/views/public/js/no-empty-get.js"
            ]
        ]);
        $this->render(RouterDictionnary::getView($this->controller));

    }

    private function onPOST() {

        $this->usersModel = $this->loadModel("Users");

        if (!empty($this->actions)) {

            switch ($this->actions[0]) {
                case "role":
                    $aUser = $this->usersModel->getFromId($this->actions[1]);
                    if (!empty($aUser) && Session::userGetId() != $this->actions[1] && Session::userGetRole() > $aUser["role"] && !$aUser["banned"]) {
                        $this->roleForm = $this->loadForm("ManageRoleForm");
                        $bFormSuccess = $this->roleForm->check($aUser);
                        $aFormResponse = $this->roleForm->getResponse();
                        if ($bFormSuccess) {
                            $this->usersModel->updateRole($_POST["role-" . $_POST["form-id"]], $aUser["_id"]);
                            Session::___tmp___setModal("Rôle", "L'utilisateur " . $aUser["pseudo"] . " a changé de rôle");
                            // Router::location(Router::location(Router::getReferer()) . "#" . $aUser["_id"]);
                        }
                        $this->data->set([
                            "form" => [
                                "response" => $aFormResponse
                            ],
                        ]);
                    } else {
                        // $this->on404();
                    }
                    break;
                case "bannir":
                    $aUser = $this->usersModel->getFromId($this->actions[1]);
                    if (!empty($aUser) && Session::userGetId() != $this->actions[1] && Session::userGetRole() > $aUser["role"] && !$aUser["banned"]) {
                        $this->banishmentsModel = $this->loadModel("Banishments");
                        $this->banishForm = $this->loadForm("ManageBanishForm");
                        $bFormSuccess = $this->banishForm->check();
                        $aFormResponse = $this->banishForm->getResponse();
                        if ($bFormSuccess) {
                            $this->usersModel->updateBanned(true, $aUser["_id"]);
                            $this->banishmentsModel->create([
                                $aUser["_id"],
                                $_POST["reason-" . $_POST["form-id"]],
                                date("Y-m-d H:i:s", time())
                            ]);
                            Session::___tmp___setModal("Bannissement", "L'utilisateur " . $aUser["pseudo"] . " a été banni");
                            // Router::location(Router::location(Router::getReferer()) . "#" . $aUser["_id"]);
                        }
                        $this->data->set([
                            "form" => [
                                "response" => $aFormResponse
                            ],
                        ]);
                    } else {
                        $this->on404();
                    }
                    break;
                default:
                    $this->on404();
                    break;
            }

        }

        $aUsers = $this->usersModel->getAll();
        $this->data->set([
            "head-title" => Constants::WEB_SITE_NAME . " - " . Constants::USER_ROLES[Session::userGetRole()]["menu-name"],
            "page-title" => Constants::USER_ROLES[Session::userGetRole()]["menu-name"],
            "script" => [
                WEB_ROOT . "/views/public/js/no-empty-get.js"
            ],
            "user" => [
                "users" => $aUsers
            ]
        ]);
        $this->render(RouterDictionnary::getView($this->controller));

    }

    private function getResearch() {

        $aResearch = [];

        if (isset($this->query["role"])) {
            if ($this->query["role"] != "tous") {
                array_push($aResearch, [
                    "column" => "role",
                    "name" => "role",
                    "value" => $this->query["role"],
                    "operator" => "="
                ]);
            }
        }

        if (isset($this->query["key-word"])) {
            array_push($aResearch, [
                "column" => "pseudo",
                "name" => "keyword",
                "value" => "%" . $this->query["key-word"] . "%",
                "operator" => "LIKE"
            ]);
        }

        if (isset($this->query["state"])) {
            array_push($aResearch, [
                "column" => "banned",
                "name" => "state",
                "value" => $this->query["state"],
                "operator" => "="
            ]);
        }

        return $aResearch;

    }

    private function getResults($aResearch) {

        $nActionPage = (int) $this->actions[1];

        if ($nActionPage < 1) {
            $this->on404();
        }

        $nLimit = Constants::USER_MAX_PER_PAGE;
        $nOffset = Constants::USER_MAX_PER_PAGE * ($nActionPage - 1);

        if (isset($this->query["sort"])) {
            switch ($this->query["sort"]) {
                case "recent":
                    $aResults = $this->usersModel->getFromResearch($aResearch, "inscription_date DESC", $nLimit, $nOffset);
                    break;
                case "ancien";
                    $aResults = $this->usersModel->getFromResearch($aResearch, "inscription_date ASC", $nLimit, $nOffset);
                    break;
                case "pseudocroissant":
                    $aResults = $this->usersModel->getFromResearch($aResearch, "pseudo ASC", $nLimit, $nOffset);
                    break;
                case "pseudodecroissant":
                    $aResults = $this->usersModel->getFromResearch($aResearch, "pseudo DESC", $nLimit, $nOffset);
                    break;
                default:
                    $aResults = $this->usersModel->getFromResearch($aResearch, "inscription_date DESC", $nLimit, $nOffset);
            }
        } else {
            $aResults = $this->usersModel->getFromResearch($aResearch, "inscription_date DESC", $nLimit, $nOffset);
        }

        return $aResults;

    }

    private function setPageData() {
        $this->data->set([
            "user" => [
                "page" => (int) $this->actions[1]
            ]
        ]);
    }

    private function setResultsData($nCount) {

        $nTotResults = $nCount;
        $nTotPages = (int) ceil($nTotResults / Constants::USER_MAX_PER_PAGE);
        $nTotPages = $nTotPages < 1 ? 1 : $nTotPages;

        $this->data->set([
            "user" => [
                "total-users" => $nTotResults,
                "total-pages" => $nTotPages,
            ]
        ]);
    }

    private function setUsersData($aUsers) {
        $this->data->set([
            "user" => [
                "users" => $aUsers
            ]
        ]);
    }

}