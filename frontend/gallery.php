<?php include 'php/gallery.php';?>
<!DOCTYPE html>

<head>
    <title>Gallery | photosite</title>
    <link rel="stylesheet" href="css/gallery.css">
</head>

<body>
    
    <div class="main-container">
        <div class="titlebar">
            <h1><a href="gallery">Photosite<a></h1>
            <div><a href="/adminpanel/addimg">admin</a></div>
        </div>
        <div class="sidebar">
            <?php 
                echo $locationsList;
                echo $datesList;
                echo $categoryList; 
                echo $cameraList; 
                echo $lensList; 
            ?>
        </div>
        
        <div class="gallery-container">
            <ul class="gallery">
                <?php foreach ($imageshtml as $imagehtml) {echo $imagehtml;} ?>
            </ul>
        </div>
    </div>
</body>