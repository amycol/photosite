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
        $sql = "SELECT " . $selector . " FROM " . $table . " " . $where . ";";
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


function writeIDQuery($val, $fieldname) {
    $val = htmlspecialchars($val);
    if ($val) {
        return " " . $fieldname . "=" . $val;
    }
}

function writeLocationQuery($values) {
    if (array_key_exists('country', $values)) {
        if (array_key_exists('city', $values)) {
            if (array_key_exists('firstLine', $values)) {
                // Country, city, and firstline are all set
                $where = "WHERE country=\"" . $values['country'] . "\" AND city=\"" . $values['city'] . "\" AND firstLine=\"" . $values['firstLine'] . "\"";
            } else {
                // Country and city are set but not firstline
                $where = "WHERE country=\"" . $values['country'] . "\" AND city=\"" . $values['city'] . "\"";
            }
        } else {
            // Only country is set
            $where = "WHERE country=\"" . $values['country'] . "\"";
        }
    }
    $dbres = dbRead("locations", "id", $where);
    $locIDs2D = $dbres->fetch_all();
    $locIDs = array();
    foreach ($locIDs2D as $locID){
        $locIDs[] = $locID[0];
    }
    return " locationID IN (" . implode("," , $locIDs) . ")";
}

// Writes "WHERE" part of SQL query from URL arguments
function urlToQuery($args) 
{
    $queryParts = array();
    while (current($args)) {
        if (in_array(key($args), array("categoryID", "cameraID", "lensID"))) {
            $queryParts[] = writeIDQuery(current($args), key($args));
        } elseif (key($args) == "location"){
            $queryParts[] = writeLocationQuery(json_decode(current($args), true));
        } elseif (key($args) == "date") {
            $queryParts[] = " date LIKE \"" . current($args) . "%\"";
        }
        next($args);
    }
    if ($queryParts) {
        $query = "WHERE" . implode(" AND" , $queryParts);
    } else {
        $query = "";
    }
    return $query;
}

//http://pstest.local:5503/frontend/gallery?categoryID=[1,2]&lensID=[1,2]&cameraID=[1]&date=2024&location={"country":"England","city":"Reading","firstLine":"The Oracle"}
?>
<!-- PHP Stuff
     - Check if longer than x
        - If longer than x, add title tag for hover tooltip 
-->