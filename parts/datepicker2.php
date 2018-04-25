<?php
include_once ('../classes/milestone.php');
if ($_REQUEST['js_submit_value']) {
    $id = $_REQUEST['js_submit_value'];
}
$milestone = new milestone();
$milestone->getMilestoneDates($id);