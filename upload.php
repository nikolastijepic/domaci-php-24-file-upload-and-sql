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

exit();

$connection = mysqli_connect("localhost", "root", "ServBay.dev", "php23");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if(!isset($_FILES['profileImage'])){
    die('Niste prosledili profilnu sliku!');
}

// Provera velicine slike ///
$imageSize = $_FILES['profileImage']['size'];

$maxFileSize = 2 * 1024 * 1024;

if($imageSize > $maxFileSize){
    die("Slika je prevelika! Maksimalna dozvoljena velicina je 2MB.");
}

// Provera maksimalne rezolucije slike (1920x1024) ///
list($imageWidth, $imageHeight) = getimagesize($_FILES['profileImage']['tmp_name']);

if($imageWidth > 1920 || $imageHeight > 1024){
    die("Maksimalna dozvoljena rezolucija slike je 1920x1024.");
}

// Provera formata slike ///
$allowedExtensions = ['jpeg', 'jpg', 'png', 'gif'];

$imageType = pathinfo($_FILES['profileImage']['name'], PATHINFO_EXTENSION);

if(!in_array($imageType, $allowedExtensions)){
    die("Format slike nije dobar, mora biti: ".implode(', ', $allowedExtensions));
}

// Generisanje imena slike ///
$imageName = time().".".$imageType;

$finalPath = "./uploads/$imageName";
$tmpFileName = $_FILES['profileImage']['tmp_name'];

if(!is_dir('./uploads')){
    mkdir('./uploads', permissions: 0755, recursive: true);
}

$imageUploaded = move_uploaded_file($tmpFileName, $finalPath);

if($imageUploaded){
    $imageName = $connection->real_escape_string($imageName);
    $connection->query("INSERT INTO images (name) VALUES ('$imageName')");
    die("Slika je uspesno uploadovana!");
} else {
    die("Doslo je do greske prilikom uploadovanja slike!");
}