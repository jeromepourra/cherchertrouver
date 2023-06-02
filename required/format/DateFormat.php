<?php 

class DateFormat {

    private const DAY_NAMES = ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
    private const MONTH_NAMES = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aôut", "Septembre", "Octobre", "Novembre", "Décembre"];

    private static function getDateTime($sDate, $sFormat = "Y-m-d H:i:s", $sTimeZone = "UTC") {
        $oDateTime = DateTime::createFromFormat($sFormat, $sDate, new DateTimeZone($sTimeZone));
        if (!$oDateTime || $oDateTime->format($sFormat) ==! $sDate) {
            return null;
        }
        return $oDateTime;
    }

    public static function format($sDate, $sFromTimeZone = "UTC", $sToTimeZone = "Europe/Paris") {

        $oDateTime = self::getDateTime($sDate, "Y-m-d H:i:s", $sFromTimeZone);

        if ($oDateTime instanceof DateTime) {
            
            if ($sFromTimeZone != $sToTimeZone) {
                $oDateTime->setTimezone(new DateTimeZone("Europe/Paris"));
            }

            $sDayName = DateFormat::DAY_NAMES[$oDateTime->format("w")];
            $sDay = $oDateTime->format("d");
            $sMonthName = DateFormat::MONTH_NAMES[$oDateTime->format("n") - 1];
            $sYear = $oDateTime->format("Y");
            $sTime = $oDateTime->format("H:i:s");

            return $sDayName . " " . $sDay . " " . $sMonthName . " " . $sYear . " à " . $sTime;

        }

        return "Cette date n'est pas valide";

    }

    public static function formatSinceMonth($sDate, $sFromTimeZone = "UTC", $sToTimeZone = "Europe/Paris") {

        $oDateTime = self::getDateTime($sDate, "Y-m-d H:i:s", $sFromTimeZone);

        if ($oDateTime instanceof DateTime) {
            
            if ($sFromTimeZone != $sToTimeZone) {
                $oDateTime->setTimezone(new DateTimeZone("Europe/Paris"));
            }

            $sMonthName = DateFormat::MONTH_NAMES[$oDateTime->format("n") - 1];
            $sYear = $oDateTime->format("Y");

            return $sMonthName . " " . $sYear;

        }

        return "Cette date n'est pas valide";

    }

}