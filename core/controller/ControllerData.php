<?php

class ControllerData
{

    private $data = [];

    public function set(array $aData) {
        $this->data = array_merge_recursive($this->data, $aData);
    }

    public function get() {

        $aParams = func_get_args();

        if (count($aParams) === 0) {
            return $this->data;
        } else {
            $aParams = func_get_args();
            array_unshift($aParams, $this->data);
            return call_user_func_array([$this, "getFrom"], $aParams);
        }
    }

    public function getFrom() {

        $aParams = func_get_args();
        $mFrom = array_shift($aParams);

        if (count($aParams) === 0) {
            return $mFrom;
        } else {

            foreach ($aParams as $sParam) {
                switch (gettype($mFrom)) {
                    case "array":
                        $mFrom = $this->getFromArray($mFrom, $sParam);
                        break;
                    case "object":
                        $mFrom = $this->getFromObject($mFrom, $sParam);
                        break;
                    default:
                        return null;
                }
            }

            return $mFrom;
        }
    }

    private function getFromArray($mFrom, $sParam) {
        if (isset($mFrom[$sParam])) {
            return $mFrom[$sParam];
        }
        return null;
    }

    private function getFromObject($mFrom, $sParam) {
        if (property_exists($mFrom, $sParam)) {
            return $this->getFromObjectProperty($mFrom, $sParam);
        } elseif (method_exists($mFrom, $sParam)) {
            return $this->getFromObjectMethod($mFrom, $sParam);
        }
        return null;
    }

    private function getFromObjectProperty($mFrom, $sProperty) {
        return $mFrom->$sProperty;
    }

    private function getFromObjectMethod($mFrom, $sMethod) {
        return $mFrom->$sMethod();
    }
}
