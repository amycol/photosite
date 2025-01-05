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
    <title>Test page</title>
</head>

<body>
    <div class="tabs"><?php include_once("php/tablist.php") ?></div>
    <div class="content">
        <p>Add Category</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="name">Category name:</label><br>
            <input type="text" name="name" value="<?php echo $name;?>" <?php if($nameErr){echo $errCSS;}?>><br>
            <label for="desc">Category description:</label><br>
            <input type="text" name="desc" value="<?php echo $desc;?>"><br>
            
            <input type="submit" name="clear" value="Clear">
            <input type="submit" name="submit" value="Submit">
        </form>
        <p <?php echo $outStyle; ?>class="output">
            Result: <?php echo $out[0]; ?><br>
            ID: <?php echo $out[1]; ?>
        <pre></pre>
        </p>
    </div>
</body>