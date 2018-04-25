<?php
include_once ('../classes/risk.php');
if ($_REQUEST['js_submit_value']) {
    $title = $_REQUEST['js_submit_value'];
}
if (strpos($title, ',') !== false) {
    $split = explode(',', $title);
    $risktitle = $split[0];
    $id = $split[1];
    $risk = new risk();
    $risk->getRiskDetails($risktitle, $id);
}
else {
    header('location:../fyp/404.php');
}