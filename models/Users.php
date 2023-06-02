<?php

namespace Models;

use Model;
use ModelException;
use PDO;

class Users extends Model {

    public function __construct() {
        $this->connect();
        $this->setTable("users");
    }

    public function pseudoExists($sPseudo) {
        return !empty($this->read("1", "pseudo = :pseudo", null, [":pseudo" => $sPseudo], "fetch"));
    }

    public function emailExists($sEmail) {
        return !empty($this->read("1", "email = :email", null, [":email" => $sEmail], "fetch"));
    }

    public function emailVerified($sId) {
        $aResults = $this->read("COUNT(*)", "_id = :id AND email_confirmation = :confirm", null, [":id" => $sId, ":confirm" => 1], "fetch");
        return (bool) $aResults[0];
    }

    public function getAll() {
        return $this->read("*", "", []);
    }

    public function getFromId($sId) {
        return $this->read("*", "_id = :id", null, [":id" => $sId], "fetch");
    }

    public function getFromIdentifiant($sIdentifiant) {
        return $this->read("*", "pseudo = :identifiant OR email = :identifiant", null, [":identifiant" => $sIdentifiant], "fetch");
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
        $aCount = $this->read("COUNT(*)", $sCondition, $sOrder, $aBind, "fetch", PDO::FETCH_NUM);
        $nCount = (int) $aCount[0];

        return [
            "count" => $nCount,
            "users" => $this->readPaginate("*", $sCondition, $sOrder, $sLimit, $sOffset, $aBind)
        ];

    }

    public function create($aValues) {

        $aColumns = ["pseudo", "firstname", "lastname", "email", "phone", "birthday", "bio", "password", "inscription_date"];

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

    public function updateConnectionDate($sId) {
        $sDate = date("Y-m-d H:i:s", time());
        $this->update("connection_date = :date", "_id = :id", [":date" => $sDate, ":id" => $sId]);
    }

    public function updateBio($sBio, $sId) {
        $this->update("bio = :bio", "_id = :id", [":bio" => $sBio, ":id" => $sId]);
    }

    public function updateContact($sContact, $sId) {
        $this->update("contact_favori = :contact", "_id = :id", [":contact" => $sContact, ":id" => $sId]);
    }

    public function updateEmail($sEmail, $sId) {
        $this->update("email = :email", "_id = :id", [":email" => $sEmail, ":id" => $sId]);
    }

    public function updatePassword($sPassword, $sId) {
        $this->update("password = :password", "_id = :id", [":password" => $sPassword, ":id" => $sId]);
    }

    public function updateEmailConfirmation($bConfirm, $sId) {
        $this->update("email_confirmation = :confirm", "_id = :id", [":confirm" => $bConfirm, ":id" => $sId]);
    }

    public function updateBanned($bBanned, $sId) {
        $this->update("banned = :banned", "_id = :id", [":banned" => $bBanned, ":id" => $sId]);
    }

    public function updateRole($sRole, $sId) {
        $this->update("role = :role", "_id = :id", [":role" => $sRole, ":id" => $sId]);
    }

    public function removeUser($sId) {
        $this->delete("_id = :id", [":id" => $sId]);
    }

}