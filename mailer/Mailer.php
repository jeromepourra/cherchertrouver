<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require ROOT . "/mailer/PHPMailer/src/Exception.php";
require ROOT . "/mailer/PHPMailer/src/PHPMailer.php";
require ROOT . "/mailer/PHPMailer/src/SMTP.php";

class Mailer {

    public static function send($sContentName, $sFrom, $sFromName, $sTo, $sToName, $sSubject, $aData) {

        $sMailPath = ROOT . "/mailer/content/" . $sContentName . ".php";

        if (file_exists($sMailPath)) {

            $___MAIL_DATA___ = $aData;
            ob_start();
            require $sMailPath;
            $sMailContent = ob_get_clean();

            $oMail = new PHPMailer(true);

            try {

                $oMail->SMTPDebug = SMTP::DEBUG_OFF;
                $oMail->isSMTP();
                $oMail->Host        = 'smtp.ionos.fr';
                $oMail->SMTPAuth    = true;
                $oMail->Username    = 'noreply@cherchertrouver.jeromepourra.fr';
                $oMail->Password    = 'lx2yDd7OVQ3ucV3AUblM';
                $oMail->SMTPSecure  = PHPMailer::ENCRYPTION_SMTPS;
                $oMail->Port        = 465;
                $oMail->CharSet     = PHPMailer::CHARSET_UTF8;
                $oMail->Encoding    = PHPMailer::ENCODING_BINARY;
            
                $oMail->setFrom($sFrom, self::replaceAccent($sFromName));
                $oMail->addAddress($sTo, self::replaceAccent($sToName));
            
                $oMail->isHTML(true);
                $oMail->Subject     = $sSubject;
                $oMail->Body        = $sMailContent;
            
                $oMail->send();
                return true;

            } catch (Exception $e) {
                // NOTHING
            }

        }

        return false;

    }

    private static function replaceAccent($sData) {
        $aReplace = ["Š" => "S", "š" => "s", "Ž" => "Z", "ž" => "z", "À" => "A", "Á" => "A", "Â" => "A", "Ã" => "A", "Ä" => "A", "Å" => "A", "Æ" => "A", "Ç" => "C", "È" => "E", "É" => "E", "Ê" => "E", "Ë" => "E", "Ì" => "I", "Í" => "I", "Î" => "I", "Ï" => "I", "Ñ" => "N", "Ò" => "O", "Ó" => "O", "Ô" => "O", "Õ" => "O", "Ö" => "O", "Ø" => "O", "Ù" => "U", "Ú" => "U", "Û" => "U", "Ü" => "U", "Ý" => "Y", "Þ" => "B", "ß" => "Ss", "à" => "a", "á" => "a", "â" => "a", "ã" => "a", "ä" => "a", "å" => "a", "æ" => "a", "ç" => "c", "è" => "e", "é" => "e", "ê" => "e", "ë" => "e", "ì" => "i", "í" => "i", "î" => "i", "ï" => "i", "ð" => "o", "ñ" => "n", "ò" => "o", "ó" => "o", "ô" => "o", "õ" => "o", "ö" => "o", "ø" => "o", "ù" => "u", "ú" => "u", "û" => "u", "ý" => "y", "þ" => "b", "ÿ" => "y"];
        $sReplace = strtr($sData, $aReplace);
        return $sReplace;
    }

}