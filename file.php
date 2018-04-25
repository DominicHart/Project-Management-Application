<?php
if(isset($_GET['name'])) {
    $filename = $_GET['name'];
    $file = "files/projects/" . $filename;
#setting headers
    header('Content-Description: File Transfer');
    header('Cache-Control: public');
    header('Content-Type: ' . $type);
    header("Content-Transfer-Encoding: binary");
    header('Content-Disposition: attachment; filename=' . basename($file));
    header('Content-Length: ' . filesize($file));
    ob_clean(); #THIS!
    flush();
    readfile($file);
}
else {
    header('location:files.php');
}
