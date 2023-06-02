<?php

class FrontData {

    public static ControllerData $data;
    public static ControllerData $layoutData;

    public static function initialize(ControllerData $oControllerData, ControllerData $oLayoutData) {
        self::$data = $oControllerData;
        self::$layoutData = $oLayoutData;
    }

    public static function safe($sData) {
        return is_string($sData) ? htmlspecialchars($sData) : $sData;
    }

}

FrontData::initialize($___DATA_CONTROLLER___, $___LAYOUT_DATA_CONTROLLER___);