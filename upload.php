<?php

require_once "./models/Images.php";

$image = new Images();

/// Provera velicine slike (maksimalna dozvoljena velicina slike je 2MB) ///
$imageSize = $_FILES['profileImage']['size'];

if(!$image->isValidSize($imageSize)){
    die("Slika je prevelika! Maksimalna dozvoljena velicina slike je 2MB.");
}

/// Provera formata slike (jpeg, jpg, png, gif) ///
$imageType = pathinfo($_FILES['profileImage']['name'], PATHINFO_EXTENSION);

if(!$image->isValidExtension($imageType)){
    die("Format slike nije dobar, mora biti: ".implode(', ', Images::ALLOWED_EXTENSIONS));
}

/// Provera maksimalne sirine i visine slike (1920x1024) ///
list($imageWidth, $imageHeight) = getimagesize($_FILES['profileImage']['tmp_name']);

if(!$image->isValidDimensions($imageWidth, $imageHeight)){
    die("Maksimalna dozvoljena sirina slike je 1920px, a maksimalna dozvoljena visina slike je 1024px.");
}

// Generisanje jedinstvenog imena slike ///
$randomName = $image->generateRandomName($imageType);

if(!is_dir('./uploads')){
    mkdir('./uploads', permissions: 0755, recursive: true);
}

// Upload slike i cuvanje imena slike u bazi podataka ///
if(!$image->upload($_FILES['profileImage']['tmp_name'], $randomName, "uploads")) {
    die ("Upload slike nije uspeo!");
} else {
    echo "Uspesan upload slike!";
}