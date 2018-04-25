<?php
include_once ('../classes/task.php');
if ($_REQUEST['js_submit_value']) {
    $task = $_REQUEST['js_submit_value'];
}
if (strpos($task, ',') !== false) {
    $split = explode(',', $task);
    $taskID = $split[0];
    $milestoneID = $split[1];
    $task = new task();
    $task->getTaskDetails($taskID, $milestoneID);
}