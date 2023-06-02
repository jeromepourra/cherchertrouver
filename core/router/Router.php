<?php

require ROOT . "/core/router/RouterException.php";
require ROOT . "/core/router/RouterDictionnary.php";

class Router extends RouterDictionnary {

    public static function run($sController, $aActions, $aQuery) {

        try {

            $sFile = self::getController($sController);
            $sFullPath = ROOT . "/controllers/" . $sFile . ".php";
            $aFullPathInfos = pathinfo($sFullPath);
            $sClass = "Controllers\\" . $aFullPathInfos["filename"];

            if (file_exists($sFullPath)) {

                require $sFullPath;
            
                if (class_exists($sClass)) {

                    $oController = new $sClass($aActions, $aQuery);

                    if (method_exists($oController, "run")) {
                        $oController->run();
                    } else {
                        throw new ControllerException(__METHOD__, "controller:" . $sClass . " method 'run()' not exists");
                    }

                } else {
                    throw new RouterException(__METHOD__, "class:" . $sClass . " not exists");
                }
            
            } else {
                throw new RouterException(__METHOD__, "file:" . $sFullPath . " not exists");
            }

        } catch (ControllerException $e) {
            if (Constants::DEBUG_MODE) {
                throw $e;
            } else {
                self::location(RouterDictionnary::buildURL());
            }
        } catch (RouterException $e) {
            if (Constants::DEBUG_MODE) {
                throw $e;
            } else {
                self::location(RouterDictionnary::buildURL());
            }
        }

    }

    public static function getReferer() {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $sUrl = $_SERVER['HTTP_REFERER'];
            if (str_starts_with($sUrl, WEB_ROOT)) {
                return $sUrl;
            }
        }
        return WEB_ROOT;
    }

    public static function location($sUrl = WEB_ROOT) {
        header("Location: " . $sUrl);
        die;
    }

}