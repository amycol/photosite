<?php require 'php/generators.php';?>
<!DOCTYPE html>

<head>
    <title>Photos | photosite</title>
    <link rel="stylesheet" href="css/tables.css">
    <link rel="stylesheet" href="css/forms.css">
</head>

<body>
    <?php include_once("php/tablist.php") ?></div>
    <?php echo genTable("digitalPhotos");?>
    <br><br>
    <?php echo genAddForm("digitalPhotos", "addimg");?>
</body>