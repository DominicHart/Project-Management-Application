<?php
include_once ('../classes/transaction.php');
if ($_REQUEST['js_submit_value']) {
    $id = $_REQUEST['js_submit_value'];
}
$transaction = new transaction();
$transaction->getThisTransactionDelete($id);