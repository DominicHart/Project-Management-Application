<?php
session_start();
if(isset($_SESSION['userID'])) {
    $thisuser = $_SESSION['userID'];
    $username = $_SESSION['username'];
    $fullname = $_SESSION['fullname'];
    $tips = $_SESSION['tips'];
    $access = $_SESSION['access'];
    if (!isset($_SESSION['usersearch'])) {
        $_SESSION['usersearch'] = false;
    }
    if (!isset($_SESSION['error'])) {
        $_SESSION['error'] = false;
    }
    if (!isset($_SESSION['alert'])) {
        $_SESSION['alert'] = false;
    }
}