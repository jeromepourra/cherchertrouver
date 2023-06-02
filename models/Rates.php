<?php

namespace Models;

use Model;
use ModelException;
use PDO;

class Rates extends Model {

    public function __construct() {
        $this->connect();
        $this->setTable("rates");
    }

    public function exists($sFrom, $sTo) {
        $aResults = $this->read("COUNT(*)", "from_user  = :from AND to_user = :to", null, [":from" => $sFrom, ":to" => $sTo], "fetch", PDO::FETCH_NUM);
        return (bool) $aResults[0];
    }

    public function getRateAvgFromUser($sUserId) {
        $aResults = $this->read("ROUND(AVG(value))", "to_user = :user", null, [":user" => $sUserId], "fetch", PDO::FETCH_NUM);
        return (int) $aResults[0];
    }

    public function getRateCountFromUser($sUserId) {
        $aResults = $this->read("COUNT(*)", "to_user = :user", null, [":user" => $sUserId], "fetch", PDO::FETCH_NUM);
        return (int) $aResults[0];
    }

    public function getMyRate($sFrom, $sTo) {
        return $this->read("value", "from_user  = :from AND to_user = :to", null, [":from" => $sFrom, ":to" => $sTo], "fetch", PDO::FETCH_NUM);
    }

    public function create($aValues) {

        $aColumns = ["from_user", "to_user", "value"];

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

}

?>