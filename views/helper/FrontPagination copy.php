<?php

class FrontPagination {

    public static function printSearchPagination() {

        $nPage = FrontData::$data->get("annonce", "page");
        $nTotPage = FrontData::$data->get("annonce", "total-pages");

        if (isset($nPage) && isset($nTotPage) && $nPage > 0) {
            $nPage = $nPage < 1 ? 1 : $nPage;
            $nPage = $nPage > $nTotPage ? $nTotPage : $nPage;
    
            echo "
                <li class='page-item " . self::printPreviousActive($nPage) . "'>
                    <a class='page-link' href='" . self::printSearchUrl($nPage - 1) . "'>
                        <i class='fa-solid fa-chevron-left'></i>
                    </a>
                </li>
            ";
    
            self::printPages($nPage, $nTotPage, 5, "printSearchUrl");
    
            echo "
                <li class='page-item " . self::printNextActive($nPage, $nTotPage) . "'>
                    <a class='page-link' href='" . self::printSearchUrl($nPage + 1) . "'>
                        <i class='fa-solid fa-chevron-right'></i>
                    </a>
                </li>
            ";
        }
        
    }

    public static function printSearchUrl($nPage) {
        $sController = FrontData::$data->get("controller", "name");
        $aActions = FrontData::$data->get("controller", "actions");
        $aQuery = FrontData::$data->get("controller", "query");
        $aActions[count($aActions) - 1] = $nPage;
        return Router::buildURL($sController, $aActions, $aQuery);
    }

    public static function printPreviousActive($nPage) {
        $nPage = FrontData::$data->get("annonce", "page");
        if ($nPage <= 1) {
            return "disabled";
        }
    }

    public static function printCurrentActive($nPage, $nCurrent) {
        return $nPage == $nCurrent ? "active" : "";
    }

    public static function printNextActive($nPage, $nTotPage) {
        $nPage = FrontData::$data->get("annonce", "page");
        $nTotPage = FrontData::$data->get("annonce", "total-pages");
        if ($nPage >= $nTotPage) {
            return "disabled";
        }
    }

    public static function printPages($nPage, $nTotPage, $nMaxDisplay, $sUrlFunc) {

        $nHalfMaxDisplay = ceil(($nMaxDisplay - 1) / 2);

        for ($i=$nHalfMaxDisplay; $i>=1; $i--) {
            if ($nPage - $i > 0) {
                echo "
                    <li class='page-item'>
                        <a class='page-link' href='" . self::$sUrlFunc($nPage - $i) . "'>" . $nPage - $i . "</a>
                    </li>
                ";
            }
        }

        echo "
            <li class='page-item active'>
                <a class='page-link' href='" . self::$sUrlFunc($nPage) . "'>" . $nPage . "</a>
            </li>
        ";

        for ($i=1; $i<=$nHalfMaxDisplay; $i++) {
            if ($nPage + $i <= $nTotPage) {
                echo "
                    <li class='page-item'>
                        <a class='page-link' href='" . self::$sUrlFunc($nPage + $i) . "'>" . $nPage + $i . "</a>
                    </li>
                ";
            }
        }
    }

}