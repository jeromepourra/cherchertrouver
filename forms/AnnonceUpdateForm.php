<?php

namespace Forms;

use Form;
use Models\Annonces;
use Models\AnnoncesPictures;
use Models\Categories;

class AnnonceUpdateForm extends Form {

    private const FIELDS = [
        "category" => true,
        "title" => true,
        "price" => true,
        "description" => true
    ];
    private const FIELDS_FILES = [
        "new-pictures" => false
    ];

    private const TITLE_MIN_LEN = 2;
    private const TITLE_MAX_LEN = 64;

    private const PRICE_MIN = 0;
    private const PRICE_MAX = 999999999;

    private const DESCRIPTION_MIN_LEN = 2;
    private const DESCRIPTION_MAX_LEN = 1024;

    private const PICTURES_MIN = 0;
    private const PICTURES_MAX = 5;
    private const PICTURES_MAX_SIZE = 5 * 1024 * 1024;
    private const PICTURES_EXT_ACCEPTED = ["image/gif", "image/jpeg", "image/png"];

    private Categories $categoriesModel;
    private Annonces $annonceModel;
    private AnnoncesPictures $annoncePicturesModel;

    private $update = false;
    private $updateFields = [];

    public function check($sAnnonceId, Categories $oCategoriesModel, Annonces $oAnnonceModel, AnnoncesPictures $oAnnoncePicturesModel) {

        $this->categoriesModel = $oCategoriesModel;
        $this->annonceModel = $oAnnonceModel;
        $this->annoncePicturesModel = $oAnnoncePicturesModel;
        $this->initialize(self::FIELDS, self::FIELDS_FILES);

        $this->checkSends();
        $this->checkFulls();

        if ($this->success) {
            $this->checkCategory();
            $this->checkContent("title", self::TITLE_MIN_LEN, self::TITLE_MAX_LEN);
            $this->checkNumeric("price", self::PRICE_MIN, self::PRICE_MAX, 2);
            $this->checkContent("description", self::DESCRIPTION_MIN_LEN, self::DESCRIPTION_MAX_LEN);
        }
        
        $this->filesCheckSends();
        $this->filesCheckFulls();
        
        if ($this->success) {
            $nPicturesModel = $this->annoncePicturesModel->getCountFromAnnonce($sAnnonceId);
            $nPicturesMax = self::PICTURES_MAX - $nPicturesModel;
            $this->filesCheck("new-pictures", self::PICTURES_MIN, $nPicturesMax, self::PICTURES_MAX_SIZE, self::PICTURES_EXT_ACCEPTED, true);
        }

        if ($this->success) {
            $aAnnonceModel = $this->annonceModel->getFromId($sAnnonceId);
            $this->checkCategoryDifferent($aAnnonceModel);
            $this->checkTitleDifferent($aAnnonceModel);
            $this->checkPriceDifferent($aAnnonceModel);
            $this->checkDescriptionDifferent($aAnnonceModel);
            $this->checkFileUploaded();
        }

        if ($this->success) {
            if ($this->update) {
                $this->response->resetFieldsValue();
                $this->response->pushSuccess("form", "Votre annonce a bien été modifié");
            } else {
                $this->success = false;
                $this->response->pushError("form", "Vous devez modifier au moins l'un des champs");
            }
        } else {
            $this->response->pushError("form", "Une erreur est survenue, veuillez vérifier les champs");
        }

        return $this->success;

    }

    private function checkCategory() {
        if (!$this->categoriesModel->idExist($_POST["category"])) {
            $this->success = false;
            $this->response->pushError("category", "Cette catégorie n'existe pas");
        }
    }

    public function getUpdateFields() {
        return $this->updateFields;
    }

    private function pushUpdateFields($sField) {
        array_push($this->updateFields, $sField);
    }

    private function checkCategoryDifferent($aAnnonceModel) {
        $sNew = $_POST["category"];
        $sOld = $aAnnonceModel["category_id"];
        if ($sNew !== $sOld) {
            $this->update = true;
            $this->pushUpdateFields("category");
        }
    }

    private function checkTitleDifferent($aAnnonceModel) {
        $sNew = $_POST["title"];
        $sOld = $aAnnonceModel["title"];
        if ($sNew !== $sOld) {
            $this->update = true;
            $this->pushUpdateFields("title");
        }
    }

    private function checkPriceDifferent($aAnnonceModel) {
        $sNew = $_POST["price"];
        $sOld = $aAnnonceModel["price"];
        if ($sNew !== $sOld) {
            $this->update = true;
            $this->pushUpdateFields("price");
        }
    }

    private function checkDescriptionDifferent($aAnnonceModel) {
        $sNew = $_POST["description"];
        $sOld = $aAnnonceModel["description"];
        if ($sNew !== $sOld) {
            $this->update = true;
            $this->pushUpdateFields("description");
        }
    }

    private function checkFileUploaded() {
        if ($this->uploaded > 0) {
            $this->update = true;
            $this->pushUpdateFields("new-pictures");
        }
    }

}