<?php 
    require 'utils.php';
    
    function genFilterLink($key, $value) {
        $newParameters = $_GET;
        $newParameters[$key] = $value;
        $httpQuery = http_build_query($newParameters);
        return "gallery?" . $httpQuery;
    }

    function genComplexFilterLink($key, $array) {
        $newParameters = $_GET;
        $newParameters[$key] = json_encode($array);
        $httpQuery = http_build_query($newParameters);
        return "gallery?" . $httpQuery;
    }

    function genFilterList($table, $labelColumn, $title, $key) {
        $list = "<ul class=\"filter-tree\"><p class=\"tree-title\">" . $title . "</p>";
        $dbres = dbRead($table, '*', "");
        if ($dbres->num_rows > 0) {
            // Iterate through rows 
            while($dbrow = $dbres->fetch_assoc()) {
                $list = $list . "<li><a href=\"" . genFilterLink($key, $dbrow['id']) .  "\">" . $dbrow[$labelColumn] . "</a></li>";
            }
        }
        $list = $list . "</ul>";
        return $list;
    }

    function genLocationFilterList($data, $title, $key) {
        $list = "<ul class=\"filter-tree\"><p class=\"tree-title\">" . $title . "</p>";
            // Iterate through countries/years 
            while ($lvl1 = current($data)) {
                $list = $list . "<li>" . "<a href=\"" . genComplexFilterLink($key, array("country"=>key($data))) .  "\">" . key($data) . "</a><ul>";
                // Iterate through cities/months
                while ($lvl2 = current($lvl1)) {
                    $list = $list . "<li>" . "<a href=\"" . genComplexFilterLink($key, array("country"=>key($data), "city"=>key($lvl1))) .  "\">" . key($lvl1) . "</a><ul>";
                    //Iterate through firstLine/days
                    while ($lvl3 = current($lvl2)) {
                        $list = $list . "<li>" . "<a href=\"" . genComplexFilterLink($key, array("country"=>key($data), "city"=>key($lvl1), "firstLine"=>$lvl3)) .  "\">" . $lvl3 . "</a>" . "</li>";
                        next($lvl2);
                    }
                        $list = $list . "</ul></li>";
                        next($lvl1);
                }
                $list = $list . "</ul></li>";
                next($data);
            }
        $list = $list . "</ul>";
        return $list;
    }

    function genDateFilterList($data, $title, $key) {
        $months = ["01"=>"Jan", "02"=>"Feb", "03"=>"Mar", "04"=>"Apr", "05"=>"May", "06"=>"Jun", "07"=>"Jul", "08"=>"Aug", "09"=>"Sep", "10"=>"Oct", "11"=>"Nov", "12"=>"Dec"];
        echo $months["3"];
        $list = "<ul class=\"filter-tree\"><p class=\"tree-title\">" . $title . "</p>";
            // Iterate through countries/years 
            while ($lvl1 = current($data)) {
                $list = $list . "<li>" . "<a href=\"" . genFilterLink($key, key($data)) .  "\">" . key($data) . "</a><ul>";
                // Iterate through cities/months
                while ($lvl2 = current($lvl1)) {
                    $list = $list . "<li>" . "<a href=\"" . genFilterLink($key, key($data) . "-" . key($lvl1)) .  "\">" . $months[key($lvl1)] . "</a><ul>";
                    //Iterate through firstLine/days
                    while ($lvl3 = current($lvl2)) {
                        $list = $list . "<li>" . "<a href=\"" . genFilterLink($key, key($data) . "-" . key($lvl1) . "-" . $lvl3) .  "\">" . $lvl3 . "</a></li>";
                        next($lvl2);
                    }
                        $list = $list . "</ul></li>";
                        next($lvl1);
                }
                $list = $list . "</ul></li>";
                next($data);
            }
        $list = $list . "</ul>";
        return $list;
    }
    
    function genLocationArray() {
        $locations = array();
        $dbres = dbRead('locations', 'country, city, firstLine', "");
        if ($dbres->num_rows > 0) {
            // Iterate through rows 
            while($dbrow = $dbres->fetch_row()) {
                if (!array_key_exists($dbrow[0], $locations)) {
                    $locations[$dbrow[0]] = array();
                }
                if (!array_key_exists($dbrow[1], $locations[$dbrow[0]])) {
                    $locations[$dbrow[0]][$dbrow[1]] = array();
                }
                if ($dbrow[2]) {
                    $locations[$dbrow[0]][$dbrow[1]][] = $dbrow[2];
                }
            }
        }
        return $locations;
    }
    function genDateArray() {
        $dates = array();
        $dbres = dbRead('digitalPhotos', 'date', "");
        if ($dbres->num_rows > 0) {
            // Iterate through rows 
            while($dbrow = $dbres->fetch_row()) {
                $exDate = explode("-", $dbrow[0]);
                if (!array_key_exists($exDate[0], $dates)) {
                    $dates[$exDate[0]] = array();
                }
                if (!array_key_exists($exDate[1], $dates[$exDate[0]])) {
                    $dates[$exDate[0]][$exDate[1]] = array();
                }
                $dates[$exDate[0]][$exDate[1]][] = $exDate[2];
            }
        }
        return $dates;
    }

    $parameters = $_GET;
    $where = urlToQuery($parameters);
    $dbres = dbRead("digitalPhotos", "*", $where);
    if ($dbres->num_rows > 0) {
        // Iterate through rows 
        while($dbrow = $dbres->fetch_assoc()) {
            $ids[] = $dbrow['id'];
        }
    }
    //print_r($parameters);
    $imageshtml = array();
    foreach ($ids as $id) {
        $id = str_pad($id, 8, "00000000", STR_PAD_LEFT);
        $imageshtml[] = "<li><a href=\"http://pstest.local:5503/frontend/viewer?id=" . $id . "&" . http_build_query($parameters) . "\"><img src=\"http://img.pstest.local:5503/thumb/" . $id . "t.webp\"></a></li>";
    }

    $categoryList = genFilterList("categories", "name", "Categories", "categoryID");
    $cameraList = genFilterList("digitalCameras", "model", "Cameras", "cameraID");
    $lensList = genFilterList("lenses", "name", "Lenses", "lensID");
    $locations = genLocationArray();
    $locationsList = genLocationFilterList($locations, "Locations", "location");
    $dates = genDateArray();
    $datesList = genDateFilterList($dates, "Dates", "date");
    //echo "<pre>"; print_r($locationsList); print_r($locationsList); echo "</pre>";
?>