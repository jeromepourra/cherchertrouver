<?php

class FrontForm extends FrontData {

    public static function printFormSuccess($sField) {
        $aMessages = self::$data->get("form", "response", "messages", "success", $sField);
        if (isset($aMessages)) {
            foreach ($aMessages as $sMessage) {
                if (!empty($sMessage)) {
                    echo "<p class='text-bg-success text-center rounded p-2'>" . $sMessage . "</p>";
                }
            }
        }
    }

    public static function printFormError($sField) {
        $aMessages = self::$data->get("form", "response", "messages", "errors", $sField);
        if (isset($aMessages)) {
            foreach ($aMessages as $sMessage) {
                if (!empty($sMessage)) {
                    echo "<p class='text-bg-danger text-center rounded p-2'>" . $sMessage . "</p>";
                }
            }
        }
    }

    public static function printFieldErrors($sField, $sTextAlign = "text-end") {
        $aMessages = self::$data->get("form", "response", "messages", "errors", $sField);
        if (isset($aMessages)) {
            foreach ($aMessages as $sMessage) {
                if (!empty($sMessage)) {
                    echo "<small class='d-block text-danger " . $sTextAlign . "'>" . $sMessage . "</small>";
                }
            }
        }
    }

    public static function printFieldClass($sField, $sFormKey = "form") {
        if (self::$data->get($sFormKey, "response", "messages") !== null) {
            $aErrors = self::$data->get("form", "response", "messages", "errors", $sField);
            if (!empty($aErrors)) {
                echo "is-invalid";
            }
        }
    }

    public static function putFieldValue($sField) {
        $sValue = self::$data->get("form", "response", "values", $sField);
        if (isset($sValue)) {
            echo $sValue;
        }
    }

    public static function putSelectFieldValue($sField, $sValue) {
        $sExpect = self::$data->get("form", "response", "values", $sField);
        if (isset($sExpect)) {
            if ($sValue == $sExpect) {
                echo "selected";
            }
        }
    }

    public static function putSelectFieldValueFromData($sExpect, $sValue) {
        if (isset($sExpect)) {
            if ($sValue == $sExpect) {
                echo "selected";
            }
        }
    }

}