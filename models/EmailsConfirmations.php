<?php

namespace Models;

use Model;
use ModelException;
use PDO;

class EmailsConfirmations extends Model {

    public function __construct() {
        $this->connect();
        $this->setTable("emails_confirmations");
    }

    public function exists($sUserId, $sUrlKey) {
        $aResults = $this->read("COUNT(*)", "user_id  = :userId AND url_key = :urlKey", null, [":userId" => $sUserId, ":urlKey" => $sUrlKey], "fetch", PDO::FETCH_NUM);
        return (bool) $aResults[0];
    }

    public function getFromKey($sKey) {
        return $this->read("*", "url_key = :key", null, [":key" => $sKey], "fetch");
    }

    public function create($aValues) {

        $aColumns = ["user_id", "url_key"];

        if (count($aColumns) === count($aValues)) {

            $sColumns = join(",", $aColumns);
            $sValues = ":" . join(",:", $aColumns);
            $aBind = [];

            foreach ($aColumns as $nIndex => $sColumn) {
                $aBind += [":" . $sColumn => $aValues[$nIndex]];
            }
    
            $this->insert($sColumns, $sValues, $aBind);

        } else {
            throw new ModelException(__METHOD__, "number of columns does not match number of values");
        }

    }

    public function remove($sUserId) {
        $this->delete("user_id = :userId", [":userId" => $sUserId]);
    }

}

?>