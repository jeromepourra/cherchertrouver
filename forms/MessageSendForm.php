<?php 

namespace Forms;

use Form;

class MessageSendForm extends Form {

    private const FIELDS = [
        "content" => true,
    ];

    private const CONTENT_MIN_LEN = 2;
    private const CONTENT_MAX_LEN = 1024;

    public function check() {
        
        $this->initialize(self::FIELDS);

        $this->checkSends();
        $this->checkFulls();

        if ($this->success) {
            $this->checkContent("content", self::CONTENT_MIN_LEN, self::CONTENT_MAX_LEN);
        }

        if ($this->success) {
            $this->response->resetFieldsValue();
            $this->response->pushSuccess("form", "Votre message a bien été envoyé");
        } else {
            $this->response->pushError("form", "Une erreur est survenue, veuillez vérifier les champs");
        }

        return $this->success;

    }

}