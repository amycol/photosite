<?php
    require __DIR__ . '/utils.php';
    $name = $desc = ""; //Define variables
    $nameErr = false;   //Define specific error variables
    
    if (($_SERVER["REQUEST_METHOD"] == "POST") && !(isset($_POST['clear']))) {
        //Check required fields and format inputs for security
        if (empty($_POST["name"])) {
            $emptyErr = "Required field(s) left empty";
            $nameErr = true;
        } else {
            $name = formatInput($_POST["name"]);
        }

        //Format other inputs for security
        $desc = formatInput($_POST["desc"]);
        
        //If no errors
        if (empty($emptyErr)) {
            //Write shell command
            $cmd = "/usr/local/bin/photosite addcat --name=\"$name\" --desc=\"$desc\"";
            //Run Shell Command
            exec($cmd, $out);
            //Show output
            $outStyle = "style=\"display:inline\"";
        }
    } else {
        $name = $desc = ""; //Reset variables
    }
?>


<!DOCTYPE html>

<head>
    <title>Categories | photosite</title>
    <link rel="stylesheet" href="css/tables.css">
</head>

<body>
    <div class="tabs"><?php include_once("php/tablist.php") ?></div>
    
    <form action="php/forms/delete.php" method="post">
        <div class="table">
            <div class="tr">
                <span class="ts"></span>
                <span class="th id">ID</span>
                <span class="th">Category</span>
                <span class="th">Description</span>
            </div>
            <?php
                echo newGenTable("categories");
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