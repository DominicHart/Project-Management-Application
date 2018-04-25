<?php
if (session_status() == PHP_SESSION_NONE){
    session_start();
};
include_once ($_SERVER['DOCUMENT_ROOT'].'./fyp/includes/config.php');
$milestone = new milestone();
if(isset($_POST['smilestone'])) {
    $id = filter_var($_POST['project'], FILTER_SANITIZE_STRING);
    $title = filter_var($_POST['mtitle'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['mdescription'], FILTER_SANITIZE_STRING);
    $start = filter_var($_POST['mstart'], FILTER_SANITIZE_STRING);
    $start = date('Y-m-d', strtotime($start));
    $deadline = filter_var($_POST['deadline'], FILTER_SANITIZE_STRING);
    $deadline = date('Y-m-d', strtotime($deadline));
    $milestone->addMilestone($id, $title, $description, $start, $deadline);
}
if(isset($_POST['updatemilestone'])) {
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['desc'], FILTER_SANITIZE_STRING);
    $start = filter_var($_POST['start'], FILTER_SANITIZE_STRING);
    $start = date('Y-m-d', strtotime($start));
    $deadline = filter_var($_POST['deadline'], FILTER_SANITIZE_STRING);
    $deadline = date('Y-m-d', strtotime($deadline));
    $completed = filter_var($_POST['complete'], FILTER_SANITIZE_STRING);
    $milestoneID = filter_var($_POST['milestone'], FILTER_SANITIZE_STRING);
    $projectID = filter_var($_POST['project'], FILTER_SANITIZE_STRING);
    $milestone->UpdateMilestone($title, $description, $start, $deadline, $completed, $milestoneID, $projectID);
}
if(isset($_POST['sdeletemilestone'])) {
    $milestoneID = filter_var($_POST['milestone'], FILTER_SANITIZE_STRING);
    $projectID = filter_var($_POST['project'], FILTER_SANITIZE_STRING);
    $milestone->deleteMilestone($milestoneID, $projectID);
}
class milestone {
    function __construct(){}
    function getMilestones($slug) {
        try {
            $db = new db();
            $getMilestoneTitles = $db->pdo->prepare("SELECT milestone.milestone_id, milestone.project_id, milestone.mtitle, project.project_id, project.slug FROM milestone LEFT JOIN project ON milestone.project_id = project.project_id WHERE project.slug = :slug ORDER BY milestone.deadline ASC");
            $getMilestoneTitles->execute(array(':slug' => $slug));
            $titles = $getMilestoneTitles->fetchAll(PDO::FETCH_ASSOC);
            if (empty($titles)) {
            } else {
                //If user is Project Manager
                if($_SESSION['access'] == 3) {
                    echo <<<_END
                    <div class="man-milestones">
                        <label for="milestone-list">Manage Milestones:</label>
                        <select name="milestone-list" id="milestone-list" required>
_END;
                    while ($title = array_shift($titles)) {
                        echo <<<_END
                    <option value="$title[milestone_id],$title[project_id]">$title[mtitle]</option>
_END;
                    }
                    echo <<<_END
                        </select>
                    <button type="submit" class="emilestone" name="emilestone">Edit</button>
                    <button type="submit" class="dmilestone" name="dmilestone">Delete</button>
                    </div>
_END;
                }
            }
            $result = $db->pdo->prepare("SELECT milestone.milestone_id, milestone.project_id, milestone.mtitle, milestone.mdescription, milestone.mstart, milestone.deadline, milestone.completed, project.project_id, project.slug FROM milestone LEFT JOIN project ON milestone.project_id = project.project_id WHERE project.slug = :slug ORDER BY milestone.deadline ASC");
            $result->execute(array(':slug' => $slug));
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            if(empty($rows)) {
                echo "This project does not have any milestones.";
            }
            else {
                echo <<<_END
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr><th>Title</th><th>Description</th><th>Start</th><th>Deadline</th><th>Completed</th></tr>
                    </thead>
                    <tbody>
_END;
                while($row = array_shift($rows)) {
                    $today = date("Y-m-d");
                    $start = date("d/m/Y", strtotime($row['mstart']));
                    $deadline = date("d/m/Y", strtotime($row['deadline']));
                    $newdeadline = date_create($row['deadline']);
                    $completeDate = '';
                    $diff = '';
                    if(isset($row['completed'])) {
                        $completed = date("d/m/Y", strtotime($row['completed']));
                        $newcompleted = date_create($row['completed']);
                        $difference = date_diff($newdeadline,$newcompleted);
                        if(strtotime($row['completed']) > strtotime($row['deadline'])) {
                            $completeDate = 'completedOver';
                            $diff = $difference->format("(%R%a days late)");
                        }
                        else {
                            $completeDate = 'completedOk';
                        }
                    }
                    else {
                        $completed = "N/A";
                    }
                    if($today > $row['deadline'] && !isset($row['completed'])) {
                        $today = date_create($today);
                        $late = date_diff($newdeadline,$today);
                        $howlate = $late->format("<b>%a days</b>");
                        echo "<div class='alert alert-warning alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-exclamation-circle' aria-hidden='true'></i>Milestone: ".$row['mtitle'].", is running behind by ".$howlate."</a></div>";
                    }
                    echo <<<_END
                    <tr><td data-label="Title">$row[mtitle]</td><td data-label="Description">$row[mdescription]</td><td data-label="Start">$start</td><td data-label="Deadline">$deadline</td><td data-label="Completed" class="$completeDate">$completed $diff</td></tr>
_END;
                }
                echo <<<_END
                </tbody>
                </table>
            </div>
_END;
            }
        } catch (PDOException $e) {
            echo "Error: Could not load milestones.";
        }
    }
    function getMilestoneTitles($id) {
        try {
            $db = new db();
            $getMilestones = $db->pdo->prepare("SELECT * FROM milestone WHERE project_id = :id ORDER BY deadline ASC");
            $getMilestones->execute(array(':id' => $id));
            $check = $getMilestones->fetch(PDO::FETCH_ASSOC);
            if($check < 1) {
                echo <<<_END
                    <option>This project has no milestones.</option>
_END;
            }
            else {
                $result = $db->pdo->prepare("SELECT milestone_id, mtitle FROM milestone WHERE project_id = :id ORDER BY deadline ASC");
                $result->execute(array(':id' => $id));
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo <<<_END
                <option value="$row[milestone_id]">$row[mtitle]</option>
_END;
                }
            }
        } catch (PDOException $e) {
            echo "Cannot load projects";
            //echo "Error: ".$e."<br>";
        }
    }
    function getMilestoneDates($id) {
        try {
            $db = new db();
            $getDates = $db->pdo->prepare("SELECT mstart, deadline FROM milestone WHERE milestone_id = :id");
            $getDates->execute(array(':id' => $id));
            while($row = $getDates->fetch(PDO::FETCH_ASSOC)) {
                $start = date("Y-m-d", strtotime($row['mstart']));
                $end = date("Y-m-d", strtotime($row['deadline']));
                echo <<<_END
                            <div class="form-group col-sm-6">
                                <label for="start3">Initiation Date</label>
                                <input type="date" class="form-control" name="start3" id="start3" min="$start" max="$end" required>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="end3">Completion Date</label>
                                <input type="date" class="form-control" name="end3" id="end3" min="$start" max="$end" required>
                            </div>
_END;
            }
        } catch (PDOException $e) {
            echo "Error: ".$e."<br>";
        }
    }
    function addMilestone($id, $title, $description, $start, $deadline) {
        try {
            $db = new db();
            $stmt = $db->pdo->prepare("INSERT INTO milestone (project_id, mtitle, mdescription, mstart, deadline) VALUES (:id, :title, :description, :start, :deadline)");
            $stmt->execute(array(':id' => $id, ':title' => $title, ':description' => $description, ':start' => $start, ':deadline' => $deadline));
            if ($stmt == true) {
                $_SESSION['alert'] = "m1";
                header('location:../dashboard.php');
            } else {
                header('location:../404.php');
            }
        } catch (PDOException $e) {
            header('location:../404.php');
        }
    }
    function getMilestoneDetails($milestone, $project) {
        try {
            $db = new db();
            $getMilestoneDetails = $db->pdo->prepare("SELECT milestone.milestone_id, milestone.project_id, milestone.mtitle, milestone.mdescription, milestone.mstart, milestone.deadline, milestone.completed FROM milestone WHERE milestone.milestone_id = :milestone AND milestone.project_id = :project ");
            $getMilestoneDetails->execute(array(':milestone' => $milestone, ':project' => $project));
            while ($row = $getMilestoneDetails->fetch(PDO::FETCH_ASSOC)) {
                $start = date("m/d/Y", strtotime($row['mstart']));
                $end = date("m/d/Y", strtotime($row['deadline']));
                $completed = date("m/d/Y", strtotime($row['completed']));
                $today = date("d/m/Y");
                echo <<<_END
                    <div id="overlay">
                        <div class="form-container form-container-e">
                            <form method="post" action="classes/milestone.php">
                               <div class="dialog-content clearfix">
                               <h4>Update Milestone</h4>
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label for="title">Title</label>
                                            <input type="text" class="form-control" name="title" id="title" value="$row[mtitle]">
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="desc">Description</label>
                                            <textarea id="desc" name="desc" cols="100" rows="3" class="form-control">$row[mdescription]</textarea>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="start">Initiation Date</label>
                                            <input type="text" class="form-control datepicker" name="start" id="start" value="$start" required>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="end">Completion Date</label>
                                            <input type="text" class="form-control datepicker" name="deadline" id="end" value="$end" required>
                                        </div>
                                    </div>
                                    <input type="hidden" value="$row[milestone_id]" name="milestone">
                                    <input type="hidden" value="$row[project_id]" name="project">
                                    <label for="complete">Complete the milestone</label>
_END;
                                    if(!isset($row['completed'])) {
                                        echo <<<_END
                                    <select name="complete" class="form-control" id="complete">
                                        <option>Not completed</option>
                                        <option value="complete">Complete today ($today)</option>
                                    </select>
_END;
                                    }
                                    else {
                                        echo <<<_END
                                        <p class="ehint">Milestone completed on: $completed.</p>
_END;
                                    }
                  echo <<<_END
                                </div>
                                <div class="dialog-buttons">
                                    <div class="row">
                                        <button type="submit" name="updatemilestone" class="update-yes">Update</button>
                                        <button type="button" onclick="edit_modal()" class="update-no">Cancel</button>
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
    function UpdateMilestone($title, $description, $start, $deadline, $completed, $milestone, $project) {
        try {
            $db = new db();
            if ($completed == 'complete') {
                $completed = date("Y-m-d");
                $updateMilestone = $db->pdo->prepare("UPDATE milestone SET mtitle = :mtitle, mdescription = :desc, mstart = :start, deadline = :deadline, completed = :completed WHERE milestone_id = :milestone AND project_id = :project");
                $updateMilestone->execute(array(':mtitle' => $title, ':desc' => $description, ':start' => $start, ':deadline' => $deadline, ':completed' => $completed, ':milestone' => $milestone, ':project' => $project));
            } else {
                $updateMilestone = $db->pdo->prepare("UPDATE milestone SET mtitle = :mtitle, mdescription = :desc, mstart = :start, deadline = :deadline WHERE milestone_id = :milestone AND project_id = :project");
                $updateMilestone->execute(array(':mtitle' => $title, ':desc' => $description, ':start' => $start, ':deadline' => $deadline, ':milestone' => $milestone, ':project' => $project));
            }
            if ($updateMilestone == true) {
                $_SESSION['alert'] = 'm3';
                echo "<script>window.history.go(-1);</script>";
            } else {
                header('location:../400.php');
            }
        }
        catch (PDOException $e) {
            echo "Error: ".$e."<br>";
        }
    }
    function getThisMilestoneDelete($milestone, $project) {
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT milestone_id, project_id, mtitle FROM milestone WHERE milestone_id = :id AND project_id = :id2");
            $result->execute(array(':id' => $milestone, ':id2' => $project));
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo <<<_END
                    <div id="overlay">
                        <div class="form-container form-container-d">
                            <form method="post" action ="classes/milestone.php">
                               <div class="dialog-content clearfix">
                                    <p>Are you sure you want to delete milestone: <strong>$row[mtitle]</strong>?</p>
                                    <input type="hidden" value="$row[project_id]" name="project">
                                    <input type="hidden" value="$row[milestone_id]" name="milestone">
                                </div>
                                <div class="dialog-buttons">
                                    <div class="row">
                                        <button type="submit" name="sdeletemilestone" class="delete-yes">Yes</button>
                                        <button type="button" onclick="edit_modal()" class="delete-no">No</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
_END;
            }
        } catch (PDOException $e) {
            header('location:http://dominichart.uk/404.php');
        }
    }
    function deleteMilestone($milestone, $project) {
        try {
            $db = new db();
            $taskBudget = 0;
            //Find tasks
            $getTasks = $db->pdo->prepare("SELECT task_id, task_spend FROM task WHERE milestone_id = :milestone");
            $getTasks->execute(array(':milestone' => $milestone));
            while($task = $getTasks->fetch(PDO::FETCH_ASSOC)) {
                //Add task budget to total for later
                $taskBudget = $taskBudget + $task['task_spend'];
                //Delete associated transactions for each task in the milestone
                $getTransactions = $db->pdo->prepare("DELETE FROM transaction WHERE task = :task");
                $getTransactions->execute(array(':task' => $task['task_id']));
            }
            //Delete the tasks and update expenses, and finally delete the milestone
            $deleteTasks = $db->pdo->prepare("DELETE FROM task WHERE milestone_id = :milestone; UPDATE project_details SET expenses = expenses - :amount WHERE project_id = :project; DELETE FROM milestone WHERE milestone_id = :milestone");
            $deleteTasks->execute(array(':milestone' => $milestone, ':amount' => $taskBudget,':project' => $project));
            if($deleteTasks == true) {
                $_SESSION['alert'] = 'm4';
                echo "<script>window.history.go(-1);</script>";
            } else {
                header('location:../404.php');
            }

        } catch (PDOException $e) {
            echo "Error: ".$e."<br>";
        }
    }
}