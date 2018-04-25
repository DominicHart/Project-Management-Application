<?php
include_once ('../classes/task.php');
if ($_REQUEST['js_submit_value']) {
    $task = $_REQUEST['js_submit_value'];
}
if (strpos($task, ',') !== false) {
    $split = explode(',', $task);
    $taskID = $split[0];
    $musername = $split[1];
    $task = new task();
    $task->getThisTaskDelete($taskID, $milestoneID);
}