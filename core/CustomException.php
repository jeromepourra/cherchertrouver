<?php

class CustomException extends Exception {
    
    public function __construct($sFrom, $sData, $nCode = 0) {
        $this->message = $sFrom . "() -> " . $sData;
        $this->code = $nCode;
    }

}