<?php
include 'includes/session.php';
include_once ('classes/user.php');
$user = new user();
$user->notLoggedin();
include 'parts/header.php';
?>
<div class="error404">
    <div class="content">
        <h1>Error: 400</h1>
        <p>Your request could not be completed, go back to the <?php if($access == 1) { echo "<a class='back' href='client.php'>dashboard</a>"; } else if($access == 2) {echo "<a class='back' href='projects.php'>dashboard</a>";} else {echo "<a class='back' href='dashboard.php'>dashboard</a>";} ?>.</p>
    </div>
</div>
