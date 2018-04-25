<?php
include_once ('../classes/project.php');
if ($_REQUEST['js_submit_value']) {
    $file = $_REQUEST['js_submit_value'];
}
if (strpos($file, ',') !== false) {
    $split = explode(',', $file);
    $projID = $split[0];
    $filename = $split[1];
    $project = new project();
    $project->getThisFileDelete($projID, $filename);
}