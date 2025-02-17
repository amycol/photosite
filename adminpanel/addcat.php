<?php require 'php/generators.php';?>
<!DOCTYPE html>

<head>
    <title>Categories | photosite</title>
    <link rel="stylesheet" href="css/tablist.css">
    <link rel="stylesheet" href="css/tables.css">
    <link rel="stylesheet" href="css/forms.css">
</head>

<body>
    <?php include_once("php/tablist.php") ?></div>
    <?php echo genTable("categories");?>
    <br><br>
    <?php echo genAddForm("categories", "addcat");?>
</body>