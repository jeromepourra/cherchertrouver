<?php

namespace Forms;

use Form;
use Models\Categories;

class AnnoncePostForm extends Form {

    private const FIELDS = [
        "category" => true,
        "title" => true,
        "price" => true,
        "description" => true
    ];
    private const FIELDS_FILES = [
        "pictures" => true
    ];

    private const TITLE_MIN_LEN = 2;
    private const TITLE_MAX_LEN = 64;

    private const PRICE_MIN = 0;
    private const PRICE_MAX = 999999999.99;

    private const DESCRIPTION_MIN_LEN = 2;
    private const DESCRIPTION_MAX_LEN = 1024;

    private const PICTURES_MIN = 1;
    private const PICTURES_MAX = \Constants::ANNONCE_PICTURES_MAX;
    private const PICTURES_MAX_SIZE = 5 * 1024 * 1024;
    private const PICTURES_EXT_ACCEPTED = ["image/gif", "image/jpeg", "image/jpg", "image/png"];

    private Categories $categoriesModel;

    public function check(Categories $oCategoriesModel) {

        $this->categoriesModel = $oCategoriesModel;
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
            $this->filesCheck("pictures", self::PICTURES_MIN, self::PICTURES_MAX, self::PICTURES_MAX_SIZE, self::PICTURES_EXT_ACCEPTED, true);
        }

        if ($this->success) {
            $this->response->resetFieldsValue();
            $this->response->pushSuccess("form", "Votre annonce a bien été enregistrée");
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

}