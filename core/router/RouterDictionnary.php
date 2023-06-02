<?php

class RouterDictionnary {

    private const CONTROLLERS = [
        "default"                   => "Index",

        "connexion"                 => "Signin",
        "inscription"               => "Signup",
        "deconnexion"               => "Signout",
        "mon-compte"                => "MyAccount",
        "confirmation-email"        => "EmailConf",
        "veuillez-confirmer-email"  => "EmailConfNeeded",
        
        "rechercher"                => "Search",
        "annonce"                   => "Annonce",
        "deposer-annonce"           => "AnnoncePost",
        "annonce-utilisateur"       => "AnnonceUser",
        "modifier-annonce"          => "AnnonceUpdate",
        "retirer-annonce"           => "AnnonceDelete",

        "noter-utilisateur"         => "RateUser",
        
        "conversation"              => "Conversation",
        "mes-conversations"         => "MyConversations",
        "envoyer-message"           => "ConversationCreate",

        "gestion-annonces"          => "ManageAnnonces",
        "gestion-utilisateurs"      => "ManageUsers",

        "rickroll"                  => "Rickroll",
        "contact"                   => "Contact",
        "qui-sommes-nous"           => "WhoAreWe",
        "mentions-legales"          => "LegalNotice",
        "donnees-personnelles"      => "PersonalData",
    ];

    private const VIEWS = [
        "Layout"                    => "parts/layout",
        "Layout404"                 => "parts/layout404",

        "Index"                     => "pages/index/index",

        "Signin"                    => "pages/account/connexion",
        "Signup"                    => "pages/account/inscription",
        "Signout"                   => "pages/account/deconnexion",
        "MyAccount"                 => "pages/account/mon-compte",

        "EmailConf"                 => "pages/account/confirmation-email",
        "EmailConfNeeded"           => "pages/account/veuillez-confirmer-email",

        "Search"                    => "pages/annonce/rechercher",
        "Annonce"                   => "pages/annonce/annonce",
        "AnnoncePost"               => "pages/annonce/deposer-annonce",
        "AnnonceUser"               => "pages/annonce/annonce-utilisateur",
        "AnnonceUpdate"             => "pages/annonce/modifier-annonce",
        
        "Conversation"              => "pages/message/conversation",
        "MyConversations"           => "pages/message/mes-conversations",
        "ConversationCreate"        => "pages/message/envoyer-message",

        "ManageAnnonces"            => "pages/management/gestion-annonces",
        "ManageUsers"               => "pages/management/gestion-utilisateurs",

        "Rickroll"                  => "pages/footer/rickroll",
        "Contact"                   => "pages/footer/contact",
        "WhoAreWe"                  => "pages/footer/qui-sommes-nous",
        "LegalNotice"               => "pages/footer/legal/mentions-legales",
        "PersonalData"              => "pages/footer/legal/donnees-personnelles",

        "NotFound"                  => "pages/error/404",
    ];

    public static function getURL($sController = null) {
        try {
            $sPage = self::getPage($sController);
            return WEB_ROOT . "/" . $sPage;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function buildURL($sController = null, $aActions = [], $aQuery = []) {
        try {
            $sPage = self::getPage($sController);
            $sActions = count($aActions) > 0 ? "/" . join("/", $aActions) : "";
            $sQuery = count($aQuery) > 0 ? "?" . http_build_query($aQuery, "", "&") : "";
            return WEB_ROOT . "/" . $sPage . $sActions . $sQuery;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function getPage($sController) {

        if ($sController === null || empty($sController)) {
            return "";
        }

        $sPage = array_search($sController, self::CONTROLLERS, true);

        if ($sPage === false) {
            throw new RouterException(__METHOD__, "page:" . $sController . " not found");
        } else {
            return $sPage;
        }

    }

    public static function getController($sPage) {

        if ($sPage === null || empty($sPage)) {
            $sPage = "default";
        }

        if (array_key_exists($sPage, self::CONTROLLERS)) {
            return self::CONTROLLERS[$sPage];
        } else {
            throw new RouterException(__METHOD__, "route:" . $sPage . " not found");
        }

    }

    public static function getView($sController) {

        if ($sController === null || empty($sController)) {
            $sController = "Index";
        }

        if (array_key_exists($sController, self::VIEWS)) {
            return self::VIEWS[$sController];
        } else {
            throw new RouterException(__METHOD__, "view:" . $sController . " not found");
        }

    }

}