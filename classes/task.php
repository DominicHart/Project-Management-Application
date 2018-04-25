<?php
if (session_status() == PHP_SESSION_NONE){
    session_start();
};
include_once ($_SERVER['DOCUMENT_ROOT'].'./fyp/includes/config.php');
$task = new task();
if(isset($_POST['stask'])) {
    $id = filter_var($_POST['milestone'], FILTER_SANITIZE_STRING);
    $project = filter_var($_POST['project'], FILTER_SANITIZE_STRING);
    $title = filter_var($_POST['task'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['desc'], FILTER_SANITIZE_STRING);
    $spend = filter_var($_POST['allbudget'], FILTER_SANITIZE_STRING);
    $employee = filter_var($_POST['pemp'], FILTER_SANITIZE_STRING);
    $start = filter_var($_POST['start3'], FILTER_SANITIZE_STRING);
    $start = date('Y-m-d', strtotime($start));
    $end = filter_var($_POST['end3'], FILTER_SANITIZE_STRING);
    $end = date('Y-m-d', strtotime($end));
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
    $statuses = array('To Do', 'In Progress', 'Complete');
    if(!in_array($status, $statuses)) {
        header('location:../404.php');
        exit();
    }
    else {
        $task->addTask($id, $project, $title, $description, $spend, $employee, $start, $end, $status);
    }
}
if(isset($_POST['updatetask'])) {
    $taskID = filter_var($_POST['task'], FILTER_SANITIZE_STRING);
    $milestoneID = filter_var($_POST['milestone'], FILTER_SANITIZE_STRING);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['desc'], FILTER_SANITIZE_STRING);
    $start = filter_var($_POST['start'], FILTER_SANITIZE_STRING);
    $start = date('Y-m-d', strtotime($start));
    $end = filter_var($_POST['end'], FILTER_SANITIZE_STRING);
    $end = date('Y-m-d', strtotime($end));
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
    $statuses = array('To Do', 'In Progress', 'Complete');
    if(!in_array($status, $statuses)) {
        header('location:../404.php');
        exit();
    }
    else {
        $task->updateTask($title, $description, $start, $end, $status, $taskID, $milestoneID);
    }
}
if(isset($_POST['sdeletetask'])) {
    $taskID = filter_var($_POST['task'], FILTER_SANITIZE_STRING);
    $milestoneID = filter_var($_POST['milestone'], FILTER_SANITIZE_STRING);
    $task->deleteThisTask($taskID, $milestoneID);
}
class task {
    public function getTasks($slug) {
        try {
            $db = new db();
            $result = $db->pdo->prepare('SELECT project_details.project_id, project_details.project_manager, project_details.customer, project.project_title, project.startDate, project.endDate, project.project_status, project.overview, project.slug, customer.cfullname, employee.fullname, project_staff.s_username, project_staff.p_id FROM project_details LEFT JOIN employee ON project_details.project_manager = employee.username LEFT JOIN customer ON project_details.customer = customer.username LEFT JOIN project ON project_details.project_id = project.project_id LEFT OUTER JOIN project_staff ON project_details.project_id = project_staff.p_id WHERE (project_details.project_manager = :username OR project_details.customer = :username OR project_staff.s_username = :username) AND project.slug = :slug GROUP BY project.project_id');
            $result->execute(array(':username' => $_SESSION['username'], ':slug' => $slug));
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo '<h2>Tasks: '.$row['project_title'].'</h2>';
                echo '<div class="key"><p class="to-do">To Do <b class="pull-right"></b></p><p class="in-progress">In Progress <b class="pull-right"></b></p><p class="complete">Complete <b class="pull-right"></b></p></div><div class="table-responsive"><table class="table table-bordered"><thead><tr><th>Title</th><th>Start</th><th>End</th><th>Duration</th><th>Staff</th><th>Budget</th></tr></thead><tbody>';
                $taskCount = 0;
                $todo = 0;
                $inprogress = 0;
                $complete = 0;
                $total = 0;
                $budget = 0;
                //Get project id from milestone, then pass slug
                $getTaskTitles = $db->pdo->prepare("SELECT task.task_id, task.task_title, task.milestone_id, milestone.project_id, project.project_id, project.slug FROM task LEFT JOIN milestone ON task.milestone_id = milestone.milestone_id LEFT JOIN project ON milestone.project_id = project.project_id WHERE project.slug = :slug");
                $getTaskTitles->execute(array(':slug' => $slug));
                $titles = $getTaskTitles->fetchAll(PDO::FETCH_ASSOC);
                if (empty($titles)) {
                } else {
                    if($_SESSION['access'] == 3) {
                        echo <<<_END
                    <div class="man-tasks">
                        <label for="task-list">Manage Tasks:</label>
                        <select name="task-list" id="task-list" required>
_END;
                        while ($title = array_shift($titles)) {
                            echo <<<_END
                    <option value="$title[task_id],$title[milestone_id]">$title[task_title]</option>
_END;
                        }
                        echo <<<_END
                        </select>
                    <button type="submit" class="etask" name="etask">Edit</button>
                    <button type="submit" class="dtask" name="dtask">Delete</button>
                    </div>
_END;
                    }
                }
                $getMilestones = $db->pdo->prepare('SELECT * FROM milestone WHERE project_id = :id');
                $getMilestones->execute(array(':id' => $row['project_id']));
                while ($milestone = $getMilestones->fetch(PDO::FETCH_ASSOC)) {
                    $getTasks = $db->pdo->prepare('SELECT task.milestone_id, task.task_title, task.task_desc, task.task_spend, task.task_emp, task.tstartDate, task.tendDate, task.tcompleted, task.task_status, employee.username, employee.fullname FROM task LEFT JOIN employee ON task.task_emp = employee.username WHERE task.milestone_id = :id ORDER BY task.tendDate ASC');
                    $getTasks->execute(array(':id' => $milestone['milestone_id']));
                    while ($task = $getTasks->fetch(PDO::FETCH_ASSOC)) {
                        $start = new DateTime($task['tstartDate']);
                        $end = new DateTime($task['tendDate']);
                        if($start == $end) {
                            $duration = 1;
                            $newduration = $duration;
                        }
                        else {
                            $duration = $start->diff($end);
                            $newduration = $duration->format('%a');
                        }
                        if(!isset($task['fullname']) && isset($task['task_emp'])) {
                            $taskemp = 'Withdrawn';
                        }
                        elseif(isset($task['fullname']) && isset($task['task_emp'])) {
                            $taskemp = $task['fullname'];
                        }
                        elseif(!isset($task['fulname']) && !isset($task['task_emp'])) {
                            $taskemp = 'N/A';
                        }
                        $allocated = number_format($task['task_spend']);
                        $taskCount = $taskCount + 1;
                        $budget = $budget + $task['task_spend'];
                        $total = $total + $newduration;
                        $status = $task['task_status'];
                        if($status == 'To Do') {
                            $background = 'to-do';
                            $todo = $todo + 1;
                        }
                        else if($status == 'In Progress') {
                            $background = 'in-progress';
                            $inprogress = $inprogress + 1;
                        }
                        else {
                            $background = 'complete';
                            $complete = $complete + 1;
                        }
                        echo '<tr class="'.$background.'"><td data-label="Title">' . $task['task_title'] . '</td><td data-label="Start">' . $start->format('d/m/y') . '</td><td data-label="End">' . $end->format('d/m/y') . '</td><td data-label="Duration">' . $newduration.' days' . '</td><td data-label="Staff">'.$taskemp.'</td><td>£'.$allocated.'</td></tr>';
                    }
                }
                echo '<tr><td colspan="4"><b>'.$taskCount.' Tasks: '.$todo.' To Do, '.$inprogress.' In Progress, '.$complete.' Complete</b></td><td><b>'.$total.' days</b></td><td><b>£'.number_format($budget).' spent</b></td></tr></tbody></table></div>';
            }
        }
        catch (PDOException $e) {
            //echo "Error: ".$e."<br>";
            echo "Error: Could not load tasks.";
        }
    }
    public function addTask($id, $project, $title, $description, $spend, $employee, $start, $end, $status) {
        try {
            $db = new db();
            if($spend > 1) {
                $amount = $spend;
                $type = 'Outgoing';
                $date = $start;
                $stmt = $db->pdo->prepare("INSERT INTO task (milestone_id, task_title, task_desc, task_spend, task_emp, tstartDate, tendDate, task_status) VALUES (:id, :title, :desc, :spend, :employee, :start, :end, :status); INSERT INTO transaction (project_id, task, amount, tType, transactionDate, tDesc) VALUES (:proj_id, LAST_INSERT_ID(), :amount, :type, :date, :desc); UPDATE project_details SET expenses = expenses + :spend WHERE project_id = :proj_id");
                $stmt->execute(array(':proj_id' => $project, ':id' => $id, ':title' => $title, ':amount' => $amount, ':type' => $type, ':date' => $date, ':desc' => $description, ':spend'=> $spend, ':employee' => $employee, ':start' => $start, ':end' => $end, ':status' => $status));
            }
            elseif($spend <= 1) {
                $stmt = $db->pdo->prepare("INSERT INTO task (milestone_id, task_title, task_desc, task_emp, tstartDate, tendDate, task_status) VALUES (:id, :title, :desc, :employee, :start, :end, :status);");
                $stmt->execute(array(':id' => $id, ':title' => $title, ':desc' => $description, ':employee' => $employee, ':start' => $start, ':end' => $end, ':status' => $status));
            }
            else {
                header('location:../dashboard.php');
            }
            if($stmt == true) {
                header('location:../dashboard.php');
                $_SESSION['alert'] = 'a3';
            } else {
                header('location:../404.php');
            }
        } catch (PDOException $e) {
            echo "Error: ".$e."<br>";
        }
    }
    function getTaskDetails($taskID, $milestoneID) {
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT * FROM task WHERE task_id = :id and milestone_id = :milestone");
            $result->execute(array(':id' => $taskID, ':milestone' => $milestoneID));
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $start = date("m/d/Y", strtotime($row['tstartDate']));
                $end = date("m/d/Y", strtotime($row['tendDate']));
                echo <<<_END
                    <div id="overlay">
                        <div class="form-container form-container-e">
                            <form method="post" action="classes/task.php">
                               <div class="dialog-content clearfix">
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label for="title">Title</label>
                                            <input type="text" class="form-control" name="title" id="title" value="$row[task_title]">
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="desc">Description</label>
                                            <textarea id="desc" name="desc" cols="100" rows="3" class="form-control">$row[task_desc]</textarea>
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
                                            <select name="status" id="status" class="form-control" required>
                                                <option value="$row[task_status]">$row[task_status]</option>
                                                <option value="To Do">To Do</option>
                                                <option value="In Progress">In Progress</option>
                                                <option value="Complete">Complete</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" value="$row[task_id]" name="task">
                                    <input type="hidden" value="$row[milestone_id]" name="milestone">
                                </div>
                                <div class="dialog-buttons">
                                    <div class="row">
                                        <button type="submit" name="updatetask" class="update-yes">Update</button>
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
    function updateTask($title, $description, $start, $end, $status, $taskID, $milestoneID) {
        try {
            $db = new db();
            $stmt = $db->pdo->prepare("UPDATE task SET task_title = :title, task_desc = :desc, tstartDate = :start, tendDate = :end, task_status = :status WHERE task_id = :id AND milestone_id = :milestone");
            $stmt->execute(array(':title' => $title, ':desc' => $description, ':start' => $start, ':end' => $end, ':status' => $status, ':id' => $taskID, ':milestone' => $milestoneID));
            if($stmt == true) {
                $_SESSION['alert'] = 'u3';
                echo "<script>window.history.go(-1);</script>";
            }
            else {
                echo "Something went wrong";
            }
        } catch (PDOException $e) {
            header('location:../404.php');
        }
    }
    function getThisTaskDelete($task, $milestone) {
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT task_id, milestone_id, task_title FROM task WHERE task_id = :id AND milestone_id = :id2");
            $result->execute(array(':id' => $task, ':id2' => $milestone));
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo <<<_END
                    <div id="overlay">
                        <div class="form-container form-container-d">
                            <form method="post" action ="classes/task.php">
                               <div class="dialog-content clearfix">
                                    <p>Are you sure you want to delete task: <strong>$row[task_title]</strong>?</p>
                                    <input type="hidden" value="$row[task_id]" name="task">
                                    <input type="hidden" value="$row[milestone_id]" name="milestone">
                                </div>
                                <div class="dialog-buttons">
                                    <div class="row">
                                        <button type="submit" name="sdeletetask" class="delete-yes">Yes</button>
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
    function deleteThisTask($task, $milestoneID) {
        try {
            $db = new db();
            //Get associated milestone to grab the projectID
            $getMilestoneID = $db->pdo->prepare("SELECT milestone_id FROM task WHERE task_id = :id");
            $getMilestoneID->execute(array(':id' => $task));
            $milestone = $getMilestoneID->fetch(PDO::FETCH_ASSOC);
            //Get the projectID from the associated milestone
            $getProjID = $db->pdo->prepare("SELECT project_id FROM milestone WHERE milestone_id = :id");
            $getProjID->execute(array(':id' => $milestone['milestone_id']));
            $project = $getProjID->fetch(PDO::FETCH_ASSOC);
            //Get the amount spent on the task for transactions
            $getAmount = $db->pdo->prepare("SELECT task_spend FROM task WHERE task_id = :id");
            $getAmount->execute(array(':id' => $task));
            $amount = $getAmount->fetch(PDO::FETCH_ASSOC);
            //Delete task, delete associated transaction and update expenses if a transaction is removed
            $stmt = $db->pdo->prepare("DELETE FROM task WHERE task_id = :id AND milestone_id = :milestone; DELETE FROM transaction WHERE task = :id; UPDATE project_details SET expenses = expenses - :amount WHERE project_id = :project");
            $stmt->execute(array(':id' => $task, ':milestone' => $milestoneID, ':amount' => $amount['task_spend'], ':project' => $project['project_id']));
            if ($stmt == true) {
                echo "<script>window.history.go(-1);</script>";
                $_SESSION['alert'] = 'd3';
            } else {
                header('location:../404.php');
            }
        } catch (PDOException $e) {
            header('location:../404.php');
        }
    }
}