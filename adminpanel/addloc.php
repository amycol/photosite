<?php require 'php/generators.php';?>
<!DOCTYPE html>

<head>
    <title>Locations | photosite</title>
    <link rel="stylesheet" href="css/tablist.css">
    <link rel="stylesheet" href="css/tables.css">
    <link rel="stylesheet" href="css/forms.css">
</head>

<body>
    <?php include_once("php/tablist.php") ?></div>
    <?php echo genTable("locations");?>
    <br><br>
    <?php echo genAddForm("locations", "addloc");?>
</body>