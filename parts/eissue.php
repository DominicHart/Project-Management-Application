<?php
include_once ('../classes/project.php');
if ($_REQUEST['js_submit_value']) {
    $issue = $_REQUEST['js_submit_value'];
}
if (strpos($issue, ',') !== false) {
    $split = explode(',', $issue);
    $issueID = $split[0];
    $projectID = $split[1];
    $project = new project();
    $project->getIssueDetails($issueID, $projectID);
}