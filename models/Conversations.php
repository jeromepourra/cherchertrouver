<?php

namespace Models;

use Model;
use ModelException;

class Conversations extends Model {

    public function __construct() {
        $this->connect();
        $this->setTable("conversations");
    }

    public function getFromIdWhereUser($sId, $sUserId) {
        return $this->read("*", "_id = :id AND (user_1_id = :userId OR user_2_id = :userId)", null, [":id" => $sId, ":userId" => $sUserId], "fetch");
    }

    public function getFromUser($sUserId) {
        return $this->read("*", "user_1_id = :userId OR user_2_id = :userId", "last_message_date DESC", [":userId" => $sUserId]);
    }

    public function create($aValues) {

        $aColumns = ["annonce_id", "user_1_id", "user_2_id", "last_message_date", "last_message_user"];

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

    public function updateLastMessageDate($sId) {
        $sDate = date("Y-m-d H:i:s", time());
        $this->update("last_message_date = :date", "_id = :id", [":date" => $sDate, ":id" => $sId]);
    }

    public function updateLastMessageUser($sUserId, $sId) {
        $this->update("last_message_user = :userId", "_id = :id", [":userId" => $sUserId, ":id" => $sId]);
    }

}

?>