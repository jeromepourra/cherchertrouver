<?php

class AnnonceState {

    public const ANNONCE_STATE_PENDING = 0;
    public const ANNONCE_STATE_REFUSED = 1;
    public const ANNONCE_STATE_REMOVED = 2;
    public const ANNONCE_STATE_ONLINE = 3;
    
    public const ANNONCE_STATES = [
        self::ANNONCE_STATE_PENDING => "En attente de validation",
        self::ANNONCE_STATE_REFUSED => "Validation refusée",
        self::ANNONCE_STATE_REMOVED => "Supprimée",
        self::ANNONCE_STATE_ONLINE  => "En ligne"
    ];

    public static function isStateOnline($sState) {
        return $sState == self::ANNONCE_STATE_ONLINE;
    }

    public static function printState($sState) {

        $nState = (int) $sState;
        $sState = self::ANNONCE_STATES[$nState];
        $sStateColor = null;

        switch ($nState) {
            case self::ANNONCE_STATE_ONLINE:
                $sStateColor = "success";
                break;
            case self::ANNONCE_STATE_PENDING:
                $sStateColor = "warning";
                break;
            case self::ANNONCE_STATE_REFUSED:
                $sStateColor = "danger";
                break;
            case self::ANNONCE_STATE_REMOVED:
                $sStateColor = "danger";
                break;
        }

        echo "<small class='d-inline-block fw-bold rounded px-2 py-1 mb-1 text-bg-" . $sStateColor . "'>" . $sState . "</small>";

    }

}