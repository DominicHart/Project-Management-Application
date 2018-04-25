<?php
include 'includes/session.php';
if(isset($thisuser)) {
    if($access == 1) {
        header('location:client.php');
    } else if ($access == 2) {
        header('location:projects.php');
    } else {
        header('location:admin.php');
    }
}
include 'parts/header.php';
?>
<div class="reset">
    <div class="content">
        <h2>Password Reset</h2>
        <form method="post" action="classes/customer.php">
            <div class="form-group">
                <label for="username">Enter your username</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Enter your email address</label>
                <input type="text" id="email" name="email" class="form-control" required>
            </div>
            <button type="submit" name="resetc">Reset Password</button>
        </form>
    </div>
</div>
