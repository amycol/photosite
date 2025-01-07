<?php require __DIR__ . '/utils.php';?>
<!DOCTYPE html>

<head>
    <title>Categories | photosite</title>
    <link rel="stylesheet" href="css/tables.css">
</head>

<body>
    <div class="tabs"><?php include_once("php/tablist.php") ?></div>
    
    <form action="php/forms/delete.php" method="post">
        <div class="table">
            <?php
                echo genTable("categories");
            ?>
        </div>
    <input type="submit" name="categories" value="Delete Selected">
    </form>

    <div class="content">
        <p>Add Category</p>
        <form action="php/forms/add.php" method="post">
            <label for="name">Category name:</label><br>
            <input type="text" name="name" value="<?php echo $name;?>" <?php if($nameErr){echo $errCSS;}?>><br>
            <label for="desc">Category description:</label><br>
            <input type="text" name="desc" value="<?php echo $desc;?>"><br>
            
            <input type="submit" name="clear" value="Clear">
            <input type="submit" name="addcat" value="Submit">
        </form>
        <p <?php echo $outStyle; ?>class="output">
            Result: <?php echo $out[0]; ?><br>
            ID: <?php echo $out[1]; ?>
        <pre></pre>
        </p>
    </div>
</body>