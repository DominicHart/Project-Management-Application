<?php
include_once ('../classes/milestone.php');
if ($_REQUEST['js_submit_value']) {
    $milestone = $_REQUEST['js_submit_value'];
}
if (strpos($milestone, ',') !== false) {
    $split = explode(',', $milestone);
    $milestoneID = $split[0];
    $projectID = $split[1];
    $milestone = new milestone();
    $milestone->getThisMilestoneDelete($milestoneID, $projectID);
}