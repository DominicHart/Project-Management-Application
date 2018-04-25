<?php
include_once ('../classes/customer.php');
if ($_REQUEST['js_submit_value']) {
    $email = $_REQUEST['js_submit_value'];
    $customer = new customer();
    if(isset($email)) { $customer->checkEmail($email); }
}