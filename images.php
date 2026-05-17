<?php

require_once "./models/Db.php";

$db = new Db();

$connection = $db->connection;

$images = "SELECT image FROM images";

$result = mysqli_query($connection, $images);

$images = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php foreach($images as $image): ?>
        <img src="./uploads/<?php echo $image['image']; ?>" alt="Slika" style="max-width: 200px; max-height: 200px;">
    <?php endforeach; ?>
</body>
</html>