<?php

session_start();

if (!isset($_SESSION["initialized"])) {
    Session::initialize();
}

class Session {

    public static function initialize() {
        $_SESSION["initialized"] = true;
        $_SESSION["account"] = [
            "connected" => false,
            "user" => null
        ];
        $_SESSION["___tmp___"] = [];
    }

    public static function reset() {
        session_unset();
        self::initialize();
    }

    public static function get() {

        $aParams = func_get_args();
    
        if (count($aParams) === 0) {
            return $_SESSION;
        } else {
    
            $mFrom = $_SESSION;
    
            foreach ($aParams as $sParam) {

                if (gettype($mFrom) == "array") {
                    $mFrom = self::getFromArray($mFrom, $sParam);
                } else {
                    throw new Exception(__METHOD__, "trying to access in a non array variable");
                }

            }
    
            return is_string($mFrom) ? htmlspecialchars($mFrom) : $mFrom;
    
        }
    
    }

    private static function getFromArray($mFrom, $sParam) {
        if (isset($mFrom[$sParam])) {
            return $mFrom[$sParam];
        }
        throw new ControllerException(__METHOD__, "trying to access undefined array index:" . $sParam);
    }

    public static function userConnected() {
        return $_SESSION["account"]["connected"];
    }

    public static function userGet() {
        return $_SESSION["account"]["user"];
    }

    public static function userGetId() {
        if (isset($_SESSION["account"]["user"]["_id"])) {
            return $_SESSION["account"]["user"]["_id"]; 
        }
        return null;
    }

    public static function userGetPseudo() {
        if (isset($_SESSION["account"]["user"]["pseudo"])) {
            return $_SESSION["account"]["user"]["pseudo"]; 
        }
        return null;
    }

    public static function userGetRole() {
        if (isset($_SESSION["account"]["user"]["role"])) {
            return $_SESSION["account"]["user"]["role"]; 
        }
        return -1;
    }

    public static function userGetVerified() {
        if (isset($_SESSION["account"]["user"]["verified"])) {
            return $_SESSION["account"]["user"]["verified"]; 
        }
        return null;
    }

    public static function userSetVerified($bValue) {
        $_SESSION["account"]["user"]["verified"] = $bValue;
    }

    public static function userConnect($aUser) {
        $_SESSION["account"]["connected"] = true;
        $_SESSION["account"]["user"] = [
            "_id" => $aUser["_id"],
            "pseudo" => $aUser["pseudo"],
            "firstname" => $aUser["firstname"],
            "lastname" => $aUser["lastname"],
            "email" => $aUser["email"],
            "role" => (int) $aUser["role"],
            "verified" => (bool) $aUser["email_confirmation"]
        ];
    }

    public static function userDisconnect() {
        $_SESSION["account"]["connected"] = false;
        $_SESSION["account"]["user"] = null;
    }

    public static function ___tmp___setModal($sTitle, $sBody) {
        if (isset($_SESSION["___tmp___"]["modals"])) {
            array_push($_SESSION["___tmp___"]["modals"], ["title" => $sTitle, "body" => $sBody]);
        } else {
            $_SESSION["___tmp___"]["modals"] = [["title" => $sTitle, "body" => $sBody]];
        }
    }

    public static function ___tmp___getModal() {
        if (isset($_SESSION["___tmp___"]["modals"])) {
            return $_SESSION["___tmp___"]["modals"];
        }
        return null;
    }

}