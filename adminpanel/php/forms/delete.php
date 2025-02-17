<?php
    if (isset($_POST)) // Check if form data exists
    {
        $cmd = "";
        // Iterate through fields
        foreach ($_POST as $field) {
            if (is_numeric($field)) {
                // Add ID to array and command
                $cmd = $cmd . " " . $field;
                
            } else {
                // Get key (element name), add to start of command string
                $cmd = array_search ($field, $_POST) . " " . $cmd;
                // Write and execute shell command
                $cmd = "/usr/local/bin/photosite del -table=" . $cmd;
                exec($cmd, $out);
            }
        }
    }
    // Send user back to previous page
    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>