<?php 

class NumberFormat {

    private static function getFloat($sNum) {
        if (is_numeric($sNum)) {
            return floatval($sNum);
        }
        return null;
    }
    
    public static function format($sNum) {
        $nNum = self::getFloat($sNum);
        if ($nNum !== null) {
            return number_format($nNum, 2, ",", " ");
        }
        return "Ce nombre n'est pas valide";
    }

    public static function formatPhone($sNum) {
        return wordwrap($sNum, 2, " ", true);
    }

}