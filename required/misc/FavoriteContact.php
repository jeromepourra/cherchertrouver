<?php

class FavoriteContact {

    public static function getName($sFavori) {
        $nFavori = (int) $sFavori;
        if (array_key_exists($nFavori, Constants::CONTACTS_FAVORI)) {
            return Constants::CONTACTS_FAVORI[$nFavori];
        }
        return null;
    }

    public static function isFavorite($sFavori, $sAttempt) {
        return self::getName($sFavori) == $sAttempt;
    }

}