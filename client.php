<?php
include 'includes/session.php';
include_once ('classes/user.php');
include_once ('classes/project.php');
include_once ('classes/message.php');
$user = new user();
$user->notLoggedin();
$project = new project();
$message = new message();
if($access == 2) {
    header('location:projects.php');
}
else if($access == 3) {
    header('location:dashboard.php');
}
else if($access == 4) {
    header('location:admin.php');
}
include 'parts/header.php';
?>
<div class="container-fluid">
    <div id="edit-delete"></div>
    <div class="row header hidden-print">
        <h1>Dashboard</h1>
        <div class="pull-right">
            <button class="dropdown-toggle" type="button" id="menu1" data-toggle="dropdown"><?php echo $_SESSION['fullname']; ?><span class="caret"></span></button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                <li class="head" role="presentation"><a role="menuitem" tabindex="-1" href="messages.php" accesskey='3'>Conversations</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="#" id="changepassword" accesskey='5'>Change Password</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
    <div class="row client">
        <nav class="nav-menu hidden-print">
            <ul class="nav nav-pills">
                <li class="active"><a data-toggle="pill" href="#viewprojects">View Your Projects</a></li>
            </ul>
        </nav>
        <div class="col-sm-11">
            <div class="tab-content">
                <?php $project->getIssues();
                    if(isset($_SESSION['alert'])) {
                        if($_SESSION['alert'] == 'pass') {
                            echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>Your password has been changed.</div>";
                        }
                        unset($_SESSION['alert']);
                    }
                    if(isset($_SESSION['error'])) {
                        if($_SESSION['error'] == 'pass1') {
                            echo "<div class='alert alert-danger alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-exclamation-circle' aria-hidden='true'></i>The passwords you entered do not match!</div>";
                        } else if ($_SESSION['error'] == 'pass2') {
                            echo "<div class='alert alert-danger alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-exclamation-circle' aria-hidden='true'></i>The password you entered is invalid!</div>";
                        } else if ($_SESSION['error'] == 'pr1') {
                            echo "<div class='alert alert-danger alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-exclamation-circle' aria-hidden='true'></i>Password must contain uppercase</div>";
                        } else if ($_SESSION['error'] == 'pr2') {
                            echo "<div class='alert alert-danger alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-exclamation-circle' aria-hidden='true'></i>Password must contain lowercase</div>";
                        } else if ($_SESSION['error'] == 'pr3') {
                            echo "<div class='alert alert-danger alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-exclamation-circle' aria-hidden='true'></i>Password must contain number</div>";
                        } else if ($_SESSION['error'] == 'pr4') {
                            echo "<div class='alert alert-danger alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-exclamation-circle' aria-hidden='true'></i>Password must be 9 characters minimum</div>";
                        }
                        unset($_SESSION['error']);
                    }
                ?>
                <div class="tab-pane fade active in" id="viewprojects">
                    <?php $project->getTaggedProjects(); ?>
                </div>

            </div>
        </div>
    </div>
</div>
<?php include 'parts/footer.php'; ?>