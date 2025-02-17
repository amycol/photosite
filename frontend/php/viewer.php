<?php
    require 'utils.php';
    require 'timeDesc.php';
    
    $id = htmlspecialchars($_GET["id"]);
    
    $parameters = $_GET;
    $where = urlToQuery($parameters);
    $dbres = dbRead("digitalPhotos", "*", $where);
    if ($dbres->num_rows > 0) {
        // Iterate through rows 
        while($dbrow = $dbres->fetch_assoc()) {
            $ids[] = $dbrow['id'];
        }
    }
    $key = array_search($id, $ids);
    
    unset($parameters['id']);
    $exitUrl = "http://pstest.local:5503/frontend/gallery?" . http_build_query($parameters);
    if ($key > 0) {
        $leftButton = "<a href=\"?id=" . str_pad($ids[$key-1], 8, "00000000", STR_PAD_LEFT) . "&" . http_build_query($parameters) . "\"><</a>";
    }
    if ($key < end($ids)-1) {
        $rightButton = "<a href=\"?id=" . str_pad($ids[$key+1], 8, "00000000", STR_PAD_LEFT) . "&" . http_build_query($parameters) . "\">></a>";
    }

    $dbres = dbRead("digitalPhotos", "*", "WHERE id=" . $id);
        if (!$dbres->num_rows > 0) {
            exit(1);
        }
    $dbrow = $dbres->fetch_assoc();
    // Remove unnecessary decimals (e.g. 8.00 --> 8)
    $dbrow = preg_replace("/\.00/", "", $dbrow);
    // Save date and time in variables for time description later
    $d = $dbrow['date'];
    $t = $dbrow['time'];
    // Rebuild shutter speed formatting
    if ($dbrow['shutterSpeedDenominator'] == 1) {
        $shutter = $dbrow['shutterSpeedNumerator'];
    } else {
        $shutter = $dbrow['shutterSpeedNumerator'] . "/" . $dbrow['shutterSpeedDenominator'];
    }
    // Set other variables
    $aperture = "F" . $dbrow['aperture'];
    $iso = $dbrow['iso'];
    $focallength = $dbrow['focalLength'] . "mm";
    $time = substr($dbrow['time'], 0, 5);
    $dateobj = date_create($dbrow['date']);
    $date = date_format($dateobj, "d-m-Y");
    $extrainfotag = "<p class=\"extrainfo\">". $dbrow['extraInfo'] . "</p>";

    // Set variables from other database tables
    $cameraid = $dbrow['cameraID'];
    $lensid = $dbrow['lensID'];
    $categoryid = $dbrow['categoryID'];
    $locationid = $dbrow['locationID'];

    $dbres = dbRead("digitalCameras", "*", "WHERE id=" . $cameraid);
        if (!$dbres->num_rows > 0) {
            exit(1);
        }
    $dbrow = $dbres->fetch_assoc();
    
    $camera = $dbrow['model'];
    $resolution = $dbrow['imageWidth'] . "x" . $dbrow['imageHeight'];

    $dbres = dbRead("lenses", "*", "WHERE id=" . $lensid);
        if (!$dbres->num_rows > 0) {
            exit(1);
        }
    $dbrow = $dbres->fetch_assoc();
    
    $lens = $dbrow['name'];

    $dbres = dbRead("categories", "*", "WHERE id=" . $categoryid);
        if (!$dbres->num_rows > 0) {
            exit(1);
        }
    $dbrow = $dbres->fetch_assoc();
    
    $category = $dbrow['name'];

    $dbres = dbRead("locations", "*", "WHERE id=" . $locationid);
        if (!$dbres->num_rows > 0) {
            exit(1);
        }
    $dbrow = $dbres->fetch_assoc();
    
    $location = $dbrow['firstLine'];
    if (strlen($dbrow['secondLine']) > 1) {
        $location = $dbrow['firstLine'] . ", " . $dbrow['secondLine'] . ", " . $dbrow['city'];
    } elseif (strlen($dbrow['firstLine']) > 1) {
        $location = $dbrow['firstLine'] . ", " . $dbrow['city'];
    } elseif (strlen($dbrow['city']) > 1) {
        $location = $dbrow['city'] . ", " . $dbrow['country'];
    } else {
        $location = $dbrow['country'];
    }
    
    $tz = $dbrow['timezone'];

    $timedesc = timeDesc($dbrow['latitude'], $dbrow['longitude'], $d, $t, $tz);




    //Array ( [id] => 10 [cameraID] => 4 [lensID] => 4 [shutterSpeedNumerator] => 1.00 [shutterSpeedDenominator] => 30.00 [aperture] => 8.00 [iso] => 100 [focalLength] => 50 [flash] => 0 [time] => 20:31:56 [date] => 2024-05-23 [locationID] => 4 [mode] => Manual [categoryID] => 24 [oldFilename] => img.tif [extraInfo] => ) 
?>