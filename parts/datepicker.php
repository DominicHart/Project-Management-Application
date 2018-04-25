<?php
include_once ('../classes/project.php');
if ($_REQUEST['js_submit_value']) {
    $id = $_REQUEST['js_submit_value'];
}
$project = new project();
$project->getProjectDates($id);
