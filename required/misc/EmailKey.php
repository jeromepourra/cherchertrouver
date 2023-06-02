<?php

class EmailKey {

    private const MIN_LEN = 64;
    private const MAX_LEN = 128;
    private const HASH = ["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","0","1","2","3","4","5","6","7","8","9","-","_"];

    public static function generate() {

        $sUrlKey = "";
        $nHashLen = count(self::HASH) - 1;
        $nRandLen = random_int(self::MIN_LEN, self::MAX_LEN);

        for ($i=0; $i<$nRandLen; $i++) {
            $nRandIndex = random_int(0, $nHashLen);
            $sUrlKey .= self::HASH[$nRandIndex];
        }

        return $sUrlKey;

    }

}