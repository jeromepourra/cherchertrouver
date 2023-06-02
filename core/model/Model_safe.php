<?php

require ROOT . "/core/model/ModelException.php";

class Model {

    private $sHost;
    private $nPort;
    private $sName;
    private $sUserName;
    private $sUserPassword;
    private $sCharset = "utf8";

    private const HOST_LOCAL = "";
    private const PORT_LOCAL = -1;
    private const NAME_LOCAL = "";
    private const USERNAME_LOCAL = "";
    private const USERPASSWORD_LOCAL = "";

    private const HOST_ONLINE = "";
    private const PORT_ONLINE = -1;
    private const NAME_ONLINE = "";
    private const USERNAME_ONLINE = "";
    private const USERPASSWORD_ONLINE = "";

    private PDO $db;
    private $table;

    protected function connect() {

        $this->setParams();
        $sDsn = "mysql:host=" . $this->sHost . ";port=" . $this->nPort . ";dbname=" . $this->sName . ";charset=" . $this->sCharset;

        try {
            $this->db = new PDO($sDsn, $this->sUserName, $this->sUserPassword);
        } catch (PDOException $e) {
            throw $e;
        }

    }

    private function setParams() {
        if ($_SERVER["REMOTE_ADDR"] == "127.0.0.1" || $_SERVER["REMOTE_ADDR"] == "::1") {
            $this->setLocal();
        } else {
            $this->setOnline();
        }
    }

    private function setOnline() {
        $this->sHost = Model::HOST_ONLINE;
        $this->nPort = Model::PORT_ONLINE;
        $this->sName = Model::NAME_ONLINE;
        $this->sUserName = Model::USERNAME_ONLINE;
        $this->sUserPassword = Model::USERPASSWORD_ONLINE;
    }

    private function setLocal() {
        $this->sHost = Model::HOST_LOCAL;
        $this->nPort = Model::PORT_LOCAL;
        $this->sName = Model::NAME_LOCAL;
        $this->sUserName = Model::USERNAME_LOCAL;
        $this->sUserPassword = Model::USERPASSWORD_LOCAL;
    }

    private function isPdo() {
        return $this->db instanceof PDO;
    }

    private function tableExists($table) {
        if ($this->isPdo()) {
            try {
                $this->db->query("SELECT 1 FROM " . $table);
                return true;
            } catch (PDOException $e) {
                return false;
            }
        }
        return false;
    }

    protected function setTable($table) {
        if ($this->tableExists($table)) {
            $this->table = $table;
            return true;
        }
        return false;
    }

    private function verify($bCheckTable = true) {
        if (!$this->isPdo()) {
            throw new ModelException(__METHOD__, "you are not connected to the database");
        }
        if ($bCheckTable && $this->table === null) {
            throw new ModelException(__METHOD__, "no table defined");
        }
    }

    private function bind(PDOStatement &$oStatement, $aBind) {
        if (!empty($aBind)) {
            foreach ($aBind as $key => $val) {
                $oStatement->bindValue($key, $val);
            }
        }
    }

    private function execute(PDOStatement &$oStatement) {
        $bExec = $oStatement->execute();
        if (!$bExec) {
            throw new ModelException(__METHOD__, "SQL query could not be executed");
        }
    }

    private function fetch(PDOStatement &$oStatement, $sFunc, $nMode) {
        $oStatement->setFetchMode($nMode);
        $data = $oStatement->$sFunc();
        $this->setSafe($data);
        return $data;
    }

    private function setSafe(&$mData) {
        if (is_array($mData)) {
            $aKeys = array_keys($mData);
            foreach ($aKeys as $sKey) {
                $this->setSafe($mData[$sKey]);
            }
        } else {
            $mData = htmlspecialchars($mData);
        }
    }

    protected function getLastInsert($sPrimaryKey = "_id") {
        
        try {

            $this->verify();

            $sQuery = "SELECT * FROM " . $this->table . " ORDER BY " . $sPrimaryKey . " DESC LIMIT 1";
            $oStatement = $this->db->prepare($sQuery);
            $this->execute($oStatement);
            return $this->fetch($oStatement, "fetch", PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            throw $e;
        }

    }

    protected function read($sSelector = "*", $sCondition = null, $sOrder = null, $aBind = [], $sFetchFunc = "fetchAll", $nFetchMode = PDO::FETCH_ASSOC) {

        try {

            $this->verify();

            $sQuery = "SELECT " . $sSelector . " FROM " . $this->table;

            if ($sCondition !== null && !empty($sCondition)) {
                $sQuery = $sQuery . " WHERE " . $sCondition;
            }

            if ($sOrder !== null && !empty($sOrder)) {
                $sQuery = $sQuery . " ORDER BY " . $sOrder;
            }

            $oStatement = $this->db->prepare($sQuery);
            $this->bind($oStatement, $aBind);
            $this->execute($oStatement);
            return $this->fetch($oStatement, $sFetchFunc, $nFetchMode);
            
        } catch (Exception $e) {
            throw $e;
        }

    }

    protected function readJoin($sJoinTable, $sJoinCondition = null, $sSelector = "*", $sCondition = null, $sOrder = null, $aBind = [], $sFetchFunc = "fetchAll", $nFetchMode = PDO::FETCH_ASSOC) {

        try {

            $this->verify();

            $sQuery = "SELECT " . $sSelector . " FROM " . $this->table;

            if ($sJoinTable !== null && !empty($sJoinTable)) {
                $bExists = $this->tableExists($sJoinTable);
                if ($bExists) {
                    $sQuery = $sQuery . " INNER JOIN " . $sJoinTable;
                } else {
                    throw new ModelException(__METHOD__, "table:" . $sJoinTable . " not exists");
                }
            }

            if ($sJoinCondition !== null && !empty($sJoinCondition)) {
                $sQuery = $sQuery . " ON " . $sJoinCondition;
            }

            if ($sCondition !== null && !empty($sCondition)) {
                $sQuery = $sQuery . " WHERE " . $sCondition;
            }

            if ($sOrder !== null && !empty($sOrder)) {
                $sQuery = $sQuery . " ORDER BY " . $sOrder;
            }

            $oStatement = $this->db->prepare($sQuery);
            $this->bind($oStatement, $aBind);
            $this->execute($oStatement);
            return $this->fetch($oStatement, $sFetchFunc, $nFetchMode);
            
        } catch (Exception $e) {
            throw $e;
        }

    }

    protected function readPaginate($sSelector = "*", $sCondition = null, $sOrder = null, $sLimit = null, $sOffset = null, $aBind = [], $sFetchFunc = "fetchAll", $nFetchMode = PDO::FETCH_ASSOC) {

        try {

            $this->verify();

            $sQuery = "SELECT " . $sSelector . " FROM " . $this->table;

            if ($sCondition !== null && !empty($sCondition)) {
                $sQuery = $sQuery . " WHERE " . $sCondition;
            }

            if ($sOrder !== null && !empty($sOrder)) {
                $sQuery = $sQuery . " ORDER BY " . $sOrder;
            }

            if ($sLimit !== null && !empty($sLimit)) {
                $sQuery = $sQuery . " LIMIT " . $sLimit;
            }

            if ($sOffset !== null && !empty($sOffset)) {
                $sQuery = $sQuery . " OFFSET " . $sOffset;
            }

            $oStatement = $this->db->prepare($sQuery);
            $this->bind($oStatement, $aBind);
            $this->execute($oStatement);
            return $this->fetch($oStatement, $sFetchFunc, $nFetchMode);
            
        } catch (Exception $e) {
            throw $e;
        }

    }

    protected function readJoinPaginate($sJoinTable, $sJoinCondition = null, $sSelector = "*", $sCondition = null, $sOrder = null, $sLimit = null, $sOffset = null, $aBind = [], $sFetchFunc = "fetchAll", $nFetchMode = PDO::FETCH_ASSOC) {

        try {

            $this->verify();

            $sQuery = "SELECT " . $sSelector . " FROM " . $this->table;

            if ($sJoinTable !== null && !empty($sJoinTable)) {
                $bExists = $this->tableExists($sJoinTable);
                if ($bExists) {
                    $sQuery = $sQuery . " INNER JOIN " . $sJoinTable;
                } else {
                    throw new ModelException(__METHOD__, "table:" . $sJoinTable . " not exists");
                }
            }

            if ($sJoinCondition !== null && !empty($sJoinCondition)) {
                $sQuery = $sQuery . " ON " . $sJoinCondition;
            }

            if ($sCondition !== null && !empty($sCondition)) {
                $sQuery = $sQuery . " WHERE " . $sCondition;
            }

            if ($sOrder !== null && !empty($sOrder)) {
                $sQuery = $sQuery . " ORDER BY " . $sOrder;
            }

            if ($sLimit !== null && !empty($sLimit)) {
                $sQuery = $sQuery . " LIMIT " . $sLimit;
            }

            if ($sOffset !== null && !empty($sOffset)) {
                $sQuery = $sQuery . " OFFSET " . $sOffset;
            }

            $oStatement = $this->db->prepare($sQuery);
            $this->bind($oStatement, $aBind);
            $this->execute($oStatement);
            return $this->fetch($oStatement, $sFetchFunc, $nFetchMode);
            
        } catch (Exception $e) {
            throw $e;
        }

    }

    protected function insert($sColumns, $sValues, $aBind = []) {

        if (!empty($sColumns) && !empty($sValues)) {

            try {

                $this->verify();

                $oStatement = $this->db->prepare("INSERT INTO " . $this->table . " (" . $sColumns . ") VALUES (" . $sValues . ")");
                $this->bind($oStatement, $aBind);
                $this->execute($oStatement);

            } catch (Exception $e) {
                throw $e;
            }

        } else {
            throw new ModelException(__METHOD__, "missing columns or values");
        }

    }

    protected function update($sSet, $sCondition, $aBind = []) {

        if (!empty($sSet) && !empty($sCondition)) {

            try {

                $this->verify();

                $oStatement = $this->db->prepare("UPDATE " . $this->table . " SET " . $sSet . " WHERE " . $sCondition);
                $this->bind($oStatement, $aBind);
                $this->execute($oStatement);

            } catch (Exception $e) {
                throw $e;
            }

        } else {
            throw new ModelException(__METHOD__, "missing column or value or condition");
        }

    }

    protected function delete($sCondition, $aBind = []) {

        if (!empty($sCondition)) {

            try {

                $this->verify();

                $oStatement = $this->db->prepare("DELETE FROM " . $this->table . " WHERE " . $sCondition);
                $this->bind($oStatement, $aBind);
                $this->execute($oStatement);

            } catch (Exception $e) {
                throw $e;
            }

        } else {
            throw new ModelException(__METHOD__, "missing condition");
        }

    }
    
}

?>