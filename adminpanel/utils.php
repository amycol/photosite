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
    
function dbRead($table, $selector)
    {
        $db = dbConnect();
    
        // Write & execute DB query 
        $sql = "SELECT * FROM categories;";
        $dbres = $db->query($sql);
        
        // Close database connection
        $db->close();
    
        return $dbres;
    }

function getTableHeaders($table)
    {
        $db = dbConnect();
    
        // Write & execute DB query 
        $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'photosite' AND TABLE_NAME = '" . $table . "';";
        $dbres = $db->query($sql);
        
        // Close database connection
        $db->close();
    
        return $dbres;
    }

function genCell($data)
    {
        $cell = "<span class=\"td\" title=" . $data . ">" . $data . "</span>";
        return $cell;
    }

function genTableHeaders($table) 
    {
        $headers = "<div class=\"tr\"><span class=\"ts\"></span>";
        $dbres = getTableHeaders($table);
        if ($dbres->num_rows > 0) {
            // Iterate through rows 
            while($dbrow = $dbres->fetch_assoc()) {
                $header = $dbrow["COLUMN_NAME"];
                if ($header == "id") {
                    $headers = $headers . "<span class=\"th id\">ID</span>";
                } else {
                    $header = ucfirst($header);
                    $headers = $headers . "<span class=\"th\">" . $header . "</span>";
                }
            }
        }
        $headers = $headers . "</div>";
        return $headers;
    }

function genTable($table)
    {
        $dbres = dbRead($table, "*");
        $rows = "";
        if ($dbres->num_rows > 0) {
            // Iterate through rows 
            while($dbrow = $dbres->fetch_assoc()) {
                // Generate the first two cells (selector checkbox & id)
                $id = $dbrow["id"];
                $selectorcell = "<span class=\"ts\"><input type=\"checkbox\" id=" . $id . " name=" . $id . " value=" . $id . "></span>";
                $idcell = "<span class=\"td id\">" . $id . "</span>";
                $f2cells = $selectorcell . $idcell;
    
                $row = "<div class=\"tr\">" . $f2cells;
                $isFirst = true;
                foreach ($dbrow as $data) {
                    if (!$isFirst) {
                        $cell = genCell($data);
                        $row = $row . $cell;
                    }
                    $isFirst = false;
                }
                $row = $row . "</div>";
                $rows = $rows . $row;
            }
        }
        $headers = genTableHeaders($table);
        $out = $headers . $rows;
        return $out;
    }
?>
<!-- PHP Stuff
     - Check if longer than x
        - If longer than x, add title tag for hover tooltip 
-->