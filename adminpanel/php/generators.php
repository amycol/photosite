<?php
require 'utils.php';

function genTimezoneOptions()
{
    $options = "";
    $timezones = DateTimeZone::listIdentifiers();
    foreach ($timezones as $timezone) {
        $options = $options . "<option value=\"" . $timezone . "\">" . $timezone . "</option>";
    }
    return $options;
}

function genCell($data)
    {
        $cell = "<span class=\"td\" title=" . $data . ">" . $data . "</span>";
        return $cell;
    }

function genTableHeaders($table) 
    {
        $headers = "<div class=\"tr\"><span class=\"ts\"></span>";
        $dbres = getColumns($table);
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
        $start = "<form action=\"php/forms/delete.php\" method=\"post\"><div class=\"table\">";
        $end = "</div><input class=\"del-button\" type=\"submit\" name=\"" . $table . "\" value=\"Delete Selected\"></form>";

        $dbres = dbRead($table, "*", "");
        $rows = "";
        if ($dbres->num_rows > 0) {
            // Iterate through rows 
            while($dbrow = $dbres->fetch_assoc()) {
                // Generate the first two cells (selector checkbox & id)
                $id = $dbrow["id"];
                $selectorcell = "<span class=\"ts\"><input class=\"checkbox\" type=\"checkbox\" id=" . $id . " name=" . $id . " value=" . $id . "></span>";
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
        $out = $start . $headers . $rows . $end;
        return $out;
    }

function genUploadForm($table) {
    $uploadTables = array("digitalCameras", "digitalPhotos", "lenses");
    $field = "";
    if (in_array($table, $uploadTables)) {
        return "<input class=\"file-upload\" type=\"file\" id=\"imgFile\" name=\"imgFile\">";
    } else {
        return "";
    }
    return $field;
}

function genAddForm($table, $subcmd) 
    {
        $start = "<form action=\"php/forms/add.php\" method=\"post\" enctype=\"multipart/form-data\"><div class=\"addForm\">";
        $end = "</div></form>";

        // Generate title
        $title = "<h3 class=\"formHeader\">Add to " . ucfirst($table) . "</h3>";
        $form = $title;

        $dbres = getColumns($table);
        if ($dbres->num_rows > 0) {
            // Iterate through rows 
            $i = 0;
            while($dbrow = $dbres->fetch_assoc()) {
                $fieldname = $dbrow["COLUMN_NAME"];
                if (($fieldname != "id") && ($fieldname != "timezone")) {
                    $formField = "
                    <div class=\"formField\">
                    <label for=" . "\"$fieldname\"" . ">" . ucfirst($fieldname) . ":</label><br>
                    <input type=\"text\" name=" . "\"$fieldname\"" . "><br></div>";
                    if ($i % 2 == 1) {
                        $formField = $formField . "<div class=\"formNewLine\"></div>";
                    }
                    $form = $form . $formField;
                    $i = $i + 1;
                } elseif ($fieldname == "timezone") {
                    $formField = "
                    <div class=\"formField\">
                    <label for=" . "\"$fieldname\"" . ">" . ucfirst($fieldname) . ":</label><br>
                    <select name=" . "\"$fieldname\"" . ">" .
                    genTimezoneOptions() .
                    "</select><br></div>";
                    if ($i % 2 == 1) {
                        $formField = $formField . "<div class=\"formNewLine\"></div>";
                    }
                    $form = $form . $formField;
                    $i = $i + 1;
                }
            }
        }
        $buttons = "
        <div class=\"formNewLine\"></div>
        <div class=\"formButtons\">
        <input type=\"submit\" name=\"clear\" value=\"Clear\">
        <input type=\"submit\" name=\"" . $subcmd . "\" value=\"Submit\">
        </div>";
        $fileUpload = genUploadForm($table);
        $form = $start . $form . $fileUpload . $buttons . $end;
        return $form;
    }
?>