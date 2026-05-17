<?php

require_once "Db.php";

class Images extends Db{
    const ALLOWED_EXTENSIONS = ['jpeg', 'jpg', 'png', 'gif'];
    const MAX_FILE_SIZE = 2 * 1024 * 1024;
    const MAX_IMAGE_WIDTH = 1920;
    const MAX_IMAGE_HEIGHT = 1024;


    public function isValidSize($size) {
        if($size > self::MAX_FILE_SIZE){
            return false;
        }
        return true;
    }

    public function isValidExtension($extension) {
        if(!in_array($extension, self::ALLOWED_EXTENSIONS)){
            return false;
        }
        return true;
    }

    public function isValidDimensions($imageWidth, $imageHeight) {
        if($imageWidth > self::MAX_IMAGE_WIDTH || $imageHeight > self::MAX_IMAGE_HEIGHT){
            return false;
        }
        return true;
    }

    public function generateRandomName($extension) {
        return uniqid('img_') . "." . $extension;
    }

    public function upload($image, $finalName, $destination) {
    $finalDestination = $destination . "/" . $finalName;

    if(move_uploaded_file($image, $finalDestination)) {
        $finalName = $this->connection->real_escape_string($finalName);
        $this->connection->query("INSERT INTO images (image) VALUES ('$finalName')");
        return true;
    }
    return false;
    }
}