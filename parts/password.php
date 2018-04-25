<?php
include '../includes/session.php';
if($access == 1) {
    include_once('../classes/customer.php');
    $customer = new customer();
    $customer->changePassword();
}
else {
    include_once('../classes/user.php');
    $user = new user();
    $user->changePassword();
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/forms.js"></script>