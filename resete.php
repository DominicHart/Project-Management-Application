<?php
include 'includes/session.php';
if(isset($thisuser)) {
    if($access == 1) {
        header('location:http://dominichart.uk/client.php');
    } else if ($access == 2) {
        header('location:http://dominichart.uk/projects.php');
    } else {
        header('location:http://dominichart.uk/admin.php');
    }
}
include 'parts/header.php';
?>
<div class="reset">
    <div class="content">
        <h2>Password Reset</h2>
        <form method="post" action="http://dominichart.uk/classes/user.php">
            <div class="form-group">
                <label for="username">Enter your username</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Enter your email address</label>
                <input type="text" id="email" name="email" class="form-control" required>
            </div>
            <button type="submit" name="resete">Reset Password</button>
        </form>
    </div>
</div>
