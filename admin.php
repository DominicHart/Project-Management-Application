<?php
include 'includes/session.php';
include_once ('classes/user.php');
include_once ('classes/project.php');
$user = new user();
$user->notLoggedin();
$project = new project();
if($access == 1) {
    header('location:client.php');
}
elseif($access == 2) {
    header('location:projects.php');
}
elseif($access == 3) {
    header('location:dashboard.php');
}
else {
    //Do nothing
}
include 'parts/header.php';
?>
<div class="container-fluid">
    <div id="edit-delete"></div>
    <div class="row header hidden-print">
        <h1>Admin Panel</h1>
        <div class="pull-right">
            <button class="dropdown-toggle" type="button" id="menu1" data-toggle="dropdown"><?php echo $_SESSION['fullname']; ?><span class="caret"></span></button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                <li class="head" role="presentation"><a role="menuitem" tabindex="-1" href="messages.php" accesskey='3'>Conversations</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="#" id="changepassword" accesskey='5'>Change Password</a></li>
                <li><a role="menuitem" tabindex="-1" href="classes/user.php?t1" accesskey='6'>Toggle Tips</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
    <div class="row dashboard admin">
        <div>
            <nav class="nav-menu hidden-print">
                <ul class="nav nav-pills">
                    <li class="active"><a data-toggle="tab" href="#man-projects">Manage Projects</a></li>
                    <li id="li-man-users"><a data-toggle="tab" href="#man-users">Manage Users</a></li>
                    <li><a data-toggle="pill" href="#addemployee">New Employee</a></li>
                    <li><a data-toggle="pill" href="#addcustomer">New Customer</a></li>
                    <li><a data-toggle="tab" href="#view-transfers">View Transfers</a></li>
                </ul>
            </nav>
        </div>
        <div class="col-sm-11 col-md-8">
            <div class="tab-content">
                <?php include 'includes/alerts.php'; ?>
                <div class="tab-pane fade in active" id="man-projects">
                    <h2>Manage Projects</h2>
                    <?php $project->getAllProject(); ?>
                </div>
                <div class="tab-pane fade" id="man-users">
                    <h2>Manage Users</h2>
                    <div id="load-selecteduser"></div>
                    <?php $user->userTable() ?>
                </div>
                <div class="tab-pane fade" id="addemployee">
                    <h2>New Employee</h2>
                    <?php if($tips == 'Yes') {echo <<<_END
                    <div class="alert alert-info alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <i class="fa fa-info-circle" aria-hidden="true"></i>The employee will receive an email with their username & password.
                    </div>
_END;
                    } ?>
                    <form action="classes/user.php" method="post" autocomplete="off">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="fullname">Employee Name</label>
                                <input type="text" class="form-control" id="fullname" name="fullname" placeholder="e.g. John Doe" required>
                            </div>
                            <div id="load-username2" class="col-sm-12"></div>
                            <div class="form-group col-sm-12">
                                <label for="username">Username</label>
                                <input type="text" class="form-control username" id="username2" name="username" placeholder="e.g. johndoe" required>
                            </div>
                            <div id="load-email2" class="col-sm-12"></div>
                            <div class="form-group col-sm-12">
                                <label for="email">Email</label>
                                <input type="email" class="form-control email" id="email2" name="email" placeholder="e.g. john.doe@email.com" required>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="role">Role</label>
                                <p>Enter: Project Manager for the employee to have full access.</p>
                                <input type="text" class="form-control" id="role" name="role" placeholder="e.g. bug tester" required>
                            </div>
                            <div class="col-sm-12">
                                <p>A password will be generated for and emailed to the employee.</p>
                                <button type="submit" name="semployee" id="semployee">Add Staff</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="addcustomer">
                    <h2>New Customer</h2>
                    <?php if($tips == 'Yes') {echo <<<_END
                    <div class="alert alert-info alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <i class="fa fa-info-circle" aria-hidden="true"></i>The customer will receive an email with their username & password.
                    </div>
_END;
                    } ?>
                    <form action="classes/customer.php" method="post" autocomplete="off">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="fullname">Customer Name</label>
                                <input type="text" class="form-control" id="fullname" name="fullname" placeholder="e.g. John Doe" required>
                            </div>
                            <div id="load-username" class="col-sm-12"></div>
                            <div class="form-group col-sm-12">
                                <label for="username">Username</label>
                                <input type="text" class="form-control username" id="username" name="username" placeholder="e.g. johndoe" required>
                            </div>
                            <div id="load-email" class="col-sm-12"></div>
                            <div class="form-group col-sm-12">
                                <label for="email">Email</label>
                                <input type="email" class="form-control email" id="email" name="email" placeholder="e.g. john.doe@email.com" required>
                            </div>
                            <div class="col-sm-12">
                                <p class="form-text">A password will be generated for and emailed to the customer.</p>
                                <button type="submit" name="scustomer" id="scustomer">Add Customer</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="view-transfers">
                    <h2>Manage Transfers</h2>
                    <?php $project->getTransfers() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'parts/footer.php'; ?>
