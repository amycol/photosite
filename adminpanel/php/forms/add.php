<?php
    require '../../utils.php';

    if (isset($_POST)) // Check if form data exists
    {
        if (isset($_POST["clear"]))
        {
            // Send user back to previous page
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
        
        $cmd = "";
        // Iterate through fields
        foreach ($_POST as $field) {
            if ($field != "Submit") {
                // Add ID to list
                $cmd = $cmd . " -" . formatInput(array_search($field, $_POST)) . "=" . formatInput($field);
            } else {
                // Get key (element name), add to start of command string
                $cmd = formatInput(array_search($field, $_POST)) . " " . $cmd;
                // Write and execute shell command
                $cmd = "/usr/local/bin/photosite " . $cmd;
                exec($cmd, $out);
            }
        }
    }
    // Send user back to previous page
    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>