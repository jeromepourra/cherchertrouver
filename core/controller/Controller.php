<?php

require ROOT . "/core/controller/ControllerData.php";
require ROOT . "/core/controller/ControllerException.php";

class Controller {

    protected $actions = [];
    protected $query = [];

    protected $minActions = 0;
    protected $maxActions = 0;
    protected $acceptedActions = [];
    
    protected $acceptedMethods = [];
    protected $requestedMethod = null;

    protected ControllerData $data;

    protected $controller = null;
    protected $controllerFull = null;

    private $layout;

    protected function initialize($bLayout = false) {

        $this->requestedMethod = $_SERVER["REQUEST_METHOD"];
        $this->data = new ControllerData();
        $this->controller = str_replace("Controllers\\", "", get_class($this));
        $this->controllerFull = get_class($this);
        $this->data->set([
            "controller" => [
                "name" => $this->controller,
                "actions" => $this->actions,
                "query" => $this->query
            ]
        ]);

        if (!$bLayout) {
            $this->loadLayout();
            if (!$this->isMethodAccepted()) {
                if (Constants::DEBUG_MODE) {
                    throw new ControllerException(__METHOD__, "request:" . $this->requestedMethod . " not accepted in controller:" . $this->controllerFull);
                } else {
                    $this->on404();
                }
            }
            if (!$this->isActionsAccepted()) {
                if (Constants::DEBUG_MODE) {
                    throw new ControllerException(__METHOD__, "an action is not accepted in controller:" . $this->controllerFull);
                } else {
                    $this->on404();
                }
            }
            if (!$this->isActionsRange()) {
                if (Constants::DEBUG_MODE) {
                    throw new ControllerException(__METHOD__, "number of action is not in range in controller:" . $this->controllerFull);
                } else {
                    $this->on404();
                }
            }
        }

    }

    private function isMethodAccepted() {
        return empty($this->acceptedMethods) || in_array($this->requestedMethod, $this->acceptedMethods);
    }

    private function isActionsAccepted() {
        $aAccepted = $this->acceptedActions;
        foreach ($this->actions as $sAction) {
            if (is_array($aAccepted)) {
                if (array_key_exists($sAction, $aAccepted)) {
                    $aAccepted = $this->getNextActionAccepted($aAccepted, $sAction);
                } else {
                    $bFound = false;
                    foreach (array_keys($aAccepted) as $sKey) {
                        if (str_starts_with($sKey, "/") && str_ends_with($sKey, "/")) {
                            if (preg_match($sKey, $sAction)) {
                                $aAccepted = $this->getNextActionAccepted($aAccepted, $sKey);
                                $bFound = true;
                                break;
                            }
                        }
                    }
                    if (!$bFound) {
                        if (array_key_exists("*", $aAccepted)) {
                            $aAccepted = $this->getNextActionAccepted($aAccepted, $sAction);
                        } else {
                            return false;
                        }
                    }
                }
            } else {
                return false;
            }
        }
        return true;
    }

    private function getNextActionAccepted($aArray, $sKey) {
        if (is_array($aArray[$sKey])) {
            return $aArray[$sKey];
        }
        return null;
    }

    private function isActionsRange() {
        $nActions = count($this->actions);
        return $nActions >= $this->minActions && $nActions <= $this->maxActions;
    }
    
    protected function render($sView) {

        $sFullPath = ROOT . "/views/" . $sView . ".php";

        if (file_exists($sFullPath)) {
            $___DATA_CONTROLLER___ = $this->data;
            $___LAYOUT_DATA_CONTROLLER___ = $this->layout->data;
            ob_start();
            require ROOT . "/views/helper/required.php";
            require $sFullPath;
            $___VIEW_CONTROLLER___ = ob_get_clean();
            require ROOT . "/views/" . RouterDictionnary::getView($this->layout->controller) . ".php";
        } else {
            throw new ControllerException(__METHOD__, "file:" . $sFullPath . " not exists");
        }

    }

    protected function loadModel($sModel) {
        return $this->loadModule("models", $sModel, "Models");
    }

    protected function loadForm($sForm) {
        return $this->loadModule("forms", $sForm, "Forms");
    }

    protected function loadLayout($sLayout = "Layout") {
        $oLayout = $this->loadModule("controllers", $sLayout, "Controllers");
        if (method_exists($oLayout, "run")) {
            $this->layout = $oLayout;
            $this->layout->run();
        } else {
            throw new ControllerException(__METHOD__, "controller:" . get_class($oLayout) . " method 'run()' not exists");
        }
    }

    private function loadModule($sDir, $sName, $sNamespace, $aConstruct = []) {
        try {
            $sClass = $this->requireModule($sDir, $sName, $sNamespace);
            return new $sClass(...$aConstruct);
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function requireModule($sDir, $sName, $sNamespace) {

        $sFullPath = ROOT . "/" . $sDir . "/" . $sName . ".php";
        $aFullPathInfos = pathinfo($sFullPath);
        $sClass = $sNamespace . "\\" . $aFullPathInfos["filename"];

        if (file_exists($sFullPath)) {

            require_once $sFullPath;

            if (class_exists($sClass)) {
                return $sClass;
            } else {
                throw new ControllerException(__METHOD__, "class:" . $sClass . " not exists");
            }
            

        } else {
            throw new ControllerException(__METHOD__, "file:" . $sFullPath . " not exists");
        }

    }

    protected function on404() {
        $this->loadLayout("Layout404");
        $this->render(RouterDictionnary::getView("NotFound"));
        die;
    }

}