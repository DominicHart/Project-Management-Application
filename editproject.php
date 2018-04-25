<?php
include 'includes/session.php';
include_once ('classes/user.php');
include_once ('classes/project.php');
$user = new user();
$user->notLoggedin();
$project = new project();
include 'parts/header.php';
if(isset($_GET['slug'])) {
    $title = $_GET['slug'];
    $project->checkSlug($title);
}
else {
    header('location:404.php');
}
?>
<div class="container-fluid">
    <div id="edit-delete"></div>
    <div class="row header">
        <h1>eProject</h1>
        <div class="pull-right">
            <button class="dropdown-toggle" type="button" id="menu1" data-toggle="dropdown"><i class="fa fa-user" aria-hidden="true"></i><span class="caret"></span></button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                <?php if($access == 4) { echo "<li class='head'><a role='menuitem' tabindex='-1' href='admin.php' accesskey='1'>Admin Panel</a></li>"; } ?>
                <li class="presentation"><?php if($access == 1) { echo "<a class='back' href='client.php' accesskey='2'>Projects</a>"; } else if($access == 2) {echo "<a class='back' href='projects.php' accesskey='2'>Projects</a>";} else {echo "<a class='back' href='dashboard.php' accesskey='2'>Projects</a>";} ?></li>
                <li class="head" role="presentation"><a role="menuitem" tabindex="-1" href="messages.php" accesskey='3'>Inbox</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="files.php" accesskey='4'>File Browser </a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="#" id="changepassword" accesskey='5'>Change Password</a></li>
                <li><a role="menuitem" tabindex="-1" href="classes/user.php?t1" accesskey='6'>Toggle Tips</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
    <div class="row viewproject">
        <div class="col-sm-10 col-sm-offset-1">
            <h2>Edit Project</h2>
            <?php $project->getThisProjectEdit($title); ?>
        </div>
    </div>
</div>
<?php include 'parts/footer.php'; ?>
