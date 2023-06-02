<?php

class FormResponse {

    private $response = [
        "messages" => [
            "success" => [],
            "errors" => []
        ],
        "values" => [],
        "data" => []
    ];

    public function get() {
        return $this->response;
    }

    public function pushSuccess($sKey, $sMsg) {
        if (isset($this->response["messages"]["success"][$sKey])) {
            array_push($this->response["messages"]["success"][$sKey], $sMsg);
        } else {
            $this->response["messages"]["success"][$sKey] = [$sMsg];
        }
    }

    public function pushError($sKey, $sMsg) {
        if (isset($this->response["messages"]["errors"][$sKey])) {
            array_push($this->response["messages"]["errors"][$sKey], $sMsg);
        } else {
            $this->response["messages"]["errors"][$sKey] = [$sMsg];
        }
    }

    public function putFieldsValue($sKey, $sData) {
        $this->response["values"][$sKey] = $sData;
    }

    public function resetFieldsValue() {
        $this->response["values"] = [];
    }

    public function putData($sKey, $sData) {
        $this->response["data"][$sKey] = $sData;
    }

}