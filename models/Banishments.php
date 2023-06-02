<?php

namespace Models;

use Model;
use ModelException;

class Banishments extends Model {

    public function __construct() {
        $this->connect();
        $this->setTable("banishments");
    }

    public function getFromUserId($sId) {
        return $this->read("*", "user_id = :id", null, [":id" => $sId], "fetch");
    }

    public function create($aValues) {

        $aColumns = ["user_id", "reason", "date"];

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