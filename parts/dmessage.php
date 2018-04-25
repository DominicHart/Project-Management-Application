<?php
include_once ('../classes/message.php');
if ($_REQUEST['js_submit_value']) {
    $ids = $_REQUEST['js_submit_value'];
}
$message = new message();
$message->getMessageDelete($ids);