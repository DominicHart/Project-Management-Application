<?php
include 'includes/session.php';
include_once ('classes/user.php');
include_once ('classes/project.php');
include_once ('classes/customer.php');
$user = new user();
$user->notLoggedin();
$project = new project();
$customer = new customer();
if($access == 1) {
    header('location:client.php');
} else if($access == 2) {
    header('location:projects.php');
} else if($access == 4) {
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
                <li class="head" role="presentation"><a role="menuitem" tabindex="-1" href="messages.php" accesskey='3'>Inbox</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="files.php" accesskey='4'>File Browser </a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="#" id="changepassword" accesskey='5'>Change Password</a></li>
                <li><a role="menuitem" tabindex="-1" href="classes/user.php?t1" accesskey='6'>Toggle Tips</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
    <div class="row dashboard">
        <div class="hidden-print">
            <?php $project->getTabs(); ?>
        </div>
        <div class="col-sm-11 col-md-8">
            <div class="tab-content">
                <?php include 'includes/alerts.php'; ?>
                <?php $project->getIssues(); ?>
                <div class="tab-pane fade in active" id="viewprojects">
                    <h2>Your projects</h2>
                    <?php $project->getTaggedProjects(); ?>
                </div>
                <div class="tab-pane fade" id="addproject">
                    <h2>Register a project</h2>
                    <?php if($tips == 'Yes') {echo <<<_END
                    <div class="alert alert-info alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <i class="fa fa-info-circle" aria-hidden="true"></i>After creating a project you can view and edit it at any time.
                    </div>
_END;
                    } ?>
                    <form action="classes/project.php" method="post">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="title">Title&nbsp;<b>*</b></label>
                                <input type="text" name="title" id="title" class="form-control" required>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="overview">Overview&nbsp;<b>*</b></label>
                                <textarea rows="5" class="form-control" name="overview" id="overview" required></textarea>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="aim">Aim&nbsp;<b>*</b></label>
                                <input type="text" name="aim" id="aim" class="form-control" required>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="objectives">Objectives&nbsp;<b>*</b></label>
                                <textarea rows="5" class="form-control" name="objectives" id="objectives" required></textarea>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="start">Initiation Date&nbsp;<b>*</b></label>
                                <input type="text" class="form-control datepicker" name="start" id="start" required>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="end">Completion Date&nbsp;<b>*</b></label>
                                <input type="text" class="form-control datepicker" name="end" id="end" required>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="end">Initial Budget&nbsp;<b>*</b></label>
                                <input type="number" min="0.00" max="9999999.00" step="0.01" class="form-control" name="budget" id="budget" required>
                            </div>
                            <div class="form-group col-sm-12 customer">
                                <label for="customer">Select Customer&nbsp;<b>*</b></label>
                                <select name="customer" id="customer" class="form-control" required>
                                    <?php $customer->getCustomers(); ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-6 newcustomer">
                                <label for="newcustomer">New Customer</label>
                                <a data-toggle="pill" href="#addcustomer">Click this to create a new customer</a>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" name="registerproject">Add Project</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="addmilestone">
                    <h2>Add a milestone</h2>
                    <?php if($tips == 'Yes') {echo <<<_END
                    <div class="alert alert-warning alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <i class="fa fa-warning" aria-hidden="true"></i>You can't change the milestone deadline once it has been created. This is to calculate slippage.
                    </div>
_END;
 } ?>
                    <form action="classes/milestone.php" method="post">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="project3">Project&nbsp;<b>*</b></label>
                                <select name="project" id="project3" class="form-control" required>
                                    <?php $project->getProjectTitles(); ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="mtitle">Title&nbsp;<b>*</b></label>
                                <input type="text" class="form-control" name="mtitle" id="mtitle" required>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="mdescription">Description&nbsp;<b>*</b></label>
                                <textarea rows="5" class="form-control" name="mdescription" id="mdescription" required></textarea>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="mstart">Start Date&nbsp;<b>*</b></label>
                                <input type="text" class="form-control datepicker" id="mstart" name="mstart" required>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="deadline">Deadline&nbsp;<b>*</b></label>
                                <input type="text" class="form-control datepicker" id="deadline" name="deadline" required>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" name="smilestone">Add Milestone</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="addtask">
                    <h2>New Project Task</h2>
                    <?php if($tips == 'Yes') {echo <<<_END
                    <div class="alert alert-info alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <i class="fa fa-info-circle" aria-hidden="true"></i>You can always edit or delete a task later on.
                    </div>
_END;
 } ?>
                    <form action="classes/task.php" method="post">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="project2">Select Project&nbsp;<b>*</b></label>
                                <select name="project" id="project2" class="form-control" required>
                                    <?php $project->getProjectTitles(); ?>
                                </select>
                            </div>
                            <div id="load-milestones" class="col-sm-12"></div>
                            <div class="form-group col-sm-12">
                                <label for="task">Define Task&nbsp;<b>*</b></label>
                                <input type="text" class="form-control" name="task" id="task" placeholder="e.g. Survey existing customers" required>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="desc">Description&nbsp;<b>*</b></label>
                                <input type="text" name="desc" id="desc" class="form-control" placeholder="e.g. Meet with client to discuss timeframe">
                            </div>
                            <div id="load-dates2"></div>
                            <div id="load-budget"></div>
                            <div id="load-pstaff"></div>
                            <div class="form-group col-sm-12">
                                <label for="status">Select Status&nbsp;<b>*</b></label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="To Do">To Do</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Complete">Complete</option>
                                </select>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" name="stask">Add Task</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="addrisk">
                    <h2>Add Risk</h2>
                    <?php if($tips == 'Yes') {echo <<<_END
                    <div class="alert alert-info alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <i class="fa fa-info-circle" aria-hidden="true"></i>You can view project risks in the project details.
                    </div>
_END;
                    } ?>
                    <form action="classes/risk.php" method="post" autocomplete="off">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="project3">Select Project&nbsp;<b>*</b></label>
                                <select name="project3" id="project3" class="form-control" required>
                                    <?php $project->getProjectTitles(); ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="risk">Title&nbsp;<b>*</b></label>
                                <input type="text" name="risk" id="risk" class="form-control" placeholder="e.g. Scope creep" required>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="rdesc">Description&nbsp;<b>*</b></label>
                                <textarea rows="5" class="form-control" name="rdesc" id="rdesc" maxlength="500" required></textarea>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="probability">Probability&nbsp;<b>*</b></label>
                                <select name="probability" id="probability" class="form-control" required>
                                    <option selected>Select Option</option>
                                    <option value="Low">Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="impact">Impact&nbsp;<b>*</b></label>
                                <textarea rows="5" class="form-control" name="impact" id="impact" maxlength="500" required></textarea>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="mitigation">Mitigation&nbsp;<b>*</b></label>
                                <textarea rows="5" class="form-control" name="mitigation" id="mitigation" maxlength="500" required></textarea>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" name="srisk">Add Risk</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="addtransaction">
                    <h2>Add Transaction</h2>
                    <?php if($tips == 'Yes') {echo <<<_END
                    <div class="alert alert-info alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <i class="fa fa-info-circle" aria-hidden="true"></i>Don't forget to add your budget to the finances.
                    </div>
_END;
                    } ?>
                    <form action="classes/transaction.php" method="post" autocomplete="off">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="project4">Select Project&nbsp;<b>*</b></label>
                                <select name="project4" id="project4" class="form-control" required>
                                    <?php $project->getProjectTitles(); ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="amount">Enter Amount&nbsp;<b>*</b></label>
                                <input type="number" min="0.00" max="9999999.00" step="0.01" class="form-control" name="amount" id="amount" required>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="btype">Select Type&nbsp;<b>*</b></label>
                                <select name="btype" id="btype" class="form-control" required>
                                    <option selected>Select Type</option>
                                    <option value="Incoming">Budget</option>
                                    <option value="Outgoing">Expense</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="bdate">Date of Transaction&nbsp;<b>*</b></label>
                                <input type="text" class="form-control datepicker" name="bdate" id="bdate" required>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="bdesc">Description&nbsp;<b>*</b></label>
                                <textarea rows="5" class="form-control" name="bdesc" id="bdesc" maxlength="500" required></textarea>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" name="stransaction">Add Transaction</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="employees">
                    <h2>Assign Employees to Projects</h2>
                    <?php if($tips == 'Yes') {echo <<<_END
                    <div class="alert alert-info alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <i class="fa fa-info-circle" aria-hidden="true"></i>Assign an employee to a project to allow them to view the project and leave comments.
                    </div>
_END;
                    } ?>
                    <form action="classes/project.php" method="post" autocomplete="off">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="employee">Select Employee</label>
                                    <select name="employee" id="employee" class="form-control" required>
                                        <?php $user->getEmployees(); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="project2">Select Project</label>
                                    <select name="project2" id="project2" class="form-control" required>
                                        <?php $project->getProjectTitles(); ?>
                                    </select>
                                </div>
                                <button type="submit" name="sassign">Assign Employee</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="reportissue">
                    <h2>Report an Issue</h2>
                    <form action="classes/project.php" method="post" autocomplete="off">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="projectf">Select Project</label>
                                    <select name="project" id="projectf" class="form-control" required>
                                        <?php $project->getProjectTitles(); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="idesc">Description</label>
                                    <textarea rows="5" class="form-control" name="idesc" id="idesc" maxlength="500" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="istatus">Issue Status</label>
                                    <select name="istatus" id="istatus" class="form-control" required>
                                        <option selected>Select Status</option>
                                        <option value="raised">Raised</option>
                                        <option value="escalated">Escalated</option>
                                        <option value="resolved">Resolved</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="showclient">Notify Client</label>
                                    <p>If you select Yes, the client can view the issue</p>
                                    <select name="showclient" id="showclient" class="form-control" required>
                                        <option selected>Select Option</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                                <button type="submit" name="sreportissue">Report Issue</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'parts/footer.php'; ?>