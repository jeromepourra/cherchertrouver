<?php

use Models\AnnoncesPictures;

class FormFileManager {

    private const ANNONCES_PICTURES_FORMAT = 16 / 9;
    private const ANNONCES_PICTURES_WIDTH = 750;
    private const ANNONCES_PICTURES_HEIGHT = self::ANNONCES_PICTURES_WIDTH / self::ANNONCES_PICTURES_FORMAT;

    private const IMAGES_FUNCTIONS = [
        "image/jpeg" => [
            "create" => "imagecreatefromjpeg",
            "save" => "imagejpeg"
        ],
        "image/png" => [
            "create" => "imagecreatefrompng",
            "save" => "imagepng"
        ],
        "image/gif" => [
            "create" => "imagecreatefromgif",
            "save" => "imagegif"
        ]
    ];

    private $from;
    private $fromPathInfo;

    private $to;
    private $toPathInfo;

    private function fileExists($sPath) {
        return file_exists($sPath);
    }

    private function removeFile($sPath) {
        if ($this->fileExists($sPath)) {
            unlink($sPath);
        }
    }

    private function createDir($sDir) {
        if (!$this->fileExists($sDir)) {
            mkdir($sDir, 0777, true);
        }
    }

    private function removeDir($sDir) {
        if ($this->fileExists($sDir)) {
            $aFiles = glob($sDir . '*', GLOB_MARK);
            foreach ($aFiles as $sFile) {
                if (is_dir($sFile)) {
                    $this->removeDir(str_replace("\\", "/", $sFile));
                } else {
                    $this->removeFile($sFile);
                }
            }
            rmdir($sDir);
        }
    }

    public function uploadAnnoncePictures($sField, $sAnnonceId, AnnoncesPictures $oAnnoncesPicturesModel) {

        $aFiles = $_FILES[$sField];

        for ($i=0; $i<count($aFiles["name"]); $i++) {

            $sName = $aFiles["name"][$i];
            $sTmpName = $aFiles["tmp_name"][$i];
            $sExtension = pathinfo($sName)["extension"];

            $aValues = [$sAnnonceId, $sExtension];
            $oPicture = $oAnnoncesPicturesModel->create($aValues);
            $this->uploadAnnoncePicture($sAnnonceId, $oPicture["_id"], $sExtension, $sTmpName);

        }

    }

    private function uploadAnnoncePicture($sToDir, $sToName, $sToExtension, $sFrom) {

        $this->from = $sFrom;
        $this->fromPathInfo = pathinfo($this->from);

        $this->to = ROOT . Constants::PATH_ANNONCES . "/" . $sToDir . "/" . $sToName . "." . $sToExtension;
        $this->toPathInfo = pathinfo($this->to);

        $this->createDir($this->toPathInfo["dirname"]);
        move_uploaded_file($this->from, $this->to);

        $this->redimImage(self::ANNONCES_PICTURES_WIDTH, self::ANNONCES_PICTURES_HEIGHT);

    }

    public function redimImage($nWidth, $nHeight) {

        $aImgInfo = getimagesize($this->to);
        $nWidthSrc = $aImgInfo[0];
        $nHeightSrc = $aImgInfo[1];
        $sType = $aImgInfo["mime"];
        $sCreateFunc = null;
        $sSaveFunc = null;

        foreach (self::IMAGES_FUNCTIONS as $sKey => $aFuncs) {
            if ($sType == $sKey) {
                $sCreateFunc = $aFuncs["create"];
                $sSaveFunc = $aFuncs["save"];
                break;
            }
        }

        $nRatioThumb = $nWidth / $nHeight;
        $nRatioOrigin = $nWidthSrc / $nHeightSrc;
        
        if ($nRatioOrigin >= $nRatioThumb) {
            $nOriginY = $nHeightSrc; 
            $nOriginX = ceil(($nOriginY * $nWidth) / $nHeight);
            $nCropX = ceil(($nWidthSrc - $nOriginX) / 2);
            $nCropY = 0;
        } else {
            $nOriginX = $nWidthSrc; 
            $nOriginY = ceil(($nOriginX * $nHeight) / $nWidth);
            $nCropY = ceil(($nHeightSrc - $nOriginY) / 2);
            $nCropX = 0;
        }
    
        $oImage_1 = imagecreatetruecolor($nWidth, $nHeight);
        $oImage_2 = $sCreateFunc($this->to);
    
        imagecopyresampled($oImage_1, $oImage_2, 0, 0, $nCropX, $nCropY, $nWidth, $nHeight, $nOriginX, $nOriginY);
        $sSaveFunc($oImage_1, $this->to);
        imagedestroy($oImage_1);
        imagedestroy($oImage_2);

    }

    public function removeAnnoncePictures($sFromDir) {
        $this->from = ROOT . Constants::PATH_ANNONCES . "/" . $sFromDir . "/";
        $this->fromPathInfo = pathinfo($this->from);
        $this->removeDir($this->from);
    }

    public function removeAnnoncePicture($sFromDir, $sFromFile) {
        $this->from = ROOT . Constants::PATH_ANNONCES . "/" . $sFromDir . "/" . $sFromFile;
        $this->fromPathInfo = pathinfo($this->from);
        $this->removeFile($this->from);
    }

}