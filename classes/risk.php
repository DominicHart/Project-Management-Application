<?php
if (session_status() == PHP_SESSION_NONE){
    session_start();
};
include_once ($_SERVER['DOCUMENT_ROOT'].'./fyp/includes/config.php');
$risk = new risk();
if(isset($_POST['srisk'])) {
    $id = filter_var($_POST['project3'], FILTER_SANITIZE_STRING);
    $title = filter_var($_POST['risk'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['rdesc'], FILTER_SANITIZE_STRING);
    $probability = filter_var($_POST['probability'], FILTER_SANITIZE_STRING);
    $options = array('Low', 'Medium', 'High');
    if(!in_array($probability, $options)) {
        echo "Option not valid";
        header('location:../dashboard.php');
        exit();
    }
    else {
        $probability = filter_var($_POST['probability'], FILTER_SANITIZE_STRING);
    }
    $impact = filter_var($_POST['impact'], FILTER_SANITIZE_STRING);
    $mitigation = filter_var($_POST['mitigation'], FILTER_SANITIZE_STRING);
    $risk->addRisk($id, $title, $description, $probability, $impact, $mitigation);
}
if(isset($_POST['sdeleterisk'])) {
    $id = filter_var($_POST['project_id'], FILTER_SANITIZE_STRING);
    $rtitle = filter_var($_POST['rtitle'], FILTER_SANITIZE_STRING);
    $risk->deleteRisk($id, $rtitle);
}
if(isset($_POST['updaterisk'])) {
    $id = filter_var($_POST['project_id'], FILTER_SANITIZE_STRING);
    $oldtitle = filter_var($_POST['oldtitle'], FILTER_SANITIZE_STRING);
    $newtitle = filter_var($_POST['newtitle'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['desc'], FILTER_SANITIZE_STRING);
    $probability = filter_var($_POST['probability'], FILTER_SANITIZE_STRING);
    $options = array('Low', 'Medium', 'High');
    if(!in_array($probability, $options)) {
        echo "Option not valid";
        header('location:../dashboard.php');
        exit();
    }
    else {
        $probability = filter_var($_POST['probability'], FILTER_SANITIZE_STRING);
    }
    $impact = filter_var($_POST['impact'], FILTER_SANITIZE_STRING);
    $mitigation = filter_var($_POST['mitigation'], FILTER_SANITIZE_STRING);
    $risk->updateRisk($id, $newtitle, $oldtitle, $description, $probability, $impact, $mitigation);
}
class risk
{
    function __construct()
    {
    }

    function addRisk($id, $title, $description, $probability, $impact, $mitigation)
    {
        try {
            $db = new db();
            $stmt = $db->pdo->prepare("INSERT INTO risks (project_id, rtitle, rdescription, probability, impact, mitigation) VALUES (:id, :title, :desc, :prob, :impact, :mitigation)");
            $stmt->execute(array(':id' => $id, ':title' => $title, ':desc' => $description, ':prob' => $probability, ':impact' => $impact, ':mitigation' => $mitigation));
            if ($stmt == true) {
                $_SESSION['alert'] = "r1";
                header('location:../dashboard.php');
            } else {
                header('location:../404.php');
            }
        } catch (PDOException $e) {
            header('location:../404.php');
        }
    }

    function getRisks($slug)
    {
        try {
            $db = new db();
            $getTitles = $db->pdo->prepare("SELECT risks.rtitle, project.project_id, project.slug FROM risks LEFT JOIN project ON risks.project_id = project.project_id WHERE project.slug = :slug");
            $getTitles->execute(array(':slug' => $slug));
            $titles = $getTitles->fetchAll(PDO::FETCH_ASSOC);
            if (empty($titles)) {
            } else {
                if ($_SESSION['access'] == 3) {
                    echo <<<_END
                    <div class="man-risks">
                        <label for="risk-list">Manage Risks:</label>
                        <select name="risk-list" id="risk-list" required>
_END;
                    while ($title = array_shift($titles)) {
                        echo <<<_END
                    <option value="$title[rtitle],$title[project_id]">$title[rtitle]</option>
_END;
                    }
                    echo <<<_END
                        </select>
                    <button type="submit" class="erisk" name="erisk">Edit</button>
                    <button type="submit" class="drisk" name="drisk">Delete</button>
                    </div>
_END;
                }
            }
            $getRisks = $db->pdo->prepare("SELECT risks.rtitle, risks.rdescription, risks.probability, risks.impact, risks.mitigation, project.project_id, project.slug FROM risks LEFT JOIN project ON risks.project_id = project.project_id WHERE project.slug = :slug");
            $getRisks->execute(array(':slug' => $slug));
            $rows = $getRisks->fetchAll(PDO::FETCH_ASSOC);
            if (empty($rows)) {
                echo "This project does not have any risks.";
            } else {
                echo <<<_END
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr><th>Title</th><th>Description</th><th>Probability</th><th>Impact</th><th>Mitigation Strategy</th></tr>
                    </thead>
                    <tbody>
_END;
                while ($row = array_shift($rows)) {
                    echo <<<_END
                    <tr><td data-label="Title">$row[rtitle]</td><td data-label="Description">$row[rdescription]</td><td data-label="Probability">$row[probability]</td><td data-label="Impact">$row[impact]</td><td data-label="Mitigation">$row[mitigation]</td></tr>
_END;
                }
                echo <<<_END
                </tbody>
                </table>
            </div>
_END;
            }
        } catch (PDOException $e) {
            //echo "Error: Could not get risks";
            echo "Error: ".$e."<br>";
        }
    }

    public function getThisRiskDelete($title, $id) {
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT rtitle, project_id FROM risks WHERE rtitle = :title AND project_id = :id");
            $result->execute(array(':title' => $title, ':id' => $id));
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            if (empty($rows)) {
                echo "Error: Risk not found";
            } else {
                while ($row = array_shift($rows)) {
                    echo <<<_END
                    <div id="overlay">
                        <div class="form-container form-container-d">
                            <form method="post" action ="classes/risk.php">
                               <div class="dialog-content clearfix">
                                    <p>Are you sure you want to delete risk: <strong>$row[rtitle]</strong>?</p>
                                    <input type="hidden" value="$row[rtitle]" name="rtitle">
                                    <input type="hidden" value="$row[project_id]" name="project_id">
                                </div>
                                <div class="dialog-buttons">
                                        <button type="submit" name="sdeleterisk" class="delete-yes">Yes</button>
                                        <button type="button" onclick="edit_modal()" class="delete-no">No</button>
                                </div>
                            </form>
                        </div>
                    </div>
_END;

                }
            }
        } catch (PDOException $e) {
            echo "Error: " . $e . "<br>";
        }
    }

    public function deleteRisk($id, $rtitle) {
        try {
            $db = new db();
            $stmt = $db->pdo->prepare("DELETE FROM risks WHERE project_id = :id AND rtitle = :title");
            $stmt->execute(array(':id' => $id, ':title' => $rtitle));
            if ($stmt == true) {
                echo "<script>window.history.go(-1);</script>";
                $_SESSION['alert'] = 'd4';
            } else {
                header('location:../404.php');
            }
        } catch (PDOException $e) {
            echo "Error: " . $e . "<br>";
        }
    }
    public function getRiskDetails($rtitle, $id) {
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT project_id, rtitle, rdescription, probability, impact, mitigation FROM risks WHERE project_id = :id AND rtitle = :title");
            $result->execute(array(':id' => $id, ':title' => $rtitle));
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo <<<_END
                    <div id="overlay">
                        <div class="form-container form-container-e">
                            <form method="post" action="classes/risk.php">
                               <div class="dialog-content clearfix">
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                        <label for="rtitle">Title</label>
                                        <input type="text" class="form-control" name="newtitle" value="$row[rtitle]">
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="desc">Description</label>
                                            <textarea id="desc" name="desc" cols="100" rows="3" class="form-control">$row[rdescription]</textarea>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="probability">Probability</label>
                                            <select name="probability" id="probability" class="form-control" required>
                                                <option value="$row[probability]" selected>$row[probability]</option>
                                                <option value="Low">Low</option>
                                                <option value="Medium">Medium</option>
                                                <option value="High">High</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="impact">Impact</label>
                                            <textarea id="impact" name="impact" cols="100" rows="3" class="form-control">$row[impact]</textarea>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="mitigation">Mitigation</label>
                                            <textarea id="mitigation" name="mitigation" cols="100" rows="3" class="form-control">$row[mitigation]</textarea>
                                        </div>
                                    </div>
                                    <input type="hidden" value="$row[project_id]" name="project_id">
                                    <input type="hidden" value="$row[rtitle]" name="oldtitle">
                                </div>
                                <div class="dialog-buttons">
                                    <div class="row">
                                        <button type="submit" name="updaterisk" class="update-yes">Update</button>
                                        <button type="button" onclick="edit_modal()" class="update-no">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div> 
_END;
            }
        } catch (PDOException $e) {
            echo "Error: ".$e."<br>";
        }
    }
    public function updateRisk($id, $newtitle, $oldtitle, $description, $probability, $impact, $mitigation) {
        $db = new db();
        $stmt = $db->pdo->prepare("UPDATE risks SET rtitle = :title, rdescription = :desc, probability = :probability, impact = :impact, mitigation = :mitigation WHERE project_id = :id AND rtitle = :oldtitle");
        $stmt->execute(array(':title' => $newtitle, ':desc' => $description, ':probability' => $probability, ':impact' => $impact, ':mitigation' => $mitigation, ':id' => $id, ':oldtitle' => $oldtitle));
        if($stmt == true) {
            $_SESSION['alert'] = 'u4';
            echo "<script>window.history.go(-1);</script>";
        }
        else {
            echo "Something went wrong";
        }
    }
}