<?php
if(isset($_SESSION['alert'])) {
    if($_SESSION['alert'] == 'a1') {
        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The project has been successfully created.</div>";
    } elseif($_SESSION['alert'] == 'a3') {
        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The project task has been successfully created.</div>";
    } elseif($_SESSION['alert'] == 'a4') {
        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>Staff successfully assigned.</div>";
    } elseif($_SESSION['alert'] == 'ae1') {
        echo "<div class='alert alert-danger alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-info-circle' aria-hidden='true'></i>That staff already belongs to that project!</div>";
    } elseif($_SESSION['alert'] == 'a6') {
        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The project has been deleted.</div>";
    } elseif($_SESSION['alert'] == 'c2') {
        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The customer has been created. They have received an email with their login details.</div>";
    } elseif($_SESSION['alert'] == 'pass') {
        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>Your password has been changed.</div>";
    } elseif($_SESSION['alert'] == 'e2') {
        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The employee has been created. They have received an email with their login details.</div>";
    } elseif($_SESSION['alert'] == 'm1') {
        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The project milestone has been created.</div>";
    } elseif($_SESSION['alert'] == 'r1') {
        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The project risk has been added.</div>";
    } elseif($_SESSION['alert'] == 't1') {
        echo "<div class='alert alert-info alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-info-circle' aria-hidden='true'></i>Tips are now <strong>disabled</strong>, please logout for changes to take effect.</div>";
    } elseif($_SESSION['alert'] == 't2') {
        echo "<div class='alert alert-info alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-info-circle' aria-hidden='true'></i>Tips are now <strong>enabled</strong>, please logout for changes to take effect.</div>";
    } elseif($_SESSION['alert'] == 'd4') {
        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The project risk has been deleted.</div>";
    } elseif($_SESSION['alert'] == 'd5') {
        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The user has been deleted.</div>";
    } elseif($_SESSION['alert'] == 'u4') {
        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The project risk has been updated.</div>";
    } elseif($_SESSION['alert'] == 'm3') {
        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The project milestone has been updated.</div>";
    } elseif($_SESSION['alert'] == 't3') {
        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The project transaction has been updated.</div>";
    }elseif($_SESSION['alert'] == 'transfer') {
        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The project has been transferred.</div>";
    } elseif($_SESSION['alert'] == 'user1') {
        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The user has been updated.</div>";
    } elseif($_SESSION['alert'] == 'f1') {
        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The file has been uploaded.</div>";
    } elseif($_SESSION['alert'] == 'f2') {
        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The file has been deleted.</div>";
    } elseif($_SESSION['alert'] == 're2') {
        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The issue has been reported.</div>";
    } elseif($_SESSION['alert'] == 're3') {
        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The issue has been hidden.</div>";
    } elseif($_SESSION['alert'] == 're4') {
        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>All issues are now visible.</div>";
    } elseif($_SESSION['alert'] == 're5') {
        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The issue has been updated.</div>";
    } elseif($_SESSION['alert'] == 're6') {
        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The issue has been deleted.</div>";
    } unset($_SESSION['alert']);
}
if(isset($_SESSION['error'])) {
    if($_SESSION['error'] == 'c2') {
        echo "<div class='alert alert-danger alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-exclamation-circle' aria-hidden='true'></i>Username already exists!</div>";
    } elseif($_SESSION['error'] == 'c3') {
        echo "<div class='alert alert-danger alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-exclamation-circle' aria-hidden='true'></i>Email address already in use!</div>";
    } elseif($_SESSION['error'] == 'pass1') {
        echo "<div class='alert alert-danger alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-exclamation-circle' aria-hidden='true'></i>The passwords you entered do not match!</div>";
    } elseif ($_SESSION['error'] == 'pass2') {
        echo "<div class='alert alert-danger alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-exclamation-circle' aria-hidden='true'></i>The password you entered is invalid!</div>";
    } elseif ($_SESSION['error'] == 'pr1') {
        echo "<div class='alert alert-danger alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-exclamation-circle' aria-hidden='true'></i>Password must contain uppercase</div>";
    } elseif ($_SESSION['error'] == 'pr2') {
        echo "<div class='alert alert-danger alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-exclamation-circle' aria-hidden='true'></i>Password must contain lowercase</div>";
    } elseif ($_SESSION['error'] == 'pr3') {
        echo "<div class='alert alert-danger alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-exclamation-circle' aria-hidden='true'></i>Password must contain number</div>";
    } elseif ($_SESSION['error'] == 'pr4') {
        echo "<div class='alert alert-danger alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-exclamation-circle' aria-hidden='true'></i>Password must be 9 characters minimum</div>";
    } elseif ($_SESSION['error'] == 'user1') {
        echo "<div class='alert alert-danger alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-exclamation-circle' aria-hidden='true'></i>You can't deactivate yourself.</div>";
    }
    unset($_SESSION['error']);
}