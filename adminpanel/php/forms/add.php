<?php
    require '../utils.php';

    function imgUpload($files) {
        $targetDir = "/opt/photosite/uploads/";

        // Iterate through uploaded files
        while ($file = current($files)) {
            $targetFile = $targetDir . basename($file['name']);
            move_uploaded_file($file['tmp_name'], $targetFile);
            next($files);
        }
    }

    function imgProc($files, $post){
        $targetDir = "/opt/photosite/uploads/";
        //run photosite on images with args from form
        while ($file = current($files)) {
            $targetFile = $targetDir . basename($file['name']);
            $cmd = writeCmd($post);
            $cmd = $cmd . " -img=\"" . $targetFile . "\"";
            echo "files " . $cmd;
            exec($cmd, $out);
            echo $cmd;
            next($files);
        }
    }

    function writeCmd($post) {
        $post = array_filter($post); // Remove empty values from array
        $cmd = "";
        // Iterate through fields
        while ($field = current($post)) {
            if ($field != "Submit") {
                // Add ID to list
                $cmd = $cmd . " -" . strtolower(formatInput(key($post))) . "=\"" . formatInput($field) . "\"";
            } else {
                // Get key (element name), add to start of command string
                $cmd = formatInput(array_search($field, $post)) . " " . $cmd;
                // Write and execute shell command
                $cmd = "/usr/local/bin/photosite " . $cmd;
            }
            next($post);
        }
        return $cmd;
    }

    if (isset($_POST)) // Check if form data exists
    {
        if (isset($_POST["clear"]))
        {
            // Send user back to previous page
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
        print_r($_FILES);
        if (!empty($_FILES)) // Check if files have been uploaded
        {
            imgUpload($_FILES);
            imgProc($_FILES, $_POST);
        } else {
            $cmd = writeCmd($_POST);
            exec($cmd, $out);
            echo $cmd;
        }
    }
    // Send user back to previous page
    //header('Location: ' . $_SERVER['HTTP_REFERER']);
?>