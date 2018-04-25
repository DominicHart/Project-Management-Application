<?php
session_start();
include_once ('includes/config.php');
try {
    $db = new db();
    date_default_timezone_set('Europe/London');
    $dateTime = date('Y-m-d H:i:s');
    if($_SESSION['access'] == 2 || $_SESSION['access'] == 3 || $_SESSION['access'] == 4) {
        $Loggedout = $db->pdo->prepare("UPDATE employee SET lastLoggedout = :dateTime WHERE username = :username");
    }
    else if($_SESSION['access'] == 1) {
        $Loggedout = $db->pdo->prepare("UPDATE customer SET lastLoggedout = :dateTime WHERE username = :username");
    }
    $Loggedout->execute(array(':dateTime' => $dateTime, ':username' => $_SESSION['username']));
    if($Loggedout == true) {
        session_destroy();
        header('location:index.php');
    }
    else {
        session_destroy();
        header('location:index.php');
    }
} catch (PDOException $e) {
    echo "Error <br>" . $e;
}
exit();