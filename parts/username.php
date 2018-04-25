<?php
include_once ('../classes/customer.php');
if ($_REQUEST['js_submit_value']) {
    $name = $_REQUEST['js_submit_value'];
    $customer = new customer();
    if(isset($name)) { $customer->checkUsername($name); }
}