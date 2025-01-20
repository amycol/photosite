<?php
//Define common variables
$err = "";          //Define error variable
$errCSS = "style=\"border:2px solid red;\"";    //Define error CSS (Will be applied to fields containing errors)
$outStyle = "style=\"display:none\"";         //Define output CSS (Will be changed to display:inline when output exists)

function formatInput($str)
    {
        $str = trim($str);
        $str = stripslashes($str);
        $str = htmlspecialchars($str);
        return $str;
    }

function dbConnect()
    {
        $servername = "localhost";
        $username = "photosite-php";
        $password = "KbQ9wujH8rgXvVCGAcfkzN";
    
        // Connect to database
        $db = new mysqli($servername, $username, $password);
    
        // Check database connection
        if ($db->connect_error) {
            die("Database connection failed: " . $db->connect_error);
        }
    
        // Select database
        $db->query("USE photosite;");
    
        return $db;
    }
    
function dbRead($table, $selector, $where)
    {
        $db = dbConnect();
    
        // Write & execute DB query 
        $sql = "SELECT " . $selector . " FROM " . $table . $where . ";";
        $dbres = $db->query($sql);
        
        // Close database connection
        $db->close();
    
        return $dbres;
    }

function dbWrite($table, $columns, $data)
    {
        $db = dbConnect();
    
        // Write & execute DB query 
        $sql = "INSERT INTO " . $table . " (" . $columns . ") VALUES (" . $data . ");";
        $dbres = $db->execute_query($sql);
        if (!$dbres) {
            // Close database connection
            $db->close();
            return false;
        } else {
            echo '<pre>'; print_r($dbres); echo '</pre>';
            return $dbres;
        }
    }

function getColumns($table)
    {
        $db = dbConnect();
    
        // Write & execute DB query 
        $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'photosite' AND TABLE_NAME = '" . $table . "';";
        $dbres = $db->query($sql);
        
        // Close database connection
        $db->close();
    
        return $dbres;
    }
?>
<!-- PHP Stuff
     - Check if longer than x
        - If longer than x, add title tag for hover tooltip 
-->