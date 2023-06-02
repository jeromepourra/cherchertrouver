<?php

const DOCUMENT_ROOT = "/dispatch.php";
define("WEB_ROOT", $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"]);
define("ROOT", str_replace(DOCUMENT_ROOT, "", $_SERVER["SCRIPT_FILENAME"]));

require ROOT . "/ini.php";
require ROOT . "/required/required.php";

require ROOT . "/core/CustomException.php";
require ROOT . "/core/form/Form.php";
require ROOT . "/core/model/Model.php";
require ROOT . "/core/router/Router.php";
require ROOT . "/core/controller/Controller.php";

// var_dump($_SERVER);

$sParams = $_GET["url"];
unset($_GET["url"]);

$sController = null;
$aActions = [];
$aQuery = [];

if (isset($sParams)) {

    if ($sParams === "dev.php") {
        require ROOT . "/dev.php";
        die;
    } elseif ($sParams === "infos.php") {
        require ROOT . "/infos.php";
        die;
    } else {
        if (!empty($sParams)) {
            $aParams = explode("/", $sParams);
            $sController = array_shift($aParams);
            $aActions = $aParams;
            $aQuery = $_GET;
        }
    }

}

Router::run($sController, $aActions, $aQuery);