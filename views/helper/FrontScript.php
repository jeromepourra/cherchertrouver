<?php

class FrontScript extends FrontData {

    public static function printScripts() {

        $aScripts = FrontData::$data->get("script");

        if (isset($aScripts)) {
            foreach ($aScripts as $sScript) {
                echo "<script src='" . $sScript . "'></script>";
            }
        }

    }

}