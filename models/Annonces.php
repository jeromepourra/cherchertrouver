<?php

namespace Models;

use AnnonceState;
use Model;
use ModelException;
use PDO;

class Annonces extends Model {

    public function __construct() {
        $this->connect();
        $this->setTable("annonces");
    }

    public function getAll() {
        return $this->read("*");
    }

    public function getFromId($sId) {
        return $this->read("*", "_id = :id", null, [":id" => $sId], "fetch");
    }

    public function getFromUser($sUserId, $sOrder = null) {
        return $this->read("*", "user_id = :user", $sOrder, [":user" => $sUserId]);
    }

    public function getOnlineFromUser($sUserId, $sOrder = null) {
        $nOnlineState = AnnonceState::ANNONCE_STATE_ONLINE;
        return $this->read("*", "user_id = :user AND state = :state", $sOrder, [":user" => $sUserId, ":state" => $nOnlineState]);
    }

    public function getFromUserWhereId($sId, $sUserId) {
        return $this->read("*", "_id = :id AND user_id = :user", null, [":id" => $sId, ":user" => $sUserId], "fetch");
    }

    public function getFromResearch($aResearch, $sOrder = null, $sLimit = null, $sOffset = null) {

        $aCondition = [];
        $aBind = [];

        foreach ($aResearch as $aData) {
            $sColumn = $aData["column"];
            $sName = $aData["name"];
            $sValue = $aData["value"];
            $sOperator = $aData["operator"];
            array_push($aCondition, $sColumn . " " . $sOperator . " " . ":" . $sName);
            $aBind += [":" . $sName => $sValue];
        }
        
        $sCondition = join(" AND ", $aCondition);
        
        // récupère le total de toutes les annonces
        $aCount = $this->readJoin("users", "users._id = annonces.user_id", "COUNT(*)", $sCondition, $sOrder, $aBind, "fetch", PDO::FETCH_NUM);
        // transforme la string retourné en un entier
        $nCount = (int) $aCount[0];

        return [
            "count" => $nCount,
            // récupère les annonces à afficher sur la page
            "annonces" => $this->readJoinPaginate("users", "users._id = annonces.user_id", "annonces.*", $sCondition, $sOrder, $sLimit, $sOffset, $aBind)
        ];

    }

    public function create($aValues) {

        $aColumns = ["user_id", "category_id", "title", "price", "description", "date"];

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

    public function updateCategory($sCategory, $sId) {
        $this->update("category_id = :category", "_id = :id", [":category" => $sCategory, ":id" => $sId]);
    }

    public function updateTitle($sTitle, $sId) {
        $this->update("title = :title", "_id = :id", [":title" => $sTitle, ":id" => $sId]);
    }

    public function updatePrice($sPrice, $sId) {
        $this->update("price = :price", "_id = :id", [":price" => $sPrice, ":id" => $sId]);
    }

    public function updateDescription($sDescription, $sId) {
        $this->update("description = :description", "_id = :id", [":description" => $sDescription, ":id" => $sId]);
    }

    public function updateState($sState, $sId) {
        $this->update("state = :state", "_id = :id", [":state" => $sState, ":id" => $sId]);
    }

    public function remove($sId) {
        $this->delete("_id = :id", [":id" => $sId]);
    }

}