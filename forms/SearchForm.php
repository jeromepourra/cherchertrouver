<?php

namespace Forms;

use AnnonceState;
use Form;
use Models\Categories;

class SearchForm extends Form {

    private const METHOD = "GET";
    private const FIELDS = [
        "sort" => true,
        "category" => true,
        "key-word" => true,
        "price-min" => true,
        "price-max" => true,
        "state" => false
    ];

    private const PRICE_MIN = 0;
    private const PRICE_MAX = 999999999.99;

    private Categories $categoriesModel;

    public function check(Categories $oCategoriesModel) {

        $this->categoriesModel = $oCategoriesModel;
        $this->initialize(self::FIELDS, [], self::METHOD);

        $this->checkFulls();

        $this->checkCategory();
        $this->checkPriceMin();
        $this->checkPriceMax();
        $this->checkPrice();
        $this->checkState();

        if ($this->success) {
            // NO RESET
        } else {
            $this->response->pushError("form", "Une erreur est survenue, veuillez vérifier les champs");
        }

        return $this->success;

    }

    private function checkCategory() {
        if (isset($_GET["category"])) {
            if ($_GET["category"] != 0) {
                if (!$this->categoriesModel->idExist($_GET["category"])) {
                    $this->success = false;
                    $this->response->pushError("category", "Cette catégorie n'existe pas");
                }
            }
        }
    }

    private function checkPriceMin() {
        if (isset($_GET["price-min"])) {
            $this->checkNumeric("price-min", self::PRICE_MIN, self::PRICE_MAX, 2);
        }
    }

    private function checkPriceMax() {
        if (isset($_GET["price-max"])) {
            $this->checkNumeric("price-max", self::PRICE_MIN, self::PRICE_MAX, 2);
        }
    }

    private function checkPrice() {
        if (isset($_GET["price-min"]) && isset($_GET["price-max"])) {
            $nPriceMin = (float) $_GET["price-min"];
            $nPriceMax = (float) $_GET["price-max"];
            if ($nPriceMin > $nPriceMax) {
                $this->success = false;
                $this->response->pushError("price-min", "Le prix min doit être inférieur au prix max");
                $this->response->pushError("price-max", "Le prix max doit être supérieur au prix min");
            }
        }
    }

    private function checkState() {
        if (isset($_GET["state"])) {
            $nState = (int) $_GET["state"];
            if (!array_key_exists($nState, AnnonceState::ANNONCE_STATES)) {
                $this->success = false;
                $this->response->pushError("state", "Cet état n'existe pas");
            }
        }
    }

}