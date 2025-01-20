<?php require 'php/generators.php';?>
<!DOCTYPE html>

<head>
    <title>Cameras | photosite</title>
    <link rel="stylesheet" href="css/tables.css">
    <link rel="stylesheet" href="css/forms.css">
</head>

<body>
    <?php include_once("php/tablist.php") ?></div>
    <?php echo genTable("digitalCameras");?>
    <br><br>
    <?php echo genAddForm("digitalCameras", "addcam");?>
</body>