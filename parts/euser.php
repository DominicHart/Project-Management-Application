<?php
include_once ('../classes/user.php');
if ($_REQUEST['js_submit_value']) {
    $username = $_REQUEST['js_submit_value'];
}
$user = new user();
$user->getUserDetails($username);