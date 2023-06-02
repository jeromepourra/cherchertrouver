<?php

require ROOT . "/core/form/FormResponse.php";
require ROOT . "/core/form/FormException.php";
require ROOT . "/core/form/FormFileManager.php";

class Form {

    private $fields;
    private $method;

    private $fieldsFiles;
    private $methodFiles;

    protected $success;
    protected $uploaded;
    protected FormResponse $response;

    public function initialize($aFields, $aFieldsFiles = [], $sMethod = "POST") {

        $this->fields = $aFields;
        $this->method = $sMethod == "POST" ? $_POST : $_GET;

        $this->fieldsFiles = $aFieldsFiles;
        $this->methodFiles = $_FILES;

        $this->response = new FormResponse();
        $this->success = true;
        $this->uploaded = 0;

    }

    public function getResponse() {
        return $this->response->get();
    }

    // FIELDS

    protected function checkSend($sField) {
        if (!isset($this->method[$sField])) {
            $this->success = false;
            $this->response->pushError($sField, "Ce champ n'a pas été envoyé");
        }
    }

    protected function checkFull($sField) {
        if (isset($this->method[$sField])) {
            if (empty($this->method[$sField]) && !($this->method[$sField] === "0")) {
                if ($this->fields[$sField]) {
                    $this->success = false;
                    $this->response->pushError($sField, "Ce champ doit être rempli");
                }
            } else {
                $this->response->putFieldsValue($sField, $this->method[$sField]);
            }
        }
    }

    protected function checkSends() {
        foreach (array_keys($this->fields) as $sField) {
            if (!isset($this->method[$sField])) {
                $this->success = false;
                $this->response->pushError($sField, "Ce champ n'a pas été envoyé");
            }
        }
    }

    protected function checkFulls() {
        foreach ($this->fields as $sField => $bRequired) {
            if (isset($this->method[$sField])) {
                if (empty($this->method[$sField]) && !($this->method[$sField] === "0")) {
                    if ($bRequired) {
                        $this->success = false;
                        $this->response->pushError($sField, "Ce champ doit être rempli");
                    }
                } else {
                    $this->response->putFieldsValue($sField, $this->method[$sField]);
                }
            }
        }
    }

    protected function checkContent($sField, $nMinLen, $nMaxLen, $sRegEx=null, $sRegExError=null) {
        $sData = $this->method[$sField];
        $nDataLen = strlen($sData);
        if ($nDataLen < $nMinLen || $nDataLen > $nMaxLen) {
            $this->success = false;
            $this->response->pushError($sField, "Ce champ doit contenir de " . $nMinLen . " à " . $nMaxLen . " caractères");
        }
        if ($sRegEx !== null) {
            $bMatch = preg_match($sRegEx, $sData);
            if (!$bMatch) {
                if ($sRegExError !== null) {
                    $this->success = false;
                    $this->response->pushError($sField, $sRegExError);
                } else {
                    $this->success = false;
                    $this->response->pushError($sField, "Ce champ doit uniquement contenir les caractères " . $sRegEx);
                }
            }
        }
    }

    protected function checkNumeric($sField, $nMinVal, $nMaxVal, $nDecimalPlace = 0) {
        $sData = $this->method[$sField];
        $sMinVal = number_format($nMinVal, $nDecimalPlace, ",", " ");
        $sMaxVal = number_format($nMaxVal, $nDecimalPlace, ",", " ");
        if (!$nDecimalPlace) {
            if (filter_var($sData, FILTER_VALIDATE_INT) === false) {
                $this->success = false;
                $this->response->pushError($sField, "Ce champ doit comporter un nombre entier");
            } else {
                $nData = intval($sData);
                if ($nData < $nMinVal || $nData > $nMaxVal) {
                    $this->success = false;
                    $this->response->pushError($sField, "Ce champ doit être compris entre <span class='fw-bold'>" . $sMinVal . "</span> et <span class='fw-bold'>" . $sMaxVal . "</span>");
                }
            }
        } else {
            if (filter_var($sData, FILTER_VALIDATE_FLOAT) === false) {
                $this->response->pushError($sField, "Ce champ doit comporter un nombre");
            } else {
                $nData = (float) $sData;
                if ($nData < $nMinVal || $nData > $nMaxVal) {
                    $this->success = false;
                    $this->response->pushError($sField, "Doit être compris entre <span class='fw-bold'>" . $sMinVal . "</span> et <span class='fw-bold'>" . $sMaxVal . "</span>");
                }
            }
        }
    }

    protected function checkCheckbox($sField) {
        $sData = $this->method[$sField];
        if ($sData != "on") {
            $this->success = false;
            $this->response->pushError($sField, "Ce champ doit être coché");
        }
    }

    // FIELDS FILES

    protected function filesCheckSend($sField) {
        if (!isset($this->methodFiles[$sField])) {
            $this->success = false;
            $this->response->pushError($sField, "Ce champ n'a pas été envoyé");
        }
    }

    protected function filesCheckFull($sField) {
        if (isset($this->methodFiles[$sField])) {
            if ($this->methodFiles[$sField]["error"][0] === UPLOAD_ERR_NO_FILE) {
                if ($this->fieldsFiles[$sField]) {
                    $this->success = false;
                    $this->response->pushError($sField, "Vous devez selectionner au moins 1 fichier");
                }
            }
        }
    }

    protected function filesCheckSends() {
        foreach (array_keys($this->fieldsFiles) as $sField) {
            if (!isset($this->methodFiles[$sField])) {
                $this->success = false;
                $this->response->pushError($sField, "Ce champ n'a pas été envoyé");
            }
        }
    }

    protected function filesCheckFulls() {
        foreach ($this->fieldsFiles as $sField => $bRequired) {
            if (isset($this->methodFiles[$sField])) {
                if ($this->methodFiles[$sField]["error"][0] === UPLOAD_ERR_NO_FILE) {
                    if ($bRequired) {
                        $this->success = false;
                        $this->response->pushError($sField, "Vous devez selectionner au moins 1 fichier");
                    }
                }
            }
        }
    }

    protected function filesCheck($sField, $nMin, $nMax, $nMaxSize, $aTypeAccept, $bImg = false) {
        $aFiles = $this->methodFiles[$sField];
        if ($aFiles["error"][0] !== UPLOAD_ERR_NO_FILE) {
            $nCount = count($aFiles["name"]);
            if ($nCount >= $nMin && $nCount <= $nMax) {
                for ($i=0; $i<$nCount; $i++) {
                    $aFile = [
                        "name" => $aFiles["name"][$i],
                        "type" => $aFiles["type"][$i],
                        "tmp_name" => $aFiles["tmp_name"][$i],
                        "error" => $aFiles["error"][$i],
                        "size" => $aFiles["size"][$i],
                    ];
                    $this->fileCheck($sField, $aFile, $nMaxSize, $aTypeAccept, $bImg);
                }
            } else {
                $this->success = false;
                $this->response->pushError($sField, "Vous devez uploader de " . $nMin . " à " . $nMax . " fichiers");
            }
        } else {
            if ($nMin > 0) {
                $this->success = false;
                $this->response->pushError($sField, "Vous devez uploader de " . $nMin . " à " . $nMax . " fichiers");
            }
        }
    }

    protected function fileCheck($sField, $aFile, $nMaxSize, $aTypeAccept, $bImg) {

        $sName = $aFile["name"];
        $sTmpName = $aFile["tmp_name"];
        $sType = $aFile["type"];
        $nError = $aFile["error"];
        $nSize = $aFile["size"];

        if ($nError !== UPLOAD_ERR_OK) {
            $this->success = false;
            $this->response->pushError($sField, "Une erreur est survenue avec le fichier '" . htmlspecialchars($sName) . "'");
            return;
        }
        
        if ($nSize > $nMaxSize) {
            $this->success = false;
            $this->response->pushError($sField, "Le fichier '" . htmlspecialchars($sName) . "' est trop volumineux, la taille maximale est de " . $nMaxSize . " octets");
        }

        if (!in_array($sType, $aTypeAccept)) {
            $this->success = false;
            $this->response->pushError($sField, "Le type du fichier '" . htmlspecialchars($sName) . "' n'est pas accepté.");
        } elseif ($bImg) {
            $this->filesCheckImage($sField, $sName, $sTmpName);
        }

        if ($this->success) {
            $this->uploaded++;
        }

    }

    private function filesCheckImage($sField, $sName, $sTmpName) {
        if (exif_imagetype($sTmpName) === false) {
            $this->success = false;
            $this->response->pushError($sField, "Le fichier '" . htmlspecialchars($sName) . "' n'est pas une image.");
        }
    }

}