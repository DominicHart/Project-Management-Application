<?php
include 'includes/session.php';
include_once ('classes/user.php');
include_once ('classes/project.php');
include_once ('classes/milestone.php');
include_once ('classes/task.php');
include_once ('classes/risk.php');
include_once ('classes/transaction.php');
$user = new user();
$user->notLoggedin();
$project = new project();
$milestone = new milestone();
$task = new task();
$risk = new risk();
$budget = new transaction();
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
    <div class="row header hidden-print">
        <h1>View Project</h1>
        <div class="pull-right">
            <button class="dropdown-toggle" type="button" id="menu1" data-toggle="dropdown"><?php echo $_SESSION['fullname']; ?><span class="caret"></span></button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                <?php if($access == 4) { echo "<li class='head'><a role='menuitem' tabindex='-1' href='admin.php' accesskey='1'>Admin Panel</a></li>"; } ?>
                <li class="presentation"><?php if($access == 1) { echo "<a class='back' href='client.php' accesskey='2'>Projects</a>"; } else if($access == 2) {echo "<a class='back' href='projects.php' accesskey='2'>Projects</a>";} else {echo "<a class='back' href='dashboard.php' accesskey='2'>Projects</a>";} ?></li>
                <li class="head" role="presentation"><a role="menuitem" tabindex="-1" href="messages.php" accesskey='3'>Conversations</a></li>
                <?php if($access == 2 || $access == 3) { echo "<li role='presentation'><a role='menuitem' tabindex='-1' href='files.php' accesskey='4'>File Browser </a></li>";} ?>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="#" id="changepassword" accesskey='5'>Change Password</a></li>
                <li><a role="menuitem" tabindex="-1" href="classes/user.php?t1" accesskey='6'>Toggle Tips</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
    <div class="row viewproject">
        <div class="hidden-print">
            <?php if($access == 2 || $access == 3) {
                echo <<<_END
                    <nav class="nav-menu"> 
                        <ul class="nav nav-pills">
                            <li class="active"><a data-toggle="pill" href="#outline">Project Outline</a></li>
                            <li><a data-toggle="pill" href="#milestones">Project Milestones</a></li>
                            <li><a data-toggle="pill" href="#tasks">Project Tasks</a></li>
                            <li><a data-toggle="pill" href="#risks">Project Risks</a></li>
                            <li><a data-toggle="pill" href="#finances">Project Finances</a></li>
                            <li><a data-toggle="pill" href="#issues">Project Issues</a></li>
                        </ul>
                    </nav>
_END;
            }
            elseif($access == 1) {
                echo <<<_END
                    <nav class="nav-menu"> 
                        <ul class="nav nav-pills">
                            <li class="active"><a data-toggle="pill" href="#outline">Project Outline</a></li>
                        </ul>
                    </nav>
_END;
            }
            else {
                echo "Error";
            }?>
        </div>
        <div class="col-sm-11">
            <div class="tab-content">
                <?php include 'includes/alerts.php';
                if($access == 2 || $access == 3) {
                    echo <<<_END
                <div class="tab-pane fade in active" id="outline">
                    <h2>Project Details</h2>
_END;
                    $project->getThisProject($title);
                    echo <<<_END
                </div>
                <div class="tab-pane fade" id="milestones">
                    <h2>Milestones</h2>
_END;
                    $milestone->getMilestones($title);
                    echo <<<_END
                </div>
                <div class="tab-pane fade" id="tasks">
_END;
                    $task->getTasks($title);
                    echo <<<_END
                </div>
                <div class="tab-pane fade" id="risks">
                    <h2>Project Risks</h2>
_END;
                    $risk->getRisks($title);
                    echo <<<_END
                </div>
                <div class="tab-pane fade" id="finances">
                    <h2>Project Finances</h2>
_END;
                    $budget->getBudget($title);
                    echo <<<_END
                </div>
_END;
                    echo <<<_END
                <div class="tab-pane fade" id="issues">
                    <h2>Project Issues</h2>
_END;
                    $project->getallIssues($title);
                    echo <<<_END
                </div>
_END;
                }
                elseif($access == 1) {
                    echo <<<_END
                <div class="tab-pane fade in active" id="outline">
                    <h2>Project Details</h2>
_END;
                    $project->getThisProject($title);
                    echo <<<_END
                </div>
_END;
                }
                else {
                    echo "Error";
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php include 'parts/footer.php'; ?>
