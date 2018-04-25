<?php
if (session_status() == PHP_SESSION_NONE){
    session_start();
};
include_once ($_SERVER['DOCUMENT_ROOT'].'./fyp/includes/config.php');
$transaction = new transaction();
if(isset($_POST['stransaction'])) {
    $id = filter_var($_POST['project4'], FILTER_SANITIZE_STRING);
    $amount = filter_var($_POST['amount'], FILTER_SANITIZE_STRING);
    $type = filter_var($_POST['btype'], FILTER_SANITIZE_STRING);
    $options = array('Incoming', 'Outgoing');
    if(!in_array($type, $options)) {
        echo "Option not valid";
        header('location:../dashboard.php');
        exit();
    }
    else {
        $type = filter_var($_POST['btype'], FILTER_SANITIZE_STRING);
    }
    $date = filter_var($_POST['bdate'], FILTER_SANITIZE_STRING);
    $date = date('Y-m-d', strtotime($date));
    $description = filter_var($_POST['bdesc'], FILTER_SANITIZE_STRING);
    $transaction->addTransaction($id, $amount, $type, $date, $description);
}
if(isset($_POST['sdeletetransaction'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
    $transaction->deleteThisTransaction($id);
}
class transaction {
    function __construct(){}
    public function addTransaction($id, $amount, $type, $date, $description) {
        try {
            $db = new db();
            if ($type == 'Incoming') {
                $getBudget = $db->pdo->prepare("SELECT budget FROM project WHERE project_id = :id");
                $getBudget->execute(array(':id' => $id));
                $row = $getBudget->fetch(PDO::FETCH_ASSOC);
                $budget = $row['budget'];
                $budget = $budget + $amount;
                $updateBudget = $db->pdo->prepare("INSERT INTO transaction (project_id, amount, tType, transactionDate, tDesc) VALUES (:id, :amount, :type, :date, :desc); UPDATE project SET budget = :budget WHERE project_id = :id");
                $updateBudget->execute(array(':id' => $id, ':amount' => $amount, ':type' => $type, ':date' => $date, ':desc' => $description, ':budget' => $budget));
            }
            else if ($type == 'Outgoing') {
                $getExpenses = $db->pdo->prepare("SELECT expenses FROM project_details WHERE project_id = :id");
                $getExpenses->execute(array(':id' => $id));
                $row = $getExpenses->fetch(PDO::FETCH_ASSOC);
                $expenses = $row['expenses'];
                $expenses = $expenses + $amount;
                $updateBudget = $db->pdo->prepare("INSERT INTO transaction (project_id, amount, tType, transactionDate, tDesc) VALUES (:id, :amount, :type, :date, :desc); UPDATE project_details SET expenses = expenses + :expenses WHERE project_id = :id");
                $updateBudget->execute(array(':id' => $id, ':amount' => $amount, ':type' => $type, ':date' => $date, ':desc' => $description, ':expenses' => $expenses));
            }
            if ($updateBudget == true) {
                $_SESSION['alert'] = "b1";
                header('location:../dashboard.php');
            } else {
                header('location:../404.php');
            }
        } catch (PDOException $e) {
            echo "Error: ".$e."<br>";
        }
    }
    public function getBudget($slug) {
        try {
            $db = new db();
            $getTransactionList = $db->pdo->prepare("SELECT transaction.transID, transaction.project_id, transaction.transactionDate, transaction.amount, transaction.tType, project.project_id, project.slug FROM transaction LEFT JOIN project ON transaction.project_id = project.project_id WHERE project.slug = :slug ORDER BY transaction.transactionDate ASC");
            $getTransactionList->execute(array(':slug' => $slug));
            $transactions = $getTransactionList->fetchAll(PDO::FETCH_ASSOC);
            if (empty($transactions)) {
            } else {
                if($_SESSION['access'] == 3) {
                    echo <<<_END
                    <div class="man-finances">
                        <label for="transaction-list">Manage Finances:</label>
                        <select name="transaction-list" id="transaction-list" required>
_END;
                    while ($transaction = array_shift($transactions)) {
                        $amount = number_format($transaction['amount']);
                        $date = date("d/m/Y", strtotime($transaction['transactionDate']));
                        $direction = $transaction['tType'];
                        if($direction == 'Incoming') {
                            $direction = 'Income';
                            $rowclass = 'money-in';
                        }
                        else {
                            $direction = 'Expense';
                            $rowclass = 'money-out';
                        }
                        echo <<<_END
                    <option value="$transaction[transID],$transaction[project_id]">£$amount, $direction, $date</option>
_END;
                    }
                    echo <<<_END
                        </select>
                    <button type="submit" class="dtransaction" name="dtransaction">Delete</button>
                    </div>
_END;
                }
            }
            $getProjID = $db->pdo->prepare("SELECT project_id FROM project WHERE project.slug = :slug");
            $getProjID->execute(array(':slug' => $slug));
            $id = $getProjID->fetch(PDO::FETCH_ASSOC);
            $projID = $id['project_id'];
            $getBudget = $db->pdo->prepare("SELECT budget FROM project WHERE project_id = :id");
            $getBudget->execute(array(':id' => $projID));
            $budg = $getBudget->fetch(PDO::FETCH_ASSOC);
            $budget = $budg['budget'];
            $getExpenses = $db->pdo->prepare("SELECT expenses FROM project_details WHERE project_id = :id");
            $getExpenses->execute(array(':id' => $projID));
            $exp = $getExpenses->fetch(PDO::FETCH_ASSOC);
            $expenses = $exp['expenses'];
            $getFinances = $db->pdo->prepare("SELECT transaction.transID, transaction.amount, transaction.tType, transaction.transactionDate, transaction.tDesc FROM transaction LEFT JOIN project ON transaction.project_id = project.project_id WHERE project.project_id = :id ORDER BY transaction.transactionDate ASC");
            $getFinances->execute(array(':id' => $projID));
            $rows = $getFinances->fetchAll(PDO::FETCH_ASSOC);
            if(empty($rows)) {
                echo "This project does not have any finances.";
            }
            else {
                echo <<<_END
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr><th>Amount</th><th>Type</th><th>Date</th><th>Description</th></tr>
                    </thead>
                    <tbody>
_END;
                while($row = array_shift($rows)) {
                    $date = date("d/m/Y", strtotime($row['transactionDate']));
                    $direction = $row['tType'];
                    $amount = $row['amount'];
                    if($direction == 'Incoming') {
                        $direction = 'Income';
                        $rowclass = 'money-in';
                    }
                    else {
                        $direction = 'Expense';
                        $rowclass = 'money-out';
                    }
                    $amount = number_format($amount);
                    echo <<<_END
                    <tr class><td data-label="Amount">£$amount</td><td class="$rowclass" data-label="In/out">$direction</td><td data-label="Date">$date</td><td data-label="Description">$row[tDesc]</td></tr>
_END;
               }
                $remaining = $budget - $expenses;
                echo '<tr><td colspan="3">Total Budget</td><td>£'.number_format($budget).'</td></tr><tr><td colspan="3">Total Expenses</td><td>£'.number_format($expenses).'</td></tr>';
                echo '<h5><strong>Budget Remaining: £'.number_format($remaining).'</strong></h5>';
                echo <<<_END
                </tbody>
                </table>
            </div>
_END;
            }
        } catch (PDOException $e) {
            echo "Error: ".$e."<br>";
        }
    }
    function getTransactionDetails($transactionID, $project)
    {
        try {
            $db = new db();
            $getTransactionDetails = $db->pdo->prepare("SELECT transaction.transID, transaction.project_id, transaction.amount, transaction.tType, transaction.transactionDate, transaction.tDesc FROM transaction WHERE transaction.transID = :transaction AND transaction.project_id = :project ");
            $getTransactionDetails->execute(array(':transaction' => $transactionID, ':project' => $project));
            while ($row = $getTransactionDetails->fetch(PDO::FETCH_ASSOC)) {
                $date = date("m/d/Y", strtotime($row['transactionDate']));
                $type = $row['tType'];
                if ($type == 'Incoming') {
                    $type = 'Income';
                } else {
                    $type = 'Expense';
                }
                echo <<<_END
                    <div id="overlay">
                        <div class="form-container form-container-e">
                            <form method="post" action="classes/transaction.php">
                               <div class="dialog-content clearfix">
                               <h4>Update Transaction</h4>
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label for="title">Amount</label>
                                            <input type="number" class="form-control" name="amount" id="newamount" value="$row[amount]">
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="type">Type</label>
                                            <input type="text" id="newtype" class="form-control" value="$type" required disabled>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="start">Date</label>
                                            <input type="text" class="form-control datepicker" name="date" id="newdate" value="$date" required>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="desc">Description</label>
                                            <textarea id="newdesc" name="desc" cols="100" rows="3" class="form-control">$row[tDesc]</textarea>
                                        </div>
                                    </div>
                                    <input type="hidden" value="$row[transID]" name="transaction">
                                    <input type="hidden" value="$row[project_id]" name="project">
                                </div>
                                <div class="dialog-buttons">
                                    <div class="row">
                                        <button type="submit" name="updatetransaction" class="update-yes">Update</button>
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
    public function getThisTransactionDelete($id) {
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT transID, transactionDate, tDesc FROM transaction WHERE transID = :id");
            $result->execute(array(':id' => $id));
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $tDate = date("d/m/Y", strtotime($row['transactionDate']));
                echo <<<_END
                    <div id="overlay">
                        <div class="form-container form-container-d">
                            <form method="post" action ="./classes/transaction.php">
                               <div class="dialog-content clearfix">
                                    <p>Are you sure you want to delete transaction: <strong>$row[tDesc] ($tDate)</strong>?</p>
                                    <input type="hidden" value="$row[transID]" name="id">
                                </div>
                                <div class="dialog-buttons">
                                    <div class="row">
                                        <button type="submit" name="sdeletetransaction" class="delete-yes">Delete</button>
                                        <button type="button" onclick="edit_modal()" class="delete-no">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
_END;
            }
        } catch (PDOException $e) {
            //echo "Error: " . $e . "<br>";
            echo "Error: Could not load finances.";
        }
    }
    public function deleteThisTransaction($id) {
        try {
            $db = new db();
            $checkType = $db->pdo->prepare("SELECT * FROM transaction WHERE transID = :id;");
            $checkType->execute(array(':id' => $id));
            while($row = $checkType->fetch(PDO::FETCH_ASSOC)) {
                $project = $row['project_id'];
                $amount = $row['amount'];
                if($row['tType'] == 'Incoming') {
                    $deleteTransaction = $db->pdo->prepare("DELETE FROM transaction WHERE transID = :id; UPDATE project SET budget = budget - :amount WHERE project.project_id = :project");
                    $deleteTransaction->execute(array(':id' => $id, ':amount' => $amount, ':project' => $project));
                } elseif($row['tType'] == 'Outgoing') {
                    $deleteTransaction = $db->pdo->prepare("DELETE FROM transaction WHERE transID = :id; UPDATE project_details SET expenses = expenses - :amount WHERE project_details.project_id = :project");
                    $deleteTransaction->execute(array(':id' => $id, ':amount' => $amount, ':project' => $project));
                }
                else {
                    header('location:../404.php');
                }
            }
            if ($deleteTransaction == true) {
                echo "<script>window.history.go(-1);</script>";
                $_SESSION['alert'] = 'd3';
            } else {
                header('location:../404.php');
            }
        } catch (PDOException $e) {
            echo "Error: " . $e . "<br>";
        }
    }
}
