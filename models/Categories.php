<?php

namespace Models;

use Model;

class Categories extends Model {

    public function __construct() {
        $this->connect();
        $this->setTable("categories");
    }

    public function getAll() {
        return $this->read("*", null, "name ASC");
    }

    public function getFromId($sId) {
        return $this->read("*", "_id = :id", null, [":id" => $sId], "fetch");
    }

    public function idExist($sId) {
        return !empty($this->read("1", "_id = :id", null, [":id" => $sId], "fetch"));
    }

}

?>