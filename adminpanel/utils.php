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
?>