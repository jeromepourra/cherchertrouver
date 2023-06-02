<?php

namespace Models;

use Model;
use ModelException;
use PDO;

class AnnoncesPictures extends Model {

    public function __construct() {
        $this->connect();
        $this->setTable("annonces_pictures");
    }

    public function getFromId($sId) {
        return $this->read("*", "_id = :id", null, [":id" => $sId], "fetch");
    }

    public function getFromAnnonce($sAnnonceId) {
        return $this->read("*", "annonce_id = :id", null, [":id" => $sAnnonceId]);
    }

    public function getFromAnnonceWhereId($sAnnonceId, $sId) {
        return $this->read("*", "annonce_id = :annonce AND _id = :id", null, [":annonce" => $sAnnonceId, ":id" => $sId], "fetch");
    }

    public function getOneFromAnnonce($sAnnonceId) {
        return $this->read("*", "annonce_id = :id", null, [":id" => $sAnnonceId], "fetch");
    }

    public function getCountFromAnnonce($sAnnonceId) {
        $aResults = $this->read("COUNT(*)", "annonce_id = :id", null, [":id" => $sAnnonceId], "fetch", PDO::FETCH_NUM);
        return (int) $aResults[0];
    }

    public function idExist($sId) {
        return !empty($this->read("1", "_id = :id", null, [":id" => $sId], "fetch"));
    }

    public function create($aValues) {

        $aColumns = ["annonce_id", "extension"];

        if (count($aColumns) === count($aValues)) {

            $sColumns = join(",", $aColumns);
            $sValues = ":" . join(",:", $aColumns);
            $aBind = [];

            foreach ($aColumns as $nIndex => $sColumn) {
                $aBind += [":" . $sColumn => $aValues[$nIndex]];
            }
    
            $this->insert($sColumns, $sValues, $aBind);
            return $this->getLastInsert();

        } else {
            throw new ModelException(__METHOD__, "number of columns does not match number of values");
        }

    }

    public function removeFromId($sId) {
        $this->delete("_id = :id", [":id" => $sId]);
    }

}