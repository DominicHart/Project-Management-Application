<?php
if (session_status() == PHP_SESSION_NONE){
    session_start();
};
include_once ($_SERVER['DOCUMENT_ROOT'].'./fyp/includes/config.php');
include_once ('slug.php');
include_once ('customer.php');
include_once ('project.php');
$project = new project();
if(isset($_POST['registerproject'])) {
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $overview = filter_var($_POST['overview'], FILTER_SANITIZE_STRING);
    $aim = filter_var($_POST['aim'], FILTER_SANITIZE_STRING);
    $objectives = filter_var($_POST['objectives'], FILTER_SANITIZE_STRING);
    $customer = filter_var($_POST['customer'], FILTER_SANITIZE_STRING);
    $start = filter_var($_POST['start'], FILTER_SANITIZE_STRING);
    $start = date('Y-m-d', strtotime($start));
    $end = filter_var($_POST['end'], FILTER_SANITIZE_STRING);
    $end = date('Y-m-d', strtotime($end));
    $budget = filter_var($_POST['budget'], FILTER_SANITIZE_STRING);
    if($customer == 'Other') {
        header('location:../dashboard.php');
        exit();
    }
    else {
        $customer = filter_var($_POST['customer'], FILTER_SANITIZE_STRING);
    }
    $project->registerProject($title, $overview, $aim, $objectives, $customer, $start, $end, $budget);
}
if(isset($_POST['updateproject'])) {
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $customer = filter_var($_POST['customer'], FILTER_SANITIZE_STRING);
    $start = filter_var($_POST['start'], FILTER_SANITIZE_STRING);
    $start = date('Y-m-d', strtotime($start));
    $end = filter_var($_POST['end'], FILTER_SANITIZE_STRING);
    $end = date('Y-m-d', strtotime($end));
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
    $overview = filter_var($_POST['overview'], FILTER_SANITIZE_STRING);
    $id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
    if($customer == 'Other') {
        header('location:../dashboard.php');
        exit();
    }
    else {
        $customer = filter_var($_POST['customer'], FILTER_SANITIZE_STRING);
    }
    $project->updateProject($title, $customer, $start, $end, $status, $overview, $id);
}
if(isset($_POST['sassign'])) {
    $employee = filter_var($_POST['employee'], FILTER_SANITIZE_STRING);
    $id = filter_var($_POST['project2'], FILTER_SANITIZE_STRING);
    $project->assignEmployee($employee, $id);
}
if(isset($_POST['sdeleteproject'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
    $project->deleteThisProject($id);
}
if(isset($_POST['transfer'])) {
    $employee = filter_var($_POST['selectpm'], FILTER_SANITIZE_STRING);
    $projID = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
    $project->transferProject($employee, $projID);
}
if(isset($_POST['sfile'])) {
    $proj_id = filter_var($_POST['project'], FILTER_SANITIZE_STRING);
    $file = $_FILES['uploadFile'];
    //file properties
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];

    // find out file extension
    $file_extension = explode('.', $file_name);
    $file_extension = strtolower(end($file_extension));

    //set allowed file types array
    $allowed = array('png', 'jpg', 'jpeg', 'gif', 'txt', 'docx', 'rtf', 'pdf', 'xlsx', '.pptx');

    //check file extension is valid
    if(in_array($file_extension, $allowed)){
        //check file has no errors
        if($file_error ===0){
            //check file size is not too large
            if($file_size <= 2097152) {
                //generate unique file name
                //$file_name_new = uniqid('', true) . '.' . $file_extension;
                //set file storage destination
                $file_destination = '../files/projects/' . $file_name;
                //move file to new location
                move_uploaded_file($file_tmp, $file_destination);
            }
        }
    }
    $project->uploadFile($proj_id, $file_name);
}
if(isset($_POST['sdeletefile'])) {
    $proj_id = filter_var($_POST['project'], FILTER_SANITIZE_STRING);
    $filename = filter_var($_POST['filename'], FILTER_SANITIZE_STRING);
    $project->deleteFile($proj_id, $filename);
}
if(isset($_POST['sreportissue'])) {
    $proj_id = filter_var($_POST['project'], FILTER_SANITIZE_STRING);
    $issue = filter_var($_POST['idesc'], FILTER_SANITIZE_STRING);
    $status = filter_var($_POST['istatus'], FILTER_SANITIZE_STRING);
    $showclient = filter_var($_POST['showclient'], FILTER_SANITIZE_STRING);
    $statuses = array('raised', 'escalated', 'resolved');
    $options = array('yes', 'no');
    //If the status is not valid or if the option is not valid, error
    if(!in_array($status, $statuses)) {
        header('location:../404.php');
        exit();
    } else if(!in_array($showclient, $options)){
        header('location:../404.php');
        exit();
    } else {
        $project->reportIssue($proj_id, $issue, $status, $showclient);
    }
}
if(isset($_POST['updateissue'])) {
    $proj_id = filter_var($_POST['project'], FILTER_SANITIZE_STRING);
    $issue_id = filter_var($_POST['issue'], FILTER_SANITIZE_STRING);
    $issue = filter_var($_POST['idesc'], FILTER_SANITIZE_STRING);
    $status = filter_var($_POST['istatus'], FILTER_SANITIZE_STRING);
    $showclient = filter_var($_POST['showclient'], FILTER_SANITIZE_STRING);
    $statuses = array('raised', 'escalated', 'resolved');
    $options = array('yes', 'no');
    //If the status is not valid or if the option is not valid, error
    if(!in_array($status, $statuses)) {
        header('location:../404.php');
        exit();
    } else if(!in_array($showclient, $options)){
        header('location:../404.php');
        exit();
    } else {
        $project->updateIssue($proj_id, $issue_id, $issue, $status, $showclient);
    }
}
if(isset($_GET['issue'])) {
    $issue = filter_var($_GET['issue'], FILTER_SANITIZE_STRING);
    if (strpos($issue, ',') !== false) {
        $split = explode(',', $issue);
        $projectID = $split[0];
        $issueDesc = $split[1];
        $project->hideIssue($projectID, $issueDesc);
    } else {
        header('location:400.php');
    }
}
if(isset($_GET['showall'])) {
    $project->showallIssues();
}
if(isset($_POST['sdeleteissue'])) {
    $proj_id = filter_var($_POST['project'], FILTER_SANITIZE_STRING);
    $issue_id = filter_var($_POST['issue'], FILTER_SANITIZE_STRING);
    $project->deleteThisIssue($issue_id, $proj_id);
}
class project
{
    function __construct()
    {
    }

    public function checkSlug($slug)
    {
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT slug FROM project WHERE slug = :slug");
            $result->execute(array(':slug' => $slug));
            $row = $result->fetch(PDO::FETCH_ASSOC);
            if ($row < 1) {
                header('location:../404.php');
            } else {
                //Do nothing
            }
        } catch (PDOException $e) {
            header('location:../404.php');
        }
    }

    public function registerProject($title, $overview, $aim, $objectives, $customer, $start, $end, $budget)
    {
        try {
            $slug = new slug();
            $newslug = $slug->slugThis($title);
            $db = new db();
            $stmt = $db->pdo->prepare("INSERT INTO project (project_title, overview, aim, objectives, startDate, endDate, budget, slug) VALUES (:title, :overview, :aim, :objectives, :start, :end, :budget, :slug); INSERT INTO project_details (project_manager, customer, project_id) VALUES (:project_manager, :customer, LAST_INSERT_ID())");
            $stmt->execute(array(':title' => $title, ':overview' => $overview, ':aim' => $aim, ':objectives' => $objectives, ':start' => $start, ':end' => $end, ':budget' => $budget, ':slug' => $newslug, ':project_manager' => $_SESSION['username'], ':customer' => $customer));
            if ($stmt == true) {
                header('location:../dashboard.php');
                $_SESSION['alert'] = 'a1';
            } else {
                header('location:../400.php');
            }
        } catch (PDOException $e) {
            //echo "Error: ".$e."<br>";
            header("location:../400.php");
        }
    }

    public function updateProject($title, $customer, $start, $end, $status, $overview, $id)
    {
        try {
            $db = new db();
            $slug = new slug();
            $newslug = $slug->slugThis($title);
            $stmt = $db->pdo->prepare("UPDATE project SET project_title = :title, startDate = :start, endDate = :end, project_status = :status, overview = :overview, slug = :slug WHERE project_id = :id; UPDATE project_details SET customer = :customer WHERE project_id = :id");
            $stmt->execute(array(':title' => $title, ':start' => $start, 'end' => $end, ':status' => $status, ':overview' => $overview, ':slug' => $newslug, ':customer' => $customer, ':id' => $id));
            if ($stmt == true) {
                $_SESSION['alert'] = "u1";
                echo "<script>window.history.go(-1);</script>";
            } else {
                header("location:../400.php");
            }
        } catch (PDOException $e) {
            //echo "Error: ".$e."<br>";
            header("location:../400.php");
        }
    }

    public function getTabs()
    {
        echo <<<_END
            <nav class="nav-menu">
                <ul class="nav nav-pills">
                    <li class="active"><a data-toggle="tab" href="#viewprojects">Projects</a></li><!--
                    --><li><a data-toggle="pill" href="#addproject">Register Project</a></li><!--
_END;
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT project_manager FROM project_details WHERE project_manager = :username");
            $result->execute(array(':username' => $_SESSION['username']));
            $row = $result->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($row)) {
                echo <<<_END
                --><li><a data-toggle="pill" href="#addmilestone">Add Milestone</a></li><!--
                --><li><a data-toggle="pill" href="#addtask">Add Task</a></li><!--
                --><li><a data-toggle="pill" href="#addrisk">Add Risk</a></li><!--
                --><li><a data-toggle="pill" href="#addtransaction">Add Transaction</a></li><!--
                --><li><a data-toggle="pill" href="#employees">Assign Employee</a></li><!--
                --><li><a data-toggle="pill" href="#reportissue">Report an Issue</a></li>
_END;
            } else {
                echo <<<_END
                --><li class="disabled"><a data-toggle="pill" href="#">Add Milestone</a></li><!--
                --><li class="disabled"><a data-toggle="pill" href="#">Add Task</a></li><!--
                --><li class="disabled"><a data-toggle="pill" href="#">Add Risk</a></li><!--
                --><li class="disabled"><a data-toggle="pill" href="#">Add Transaction</a></li><!--
                --><li class="disabled"><a data-toggle="pill" href="#">Assign Employee</a></li><!--
                --><li class="disabled"><a data-toggle="pill" href="#">Report an Issue</a></li>
_END;
            }
        } catch (PDOException $e) {
            //echo "Error: ".$e."<br>";
            echo "Error: Could not load tabs";
        }
        echo <<<_END
                </ul>
            </nav>
_END;
    }

    public function getAllProject()
    {

        try {

            $db = new db();
            echo <<<_END
            <div class="man-projects">
                <label for="man-proj">Manage Projects:</label>
                <select name="man-proj" id="man-proj" required>
_END;
            $getProjectList = $db->pdo->query("SELECT project_id, project_title FROM project");
            while ($row = $getProjectList->fetch(PDO::FETCH_ASSOC)) {
                echo <<<_END
                    <option value="$row[project_id]">$row[project_title]</option>
_END;
            }
            echo <<<_END
                </select>
                <button type="submit" name="submit" class="dproject2">Delete</button>
            </div>
            <div class="table-responsive">
            <table class="table">
            <thead>
                <tr><th>Title</th><th>PM</th><th>Customer</th><th>Start</th><th>End</th><th>Status</th></tr>
            </thead>
            <tbody>
_END;
            $getProjects = $db->pdo->query("SELECT project.project_id, project.project_title, project.project_status, project.startDate, project.endDate, project_details.project_manager, project_details.customer, employee.username, employee.fullname, customer.username, customer.cfullname FROM project_details LEFT JOIN project ON project_details.project_id = project.project_id LEFT JOIN employee ON project_details.project_manager = employee.username LEFT JOIN customer ON project_details.customer = customer.username");
            while ($row = $getProjects->fetch(PDO::FETCH_ASSOC)) {
                $start = date("d/m/Y", strtotime($row['startDate']));
                $end = date("d/m/Y", strtotime($row['endDate']));
                echo <<<_END
                    <tr><td data-label="Title">$row[project_title]</td><td data-label="PM">$row[fullname]</td><td data-label="Customer">$row[cfullname]</td><td data-label="Start">$start</td><td data-label="End">$end</td><td data-label="Status">$row[project_status]</td></tr>
_END;
            }
            echo <<<_END
            </tbody>
            </table>
        </div>
_END;

        } catch (PDOException $e) {
            header("location:../404.php");
            //echo "Error: ".$e."<br>";
        }
    }

    public function getTaggedProjects()
    {
        try {
            $db = new db();
            $count = $db->pdo->prepare("SELECT project_manager, customer, project_id FROM project_details LEFT OUTER JOIN project_staff ON project_details.project_id = project_staff.p_id WHERE project_details.project_manager = :username OR project_details.customer = :username OR project_staff.s_username = :username;");
            $count->execute(array(':username' => $_SESSION['username']));
            $res = $count->fetch(PDO::FETCH_ASSOC);
            if ($res < 1) {
                if ($_SESSION['access'] == 3) {
                    echo "<div class='alert alert-info'><i class='fa fa-info-circle' aria-hidden='true'></i>You do not have any projects. To create one <a data-toggle='pill' class='alert-link' href='#addproject'>click here</a>.</div>";
                } else {
                    echo "<div class='alert alert-info'><i class='fa fa-info-circle' aria-hidden='true'></i>You do not have any projects.</div>";
                }
            } else {
                //Select projects where the user is either 1) the customer, 2) the project manager or 3) an employee who is assigned to it. There could be more than one employee assigned to it, so concatenate employees into one result to prevent duplicate results
                $result = $db->pdo->prepare('SELECT project.project_id, project.project_title, project.startDate, project.endDate, project.project_status, project.slug, project_details.project_manager, project_details.customer, project_staff.p_id, GROUP_CONCAT(s_username SEPARATOR \', \'), employee.staff_id, employee.username, employee.fullname, customer.username, customer.cfullname FROM project_details LEFT JOIN employee ON project_details.project_manager = employee.username LEFT JOIN customer ON project_details.customer = customer.username LEFT JOIN project ON project_details.project_id = project.project_id LEFT OUTER JOIN project_staff ON project_details.project_id = project_staff.p_id WHERE project_details.project_manager = :username OR project_details.customer = :username or project_staff.s_username = :username GROUP BY project.project_id');
                $result->execute(array(':username' => $_SESSION['username']));
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    if ($row['project_status'] == 'Open') {
                        $status = '<span class="label label-success pull-right">Active</span>';
                        $border = 'open-project';
                    } else {
                        $status = '<span class="label label-danger pull-right">Completed</span>';
                        $border = 'closed-project';
                    }
                    $start = date("d/m/Y", strtotime($row['startDate']));
                    $end = date("d/m/Y", strtotime($row['endDate']));
                    echo <<<_END
                <div class="project $border">
                    <div class="title">
                        <h3>$row[project_title]$status</h3>
                    </div>
                    <div class="body">
                        <p>Project Manager: <b class="pull-right">$row[fullname]</b></p>
                        <p>Client: <b class="pull-right">$row[cfullname]</b></p>
                        <p>Duration: <b class="pull-right">$start - $end</b></p>
                        <a href="v-$row[slug]">Project Details</a>
_END;
                    if ($_SESSION['userID'] == $row['staff_id'] && ($_SESSION['access'] == '3')) {
                        echo <<<_END
                        <button type="submit" name="$row[project_id]" class="tproject">Transfer Project</button>
                        <div class="pull-right">
                            <a href="e-$row[slug]">Edit</a>
                            <button type="submit" name="$row[project_id]" class="dproject">Delete</button>
                        </div>
                        <div class="clearfix"></div>
_END;
                    }
                    echo <<<_END
                    </div>
                </div>
_END;
                }
            }
        } catch (PDOException $e) {
            header("location:../404.php");
            //echo "Error: ".$e."<br>";
        }
    }

    public function getThisProject($slug)
    {
        try {
            $db = new db();
            $result = $db->pdo->prepare('SELECT project_details.project_id, project_details.project_manager, project_details.customer, project.project_title, project.startDate, project.endDate, project.project_status, project.overview, project.aim, project.objectives, project.slug, customer.cfullname, employee.fullname, project_staff.s_username, project_staff.p_id FROM project_details LEFT JOIN employee ON project_details.project_manager = employee.username LEFT JOIN customer ON project_details.customer = customer.username LEFT JOIN project ON project_details.project_id = project.project_id LEFT OUTER JOIN project_staff ON project_details.project_id = project_staff.p_id WHERE (project_details.project_manager = :username OR project_details.customer = :username OR project_staff.s_username = :username) AND project.slug = :slug GROUP BY project.project_id');
            $result->execute(array(':username' => $_SESSION['username'], ':slug' => $slug));
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                if ($row < 1) {
                    header('location:../404.php');
                } else {
                    $start = date("d/m/Y", strtotime($row['startDate']));
                    $end = date("d/m/Y", strtotime($row['endDate']));
                    echo <<<_END
                <div class="this-project">
                                <h3>Project Details</h3>
                                <h5>Title</h5>
                                <p>$row[project_title]</p>
                                <h5>Project Manager</h5>
                                <p>$row[fullname]</p>
                                <h5>Client</h5>
                                <p>$row[cfullname]</p>
                                <h5>Overview</h5>
_END;
                    echo "<p>" . nl2br($row['overview']) . "</p>";
                    echo <<<_END
                                <h5>Aim</h5>
                                <p>$row[aim]</p>
                                <h5>Objectives</h5>
_END;
                    echo "<p>" . nl2br($row['objectives']) . "</p>";
                    echo <<<_END
                                <h5>Duration</h5>
                                <p>$start - $end</p>
                                <h4>Milestones</h4>
_END;
                    $getMilestone = $db->pdo->prepare('SELECT * FROM milestone WHERE project_id = :id ORDER BY deadline ASC');
                    $getMilestone->execute(array(':id' => $row['project_id']));
                    while ($milestone = $getMilestone->fetch(PDO::FETCH_ASSOC)) {
                        $mstart = date("d/m/Y", strtotime($milestone['mstart']));
                        $mend = date("d/m/Y", strtotime($milestone['deadline']));
                        echo <<<_END
                                <div class="milestone">
                                    <h5>$milestone[mtitle]<b class="pull-right">$mstart - $mend</b></h5>
_END;
                        $gettask = $db->pdo->prepare('SELECT * FROM task WHERE milestone_id = :id ORDER BY tendDate ASC');
                        $gettask->execute(array(':id' => $milestone['milestone_id']));
                        while ($task = $gettask->fetch(PDO::FETCH_ASSOC)) {
                            if ($task['task_status'] == 'To Do') {
                                $status = "<label class='label label-danger pull-right'>To Do</label>";
                            } elseif ($task['task_status'] == "In Progress") {
                                $status = "<label class='label label-primary pull-right'>In Progress</label>";
                            } else {
                                $status = "<label class='label label-success pull-right'>Completed</label>";
                            }
                            echo <<<_END
                            
                                    <div class="task">
                                        <h6><b>&#8618; $task[task_title]</b>$status</h6>
                                        <p>$task[task_desc]</p>
                                    </div>
_END;
                        }
                        $getComment = $db->pdo->prepare('SELECT * FROM comment WHERE milestone_id = :id ORDER BY comment_date ASC');
                        $getComment->execute(array(':id' => $milestone['milestone_id']));
                        while ($comment = $getComment->fetch(PDO::FETCH_ASSOC)) {
                            $cdate = date("d/m/Y", strtotime($comment['comment_date']));
                            echo <<<_END
                                <div class="comment">
                                    <p class="comment"><i class="fa fa-comment" aria-hidden="true"></i>$comment[comment_text]<b class="pull-right"><i class="fa fa-user" aria-hidden="true"></i>$comment[fullname] ($cdate)</b></p>
                                </div>
_END;
                        }
                        echo <<<_END
                                <button type="submit" class="new-comment" name="$milestone[milestone_id]">Comment</button>
                                <div class="clearfix"></div>
                                </div>
_END;
                    }
                    echo <<<_END
                    
                            </div>
_END;
                }
            }
        } catch (PDOException $e) {
            header("location:../404.php");
            // echo "Error: ".$e."<br>";
        }
    }

    public function getThisProjectEdit($slug)
    {
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT project_details.project_id, project_details.project_manager, project_details.customer, project.project_title, project.startDate, project.endDate, project.project_status, project.overview, project.aim, project.objectives, project.slug, customer.cfullname, employee.fullname, project_staff.s_username, project_staff.p_id FROM project_details LEFT JOIN employee ON project_details.project_manager = employee.username LEFT JOIN customer ON project_details.customer = customer.username LEFT JOIN project ON project_details.project_id = project.project_id LEFT OUTER JOIN project_staff ON project_details.project_id = project_staff.p_id WHERE (project_details.project_manager = :username OR project_details.customer = :username OR project_staff.s_username = :username) AND project.slug = :slug GROUP BY project.project_id");
            $result->execute(array(':username' => $_SESSION['username'], ':slug' => $slug));
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            if (empty($rows)) {
                header('location:../404.php');
            } else {
                while ($row = array_shift($rows)) {
                    echo <<<_END
                        <div class="man-project">
                            <label>Manage Project:</label>
                            <button type="submit" name="$row[project_id]" class="e-details">Edit</button>
                            <button type="submit" name="$row[project_id]" class="dproject">Delete</button>
                        </div>
_END;
                }
            }
            $db = new db();
            $result = $db->pdo->prepare("SELECT project_details.project_id, project_details.project_manager, project_details.customer, project.project_title, project.startDate, project.endDate, project.project_status, project.overview, project.aim, project.objectives, project.slug, customer.cfullname, employee.fullname, project_staff.s_username, project_staff.p_id FROM project_details LEFT JOIN employee ON project_details.project_manager = employee.username LEFT JOIN customer ON project_details.customer = customer.username LEFT JOIN project ON project_details.project_id = project.project_id LEFT OUTER JOIN project_staff ON project_details.project_id = project_staff.p_id WHERE (project_details.project_manager = :username OR project_details.customer = :username OR project_staff.s_username = :username) AND project.slug = :slug GROUP BY project.project_id");
            $result->execute(array(':username' => $_SESSION['username'], ':slug' => $slug));
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            if (empty($rows)) {
                header('location:../404.php');
            } else {
                echo <<<_END

                        <div class="table-responsive">
                        <table class="table table-bordered edit-table">
                        <thead>
                        <tr><th>Title</th><th>Details</th></tr>
                        </thead>
                        <tbody>
_END;
                while ($row = array_shift($rows)) {
                    $start = date("d/m/Y", strtotime($row['startDate']));
                    $end = date("d/m/Y", strtotime($row['endDate']));
                    $overview = nl2br($row['overview']);
                    $objectives = nl2br($row['objectives']);
                    echo <<<_END
                                <tr><td colspan="2"><b>Project Details</b></td></tr>
                                <tr><td><b>Title:</b></td><td>$row[project_title]</td></tr>
                                <tr><td><b>Project Manager:</b></td><td>$row[fullname]</td></tr>
                                <tr><td><b>Client:</b></td><td>$row[cfullname]</td></tr>
                                <tr><td><b>Duration:</b></td><td>$start - $end</td></tr>
                                <tr><td><b>Aim:</b></td><td>$row[aim]</td></tr>
                                <tr><td><b>Objectives:</b></td><td>$objectives</td></tr>
                                <tr><td><b>Overview:</b></td><td>$overview</td></tr>
                                <tr><td><b>Status:</b></td><td>$row[project_status]</td></tr>
_END;
                }
            }
        } catch (PDOException $e) {
            header("location:../404.php");
            //echo "Error: ".$e."<br>";
        }
        echo <<<_END
        </tbody>
        </table>
        </div>
_END;
    }

    public function getProjectTitles()
    {
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT * FROM project_details LEFT JOIN project ON project_details.project_id = project.project_id WHERE project_details.project_manager = :username");
            $result->execute(array(':username' => $_SESSION['username']));
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            if (empty($rows)) {
                echo "<option>You do not have any projects.</option>";
            } else {
                echo "<option selected>Select Project</option>";
                while ($row = array_shift($rows)) {
                    echo <<<_END
                        <option value="$row[project_id]">$row[project_title]</option>
_END;
                }
            }
        } catch (PDOException $e) {
            header("location:../404.php");
            //echo "Error: ".$e."<br>";
        }
    }

    public function getProjectDates($id)
    {
        try {
            $db = new db();
            $getDates = $db->pdo->prepare("SELECT startDate, endDate FROM project WHERE project_id = :id");
            $getDates->execute(array(':id' => $id));
            while ($row = $getDates->fetch(PDO::FETCH_ASSOC)) {
                $start = date("Y-m-d", strtotime($row['startDate']));
                $end = date("Y-m-d", strtotime($row['endDate']));
                echo <<<_END
                            <div class="form-group col-sm-6">
                                <label for="start2">Initiation Date</label>
                                <input type="date" class="form-control" name="start2" id="start2" min="$start" max="$end" required>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="end2">Completion Date</label>
                                <input type="date" class="form-control" name="end2" id="end2" min="$start" max="$end" required>
                            </div>
_END;
            }
        } catch (PDOException $e) {
            header("location:../404.php");
            //echo "Error: ".$e."<br>";
        }
    }

    public function getProjectDetails($id)
    {
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT project_details.project_manager, project_details.customer, project_details.project_id, project.project_id, project.project_title, project.startDate, project.endDate, project.project_status, project.overview, customer.username, customer.cfullname FROM project_details LEFT JOIN project ON project_details.project_id = project.project_id LEFT JOIN customer ON project_details.customer = customer.username WHERE project_details.project_id = :id");
            $result->execute(array(':id' => $id));
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $start = date("m/d/Y", strtotime($row['startDate']));
                $end = date("m/d/Y", strtotime($row['endDate']));
                echo <<<_END
                    <div id="overlay">
                        <div class="form-container form-container-e">
                            <form method="post" action="./classes/project.php">
                               <div class="dialog-content clearfix">
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label for="title">Title</label>
                                            <input type="text" class="form-control" name="title" id="title" value="$row[project_title]">
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="customer">Client</label>
                                            <select name="customer" id="customer" class="form-control" required>
                                                <option value"$row[username]">$row[cfullname]</option>
_END;
                $customer = new customer();
                $customer->getCustomers();
                echo <<<_END
                                            <option value="Other">Other</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="start">Initiation Date</label>
                                            <input type="text" class="form-control datepicker" name="start" id="start" value="$start" required>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="end">Completion Date</label>
                                            <input type="text" class="form-control datepicker" name="end" id="end" value="$end" required>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="status">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="$row[project_status]">$row[project_status]</option>
                                                <option value="Open">Open</option>
                                                <option value="Closed">Closed</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="overview">Overview</label>
                                            <textarea class="form-control" id="overview" name="overview" rows="3" cols="100">$row[overview]</textarea>
                                        </div>
                                    </div>
                                    <input type="hidden" value="$id" name="id">
                                </div>
                                <div class="dialog-buttons">
                                    <div class="row">
                                        <button type="submit" name="updateproject" class="update-yes">Update</button>
                                        <button type="button" onclick="edit_modal()" class="update-no">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div> 
_END;
            }
        } catch (PDOException $e) {
            header("location:../404.php");
            //echo "Error: ".$e."<br>";
        }
    }

    public function assignEmployee($employee, $id)
    {
        try {
            $db = new db();
            $check = $db->pdo->prepare("SELECT * FROM project_staff WHERE p_id = :id AND s_username = :staff");
            $check->execute(array(':id' => $id, ':staff' => $employee));
            $result = $check->fetch(PDO::FETCH_ASSOC);

            if ($result > 0) {
                header('location:../dashboard.php');
                $_SESSION['alert'] = 'ae1';
            } else {
                $stmt = $db->pdo->prepare("INSERT INTO project_staff (p_id, s_username) VALUES (:id, :staff)");
                $stmt->execute(array(':id' => $id, ':staff' => $employee));
                if ($stmt == true) {
                    header('location:../dashboard.php');
                    $_SESSION['alert'] = 'a4';
                } else {
                    header('location:../400.php');
                }
            }
        } catch (PDOException $e) {
            header("location:../400.php");
            //echo "Error: ".$e."<br>";
        }
    }

    function transferProject($employee, $project)
    {
        try {
            $db = new db();
            $transferProject = $db->pdo->prepare("INSERT INTO transfers (project_id, UserFrom, UserTo) VALUES (:id, :username, :user); UPDATE project_details SET project_manager = :username WHERE project_id = :id AND project_manager = :user");
            $transferProject->execute(array(':id' => $project, ':user' => $_SESSION['username'], ':username' => $employee));
            if ($transferProject == true) {
                header('location:../dashboard.php');
                $_SESSION['alert'] = 'transfer';
            } else {
                header('location:../400.php');
            }
        } catch (PDOException $e) {
            header("location:../400.php");
            //echo "Error: ".$e."<br>";
        }
    }

    function getTransfers()
    {
        try {
            $db = new db();
            echo <<<_END
            <div class="table-responsive">
            <table class="table">
            <thead>
                <tr><th>Title</th><th>From</th><th>To</th><th>On</th></tr>
            </thead>
            <tbody>
_END;
            $getTransfers = $db->pdo->query("SELECT transfers.project_id, transfers.UserFrom, transfers.UserTo, transfers.TransferDate, emp1.username, emp2.username, emp1.fullname AS UFrom, emp2.fullname AS UTo, project.project_id, project.project_title FROM transfers LEFT JOIN employee emp1 ON transfers.UserFrom = emp1.username LEFT JOIN employee emp2 ON transfers.UserTo = emp2.username LEFT JOIN project ON transfers.project_id = project.project_id");
            while ($row = $getTransfers->fetch(PDO::FETCH_ASSOC)) {
                $split = explode(' ', $row['TransferDate']);
                $date = $split[0];
                $time = $split[1];
                $date = date("d/m/Y", strtotime($date));
                $time = date("H:m", strtotime($time));
                echo <<<_END
                    <tr><td data-label="Title">$row[project_title]</td><td data-label="From">$row[UFrom]</td><td data-label="To">$row[UTo]</td><td data-label="On">$time $date</td></tr>
_END;
            }
        } catch (PDOException $e) {
            header("location:../404.php");
            //echo "Error: ".$e."<br>";
        }
    }

    public function getThisProjectDelete($id)
    {
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT project_id, project_title FROM project WHERE project_id = :id");
            $result->execute(array(':id' => $id));
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo <<<_END
                    <div id="overlay">
                        <div class="form-container form-container-d">
                            <form method="post" action ="classes/project.php">
                               <div class="dialog-content clearfix">
                                    <p>Are you sure you want to delete project: <strong>$row[project_title]</strong>?</p>
                                    <p class="text-important">This action cannot be undone.</p>
                                    <input type="hidden" value="$row[project_id]" name="id">
                                </div>
                                <div class="dialog-buttons">
                                    <div class="row">
                                        <button type="submit" name="sdeleteproject" class="delete-yes">Yes</button>
                                        <button type="button" onclick="edit_modal()" class="delete-no">No</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
_END;
            }
        } catch (PDOException $e) {
            header("location:../400.php");
            //echo "Error: " . $e . "<br>";
        }
    }

    public function deleteThisProject($id)
    {
        try {
            $db = new db();
            $stmt = $db->pdo->prepare("DELETE FROM project WHERE project_id = :id");
            $stmt->execute(array(':id' => $id));
            if ($stmt == true) {
                header('location:../index.php');
                $_SESSION['alert'] = 'a6';
            } else {
                header('location:../400.php');
            }
        } catch (PDOException $e) {
            header("location:../400.php");
            //echo "Error: " . $e . "<br>";
        }
    }

    public function getBudget($id)
    {
        try {
            $db = new db();
            $getBudget = $db->pdo->prepare("SELECT project.budget, project_details.expenses FROM project LEFT JOIN project_details ON project.project_id = project_details.project_id WHERE project.project_id = :id");
            $getBudget->execute(array(':id' => $id));
            while ($budget = $getBudget->fetch(PDO::FETCH_ASSOC)) {
                $remaining = $budget['budget'] - $budget['expenses'];
                $totalSpend = $budget['expenses'];
                $diff = number_format($remaining);
                $budget1 = number_format($budget['budget']);
                $totalSpend2 = number_format($totalSpend);
                if ($totalSpend < $budget['budget']) {
                    echo <<<_END
                    <p class="under"><b>Allocated: £$totalSpend2/£$budget1 (£$diff remaining)</b></p>
_END;
                } else {
                    echo <<<_END
                    <p class="over"><b>Allocated: £$totalSpend2/£$budget1 (£$diff remaining)</b></p>
_END;
                }
                echo <<<_END
            <input type="number" class="form-control" name="allbudget" id="allbudget" value="">
_END;
            }
        } catch (PDOException $e) {
            header("location:../404.php");
            //echo "Error: " . $e . "<br>";
        }
    }

    public function getPStaff($id)
    {
        try {
            $db = new db();
            $getStaff = $db->pdo->prepare("SELECT project_staff.p_id, project_staff.s_username, project.project_id, employee.username, employee.fullname, employee.role FROM project_staff LEFT JOIN project ON project_staff.p_id = project.project_id LEFT JOIN employee ON project_staff.s_username = employee.username WHERE project_staff.p_id = :id");
            $getStaff->execute(array(':id' => $id));
            while ($staff = $getStaff->fetch(PDO::FETCH_ASSOC)) {
                echo <<<_END
                    <option value="$staff[s_username]">$staff[fullname] ($staff[role])</option>
_END;
            }
        } catch (PDOException $e) {
            header("location:../404.php");
            //echo "Error: " . $e . "<br>";
        }
    }

    function uploadFile($proj_id, $file_name)
    {
        try {
            $db = new db();
            $uploadFile = $db->pdo->prepare("INSERT INTO files (filename, project_id, uploaded_by) VALUES (:filename, :project, :username)");
            $uploadFile->execute(array(':filename' => $file_name, ':project' => $proj_id, ':username' => $_SESSION['username']));
            if ($uploadFile == true) {
                header('location:../files.php');
                $_SESSION['alert'] = 'f1';
            } else {
                header('location:../400.php');
            }
        } catch (PDOException $e) {
            header("location:../400.php");
            //echo "Error: " . $e . "<br>";
        }
    }

    function getFiles()
    {
        try {
            echo <<<_END
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr><th>File Name</th><th>Upload Date</th><th>Author</th><th>Manage</th></tr>
                            </thead>
                            <tbody>
_END;
            $db = new db();
            $getProjects = $db->pdo->prepare("SELECT project_details.project_manager, project_details.customer, project_details.project_id, project.project_id, project.project_title FROM project_details LEFT OUTER JOIN project_staff ON project_details.project_id = project_staff.p_id LEFT JOIN project ON project_details.project_id = project.project_id WHERE project_details.project_manager = :username OR project_details.customer = :username OR project_staff.s_username = :username;");
            $getProjects->execute(array(':username' => $_SESSION['username']));
            $count = $getProjects->fetchAll(PDO::FETCH_ASSOC);
            if (empty($count)) {
                echo "You do not have any projects.";
            } else {
                while ($proj = array_shift($count)) {
                    $getFiles = $db->pdo->prepare("SELECT files.filename, files.project_id, files.uploaded_by, files.uploaded_date, employee.username, employee.fullname FROM files LEFT JOIN employee ON files.uploaded_by = employee.username WHERE files.project_id = :project_id ORDER BY files.project_id");
                    $getFiles->execute(array(':project_id' => $proj['project_id']));
                    $rows = $getFiles->fetchAll(PDO::FETCH_ASSOC);
                    if (empty($rows)) {
                        echo "You do not have any files.";
                    } else {
                        if ($handle = opendir('files/projects')) {
                            $filelist = "";
                            while ($row = array_shift($rows)) {
                                $uploadDate = date("H:i, d/m/Y", strtotime($row['uploaded_date']));
                                if ($row['filename'] != "." && $row['filename'] != "..") {
                                    $filelist .= "<tr><td data-label='File Name'><a href='f-" . $row['filename'] . "'>" . $row['filename'] . "</a></td><td data-label='Upload Date'>" . $uploadDate . "</td><td data-label='Author'>" . $row['fullname'] . "</td><td data-label='Manage'><button type='submit' class='dfile' name='" . $row['project_id'] . "," . $row['filename'] . "'>Delete</button></td></tr>";
                                }
                            }
                            closedir($handle);
                        }
                    }
                }
                echo $filelist . "</tbody></table></div>";
            }
        } catch (PDOException $e) {
            header("location:../404.php");
            //echo "Error: " . $e . "<br>";
        }
    }

    function getThisFileDelete($proj_id, $filename)
    {
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT filename, project_id FROM files WHERE project_id = :id AND filename = :file");
            $result->execute(array(':id' => $proj_id, ':file' => $filename));
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo <<<_END
                    <div id="overlay">
                        <div class="form-container form-container-d">
                            <form method="post" action ="classes/project.php">
                               <div class="dialog-content clearfix">
                                    <p>Are you sure you want to delete this file: <strong>$row[filename]</strong>?</p>
                                    <input type="hidden" value="$row[project_id]" name="project">
                                    <input type="hidden" value="$row[filename]" name="filename">
                                </div>
                                <div class="dialog-buttons">
                                    <div class="row">
                                        <button type="submit" name="sdeletefile" class="delete-yes">Yes</button>
                                        <button type="button" onclick="edit_modal()" class="delete-no">No</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
_END;
            }
        } catch (PDOException $e) {
            header("location:../404.php");
            //echo "Error: " . $e . "<br>";
        }

    }

    function deleteFile($proj_id, $filename)
    {
        try {
            $db = new db();
            unlink("../files/projects/" . $filename);
            $deleteFile = $db->pdo->prepare("DELETE FROM files WHERE filename = :file AND project_id = :project_id");
            $deleteFile->execute(array(':file' => $filename, ':project_id' => $proj_id));
            if ($deleteFile == true) {
                header('location:../files.php');
                $_SESSION['alert'] = 'f2';
            } else {
                header("location:../400.php");
            }
        } catch (PDOException $e) {
            header("location:../400.php");
            //echo "Error: " . $e . "<br>";
        }
    }

    function reportIssue($proj_id, $issue, $status, $showclient)
    {
        try {
            $db = new db();
            $reportIssue = $db->pdo->prepare("INSERT INTO issues (project_id, description, issueStatus, showClient, reported_by) VALUES (:project, :description, :issuestatus, :showClient, :user)");
            $reportIssue->execute(array(':project' => $proj_id, ':description' => $issue, ':issuestatus' => $status, ':showClient' => $showclient, ':user' => $_SESSION['username']));
            if ($reportIssue == true) {
                echo "<script>window.history.go(-1);</script>";
                $_SESSION['alert'] = 're2';
            } else {
                header("location:../400.php");
            }
        } catch (PDOException $e) {
            //header("location:../400.php");
            echo "Error: " . $e . "<br>";
        }
    }

    function getIssues()
    {
        try {
            $db = new db();
            //Instead of grouping results by project, group by issue descriptions instead
            $getIssues = $db->pdo->prepare("SELECT project.project_id, project.project_title, project.startDate, project.endDate, project.project_status, project.slug, project_details.project_manager, project_details.customer, project_staff.p_id, project_staff.s_username, employee.staff_id, employee.username, employee.fullname, customer.username, customer.cfullname, issues.issue_id, issues.project_id, issues.description, issues.issueRead, issues.issueStatus, issues.showClient, issues.reported_at FROM project_details LEFT JOIN employee ON project_details.project_manager = employee.username LEFT JOIN customer ON project_details.customer = customer.username LEFT JOIN project ON project_details.project_id = project.project_id RIGHT OUTER JOIN issues ON project_details.project_id = issues.project_id LEFT OUTER JOIN project_staff ON project_details.project_id = project_staff.p_id WHERE project_details.project_manager = :user OR project_details.customer = :user OR project_staff.s_username = :user GROUP BY issues.issue_id ORDER BY issues.issueStatus ASC");
            $getIssues->execute(array(':user' => $_SESSION['username']));
            $results = $getIssues->fetchAll(PDO::FETCH_ASSOC);
            if (empty($results)) {
                echo "<div class='issues'><h5>Issues</h5><div class='issue'><p>There are no issues to display at this time.</p></div></div>";
            } else {
                $issuecount = 0;
                echo <<<_END
                <div class="issues">
                    <h5>Issues</h5>
_END;
                while ($row = array_shift($results)) {
                    //If the user has not hidden the issue
                    if (strpos($row['issueRead'], $_SESSION['username']) === false) {
                        //If the user is a client and the issue is hidden from client users
                        if ($_SESSION['access'] == 1 && $row['showClient'] == 'no') {
                            //Do nothing
                        } //Otherwise, show issue
                        else {
                            $issuecount = $issuecount + 1;
                            $label = 'raised';
                            if ($row['issueStatus'] == 'raised') {
                                $label = "<span class='label label-warning pull-right'>Raised</span>";
                            } else if ($row['issueStatus'] == 'escalated') {
                                $label = "<span class='label label-danger pull-right'>Escalated</span>";
                            } else if ($row['issueStatus'] == 'resolved') {
                                $label = "<span class='label label-info pull-right'>Resolved</span>";
                            }
                            echo <<<_END
                        <div class="issue">
                            <p>$row[description] $label (<a href="classes/project.php?issue=$row[project_id],$row[description]">Hide</a>)</p>
                        </div>      
_END;
                        }
                    }
                }
                if ($issuecount < 1) {
                    echo "<div class='issue'><p>There are issues you have hidden (<a href='classes/project.php?showall'>Show All</a>)</p></div>";
                }
                echo <<<_END
                </div>
_END;
            }
        } catch (PDOException $e) {
            header("location:../400.php");
            //echo "Error: " . $e . "<br>";
        }
    }

    function getallIssues($slug)
    {
        try {
            $db = new db();
            $getIssueList = $db->pdo->prepare("SELECT issues.issue_id, issues.project_id, issues.description, project.project_id, project.slug FROM issues LEFT JOIN project ON issues.project_id = project.project_id WHERE project.slug = :slug ORDER BY issues.issueStatus ASC");
            $getIssueList->execute(array(':slug' => $slug));
            $issues = $getIssueList->fetchAll(PDO::FETCH_ASSOC);
            if (empty($issues)) {
                //Do nothing
            } else {
                //If user is Project Manager
                if ($_SESSION['access'] == 3) {
                    echo <<<_END
                    <div class="man-issues">
                        <label for="issue-list">Manage Issues:</label>
                        <select name="issue-list" id="issue-list" required>
_END;
                    while ($issue = array_shift($issues)) {
                        echo <<<_END
                            <option value="$issue[issue_id],$issue[project_id]">$issue[description]</option>
_END;
                    }
                    echo <<<_END
                        </select>
                    <button type="submit" class="eissue" name="eissue">Edit</button>
                    <button type="submit" class="dissue" name="dmissue">Delete</button>
                    </div>
_END;
                }
            }
            $getIssues = $db->pdo->prepare("SELECT issues.issue_id, issues.project_id, issues.description, issues.issueStatus, issues.showClient, issues.reported_by, issues.reported_at, project.project_id, project.slug, employee.username, employee.fullname FROM issues LEFT JOIN project ON issues.project_id = project.project_id LEFT JOIN employee ON issues.reported_by = employee.username WHERE project.slug = :slug ORDER BY issues.issueStatus ASC");
            $getIssues->execute(array(':slug' => $slug));
            $fullissues = $getIssues->fetchAll(PDO::FETCH_ASSOC);
            if (empty($fullissues)) {
                echo "This project does not have any issues.";
            } else {
                echo <<<_END
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr><th>Description</th><th>Status</th><th>Client</th><th>Reported</th></tr>
                            </thead>
                            <tbody>
_END;
                while ($issue = array_shift($fullissues)) {
                    $reported_at = date("H:m d/m/Y", strtotime($issue['reported_at']));
                    $reported = $issue['fullname'] . ", " . $reported_at;
                    if ($issue['issueStatus'] == 'raised') {
                        $status = 'Raised';
                    } else if ($issue['issueStatus'] == 'escalated') {
                        $status = 'Escalated';
                    } else {
                        $status = 'Resolved';
                    }
                    if ($issue['showClient'] == 'yes') {
                        $showclient = 'Yes';
                    } else {
                        $showclient = 'No';
                    }
                    echo <<<_END
                    <tr><td>$issue[description]</td><td>$status</td><td>$showclient</td><td>$reported</td></tr>
_END;
                }
                echo <<<_END
                        </tbody>
                    </table>
                </div>
_END;
            }
        } catch (PDOException $e) {
            //header("location:../400.php");
            echo "Error: " . $e . "<br>";
        }
    }

    function hideIssue($projectID, $issueDesc)
    {
        try {
            $db = new db();
            $thisusername = $_SESSION['username'] . ', ';
            $hideIssue = $db->pdo->prepare("UPDATE issues SET issueRead = CONCAT(issueRead, :user) WHERE project_id = :ID AND description = :desc");
            $hideIssue->execute(array(':user' => $thisusername, ':ID' => $projectID, ':desc' => $issueDesc));
            if ($hideIssue == true) {
                echo "<script>window.history.go(-1);</script>";
                $_SESSION['alert'] = 're3';
            } else {
                header("location:../400.php");
            }
        } catch (PDOException $e) {
            header("location:../400.php");
            //echo "Error: " . $e . "<br>";
        }
    }

    function showallIssues()
    {
        try {
            $db = new db();
            $thisusername = $_SESSION['username'] . ', ';
            $showIssues = $db->pdo->prepare("SELECT project_id, description FROM issues WHERE INSTR(issueRead, :user)");
            $showIssues->execute(array(':user' => $_SESSION['username']));
            while ($row = $showIssues->fetch(PDO::FETCH_ASSOC)) {
                $showthisissue = $db->pdo->prepare("UPDATE issues SET issueRead = REPLACE(issueRead, :user, '') WHERE project_id = :ID AND description = :desc");
                $showthisissue->execute(array(':user' => $thisusername, ':ID' => $row['project_id'], ':desc' => $row['description']));
            }
            if ($showthisissue == true) {
                echo "<script>window.history.go(-1);</script>";
                $_SESSION['alert'] = 're4';
            } else {
                header("location:../400.php");
            }
        } catch (PDOException $e) {
            echo "Error: Could not load issues.";
            //echo "Error: " . $e . "<br>";
        }
    }

    function getIssueDetails($issueID, $projectID)
    {
        try {
            $db = new db();
            $getIssue = $db->pdo->prepare("SELECT issues.issue_id, issues.project_id, issues.description, issues.issueStatus, issues.showClient, issues.reported_by, issues.reported_at, project.project_title FROM issues LEFT JOIN project ON issues.project_id = project.project_id WHERE issues.issue_id = :issue AND issues.project_id = :project");
            $getIssue->execute(array(':issue' => $issueID, ':project' => $projectID));
            while ($issue = $getIssue->fetch(PDO::FETCH_ASSOC)) {
                if ($issue['issueStatus'] == 'raised') {
                    $status = 'Raised';
                } else if ($issue['issueStatus'] == 'escalated') {
                    $status = 'Escalated';
                } else {
                    $status = 'Resolved';
                }
                if ($issue['showClient'] == 'yes') {
                    $showclient = 'Yes';
                } else {
                    $showclient = 'No';
                }
                echo <<<_END
                    <div id="overlay">
                        <div class="form-container form-container-e">
                            <form method="post" action="classes/project.php">
                               <div class="dialog-content clearfix">
                                    <h4>Update Issue</h4>
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label for="project">Project</label>
                                            <input type="text" disabled value="$issue[project_title]" class="form-control">
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="description">Description</label>
                                            <textarea rows="5" class="form-control" name="idesc" id="idesc" maxlength="500" required>$issue[description]</textarea>
                                        </div>
                                       <div class="form-group col-sm-6">
                                            <label for="istatus">Issue Status</label>
                                            <select name="istatus" id="istatus" class="form-control" required>
                                                <option value="$issue[issueStatus]" selected>$status</option>
                                                <option value="raised">Raised</option>
                                                <option value="escalated">Escalated</option>
                                                <option value="resolved">Resolved</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="showclient">Notify Client</label>
                                            <select name="showclient" id="showclient" class="form-control" required>
                                                <option value="$issue[showClient]" selected>$showclient</option>
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>
                                        <input type="hidden" value="$issue[project_id]" name="project">
                                        <input type="hidden" value="$issue[issue_id]" name="issue">
                                    </div>
                                </div>
                                <div class="dialog-buttons">
                                    <div class="row">
                                        <button type="submit" name="updateissue" class="update-yes">Update</button>
                                        <button type="button" onclick="edit_modal()" class="update-no">Cancel</button>
                                    </div>
                                </div>
                             </form>
                         </div>
                    </div>
_END;
            }
        } catch (PDOException $e) {
            header("location:../400.php");
            //echo "Error: " . $e . "<br>";
        }
    }

    function updateIssue($proj_id, $issue_id, $issue, $status, $showclient)
    {
        try {
            $db = new db();
            $updateIssue = $db->pdo->prepare("UPDATE issues SET description = :desc, issueStatus = :status, showClient = :showclient WHERE issue_id = :issue AND project_id = :project");
            $updateIssue->execute(array(':desc' => $issue, ':status' => $status, ':showclient' => $showclient, ':issue' => $issue_id, ':project' => $proj_id));
            if ($updateIssue == true) {
    echo "<script>window.history.go(-1);</script>";
    $_SESSION['alert'] = 're5';
} else {
    header("location:../400.php");
}
} catch (PDOException $e) {
    //header("location:../400.php");
    echo "Error: " . $e . "<br>";
}
    }

    function getThisIssueDelete($issue, $project)
    {
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT issue_id, project_id, description FROM issues WHERE issue_id = :id AND project_id = :id2");
            $result->execute(array(':id' => $issue, ':id2' => $project));
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo <<<_END
                    <div id="overlay">
                        <div class="form-container form-container-d">
                            <form method="post" action ="classes/project.php">
                               <div class="dialog-content clearfix">
                                    <p>Are you sure you want to delete issue: <strong>$row[description]</strong>?</p>
                                    <input type="hidden" value="$row[issue_id]" name="issue">
                                    <input type="hidden" value="$row[project_id]" name="project">
                                </div>
                                <div class="dialog-buttons">
                                    <div class="row">
                                        <button type="submit" name="sdeleteissue" class="delete-yes">Yes</button>
                                        <button type="button" onclick="edit_modal()" class="delete-no">No</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
_END;
            }
        } catch (PDOException $e) {
            header('location:../404.php');
        }
    }

    public function deleteThisIssue($issue, $project)
{
    try {
        $db = new db();
        $stmt = $db->pdo->prepare("DELETE FROM issues WHERE issue_id = :id AND project_id = :id2");
        $stmt->execute(array(':id' => $issue, ':id2' => $project));
        if ($stmt == true) {
            echo "<script>window.history.go(-1);</script>";
            $_SESSION['alert'] = 're6';
        } else {
            header('location:../400.php');
        }
    } catch (PDOException $e) {
        //header("location:../400.php");
        echo "Error: " . $e . "<br>";
    }
}
}