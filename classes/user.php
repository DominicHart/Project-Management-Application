<?php
if (session_status() == PHP_SESSION_NONE){
    session_start();
};
include_once ($_SERVER['DOCUMENT_ROOT'].'./fyp/includes/config.php');
include_once ('customer.php');
$user = new user();

if(isset($_POST['login'])) {
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $type = 'employee';
    $user->login($username, $password, $type);
}
if(isset($_POST['login2'])) {
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $type = 'customer';
    $user->login($username, $password, $type);
}
if(isset($_POST['changepass'])) {
    $oldpass = filter_var($_POST['oldpassword'], FILTER_SANITIZE_STRING);
    $newpass1 = filter_var($_POST['password1'], FILTER_SANITIZE_STRING);
    $newpass2 = filter_var($_POST['password2'], FILTER_SANITIZE_STRING);
    if($newpass1 != $newpass2) {
        header('location:../client.php');
        $_SESSION['error'] = 'pass1';
    }
    else {
        $user->changePass($oldpass, $newpass1);
    }
}
if(isset($_POST['semployee'])) {
    $name = filter_var($_POST['fullname'], FILTER_SANITIZE_STRING);
    $newusername = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $role = filter_var($_POST['role'], FILTER_SANITIZE_STRING);
    $user->addEmployee($name, $newusername, $email, $role);
}
if(isset($_POST['resete'])) {
    $user1 = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $user->resetPass($user1, $email);
}
if(isset($_GET['t1'])) {
    $user->toggleTipsEmp();
}
if(isset($_POST['updateuser'])) {
    $fullname = filter_var($_POST['fullname'], FILTER_SANITIZE_STRING);
    $newusername = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $oldusername = filter_var($_POST['oldusername'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $role = filter_var($_POST['role'], FILTER_SANITIZE_STRING);
    $access = filter_var($_POST['access'], FILTER_SANITIZE_STRING);
    $newpass = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    if($access == 3) {
        $role = 'Project Manager';
    }
    elseif ($access == 2) {
        $role = 'Customer';
    }
    $user->updateUser($newusername, $fullname, $email, $role, $access, $newpass, $oldusername);
}
if(isset($_POST['sdeleteuser'])) {
    $id = filter_var($_POST['userID'], FILTER_SANITIZE_STRING);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $user->deleteUser($id, $username);
}
class user {
    function __construct(){}
    public function notLoggedin() {
        if(!isset($_SESSION['userID'])) {
            header('location:../index.php');
        }
    }
    function login($username, $password, $type) {
      
$db = new db();

        if(session_id() == '') {
           session_start();
        }
       
        try {
            if($type == 'employee') {
                $login = $db->pdo->prepare("SELECT * FROM employee WHERE username = :username");
                $login->execute(array(':username' => $username));

                $result = $login->fetch(PDO::FETCH_ASSOC);

                if($result['activated'] == 0) {
                    $_SESSION['error'] = 2;
                    header('location:../index.php');
                }
                else if($result['activated'] == 1) {
                    $hash = $result['password'];
                    if (password_verify($password, $hash)) {
                        if ($result > 1) {
                            $_SESSION['userID'] = $result['staff_id'];
                            $_SESSION['username'] = $result['username'];
                            $_SESSION['fullname'] = $result['fullname'];
                            $_SESSION['tips'] = $result['newbie'];
                            $_SESSION['access'] = $result['access_level'];
                            if ($result['access_level'] == 2) {
                                $_SESSION['error'] = false;
                                header('location:../projects.php');
                            } else if ($result['access_level'] == 3) {
                                $_SESSION['error'] = false;
                                header('location:../dashboard.php');
                            } else if ($result['access_level'] == 4) {
                                header('location:../admin.php');
                            } else {
                                session_destroy();
                                header('location:../index.php');
                            }
                        } else {
                            $_SESSION['error'] = 1;
                            header('location:../index.php');
                        }
                        date_default_timezone_set('Europe/London');
                        $dateTime = date('Y-m-d H:i:s');
                        $Loggedin = $db->pdo->prepare("UPDATE employee SET lastLoggedin = :dateTime, LastLoggedIP = :IP WHERE username = :username");
                        $Loggedin->execute(array(':dateTime' => $dateTime, ':IP' => $_SERVER['REMOTE_ADDR'], ':username' => $username));
                    } else {
                        $_SESSION['error'] = 1;
                        header('location:../index.php');
                    }
                }
                else {
                    $_SESSION['error'] = 1;
                    header('location:../index.php');
                }
            }
            else if($type == 'customer') {
                $login = $db->pdo->prepare("SELECT * FROM customer WHERE username = :username");
                $login->execute(array(':username' => $username));
                $result = $login->fetch(PDO::FETCH_ASSOC);
                $hash = $result['password'];
                if (password_verify($password, $hash)) {
                    if ($result > 1) {
                        $_SESSION['userID'] = $result['customer_id'];
                        $_SESSION['username'] = $result['username'];
                        $_SESSION['fullname'] = $result['cfullname'];
                        $_SESSION['access'] = $result['access_level'];
                        $_SESSION['tips'] = $result['newbie'];
                        $_SESSION['alert'] = false;
                        $_SESSION['error'] = false;
                        header('location:../client.php');
                    }
                    else {
                        $_SESSION['error'] = 1;
                        header('location:../index.php');
                    }
                    date_default_timezone_set('Europe/London');
                    $dateTime = date('Y-m-d H:i:s');
                    $Loggedin = $db->pdo->prepare("UPDATE customer SET lastLoggedin = :dateTime, LastLoggedIP = :IP WHERE username = :username");
                    $Loggedin->execute(array(':dateTime' => $dateTime, ':IP' => $_SERVER['REMOTE_ADDR'], ':username' => $username));
                }
                else {
                    $_SESSION['error'] = 1;
                    header('location:../index.php');
                }
            }
            else {
                header('location:../404.php');
            }
        } catch (PDOException $e) {
            //echo "Error <br>" . $e;
            echo "Error: could not process login";
        }
    }
    function userTable() {
        try {
            $db = new db();
            echo <<<_END
            <div class="man-users">
                <label for="man-user">Manage Users:</label>
                <select name="man-user" id="man-user" required>
_END;
            $getUserList = $db->pdo->query("SELECT username, fullname, role FROM employee UNION SELECT username, cfullname, role FROM customer ORDER BY fullname ASC");
            while($row = $getUserList->fetch(PDO::FETCH_ASSOC)) {
                echo <<<_END
                    <option value="$row[username]">$row[fullname] ($row[role])</option>
_END;
            }
            echo <<<_END
                </select>
                <input type="text" id="searchusers" onkeyup="searchUsers()" placeholder="Search users..." title="Type in a name">
                <button type="submit" class="euser" name="euser">Edit</button>
                <button type="submit" class="deuser" name="deuser">Activate/Deactivate</button>
                <button type="submit" class="duser" name="duser">Delete</button>
            </div>
            <div class="table-responsive">
            <table class="table" id="user-table">
            <thead>
                <tr><th>Username</th><th>Fullname</th><th>Email</th><th>Role</th><th>Last Session</th><th>Last IP</th><th>Activated</th></tr>
            </thead>
            <tbody>
_END;
            $getUsers = $db->pdo->query("SELECT username, fullname, email, role, lastLoggedin, lastLoggedout, LastLoggedIP, activated FROM employee UNION SELECT username, cfullname, email, role, lastLoggedin, lastLoggedout, LastLoggedIP, activated FROM customer ORDER BY fullname ASC");
            while($row = $getUsers->fetch(PDO::FETCH_ASSOC)) {
                if($row['activated'] == 0) {
                    $activated = 'No';
                }
                else {
                    $activated = 'Yes';
                }
                date_default_timezone_set('Europe/London');
                $dateTime = date('Y-m-d H:i:s');
                $LastL1 = strtotime($row['lastLoggedin']);
                $LastL2 = strtotime($row['lastLoggedout']);
                $LastLogin = date("H:i, d/m/Y", strtotime($row['lastLoggedin']));
                $LastLogout = date("H:i, d/m/Y", strtotime($row['lastLoggedout']));
                if($LastL2 < $LastL1) {
                    $lastSession = 'User Active ('.$LastLogin.')';
                    $active = 'user-active';
                }
                else {
                    $lastSession = $LastLogin . " - " . $LastLogout;
                    $active = 'user-offline';
                }

                echo <<<_END
                <tr><td data-label="Username:">$row[username]</td><td data-label="Fullname">$row[fullname]</td><td data-label="Email">$row[email]</td><td data-label="Role">$row[role]</td><td data-label="Last Active" class="$active">$lastSession</td><td data-label="Last IP">$row[LastLoggedIP]</td><td data-label="Activated">$activated</td></tr>
_END;
            }
            echo <<<_END
            </tbody>
            </table>
        </div>
_END;

        } catch (PDOException $e) {
            echo "Error <br>" . $e;
            //echo "Error: Could not get users";
        }
    }
    function getUserDetails($username) {
        try {
            $db = new db();
            $getUserDetails = $db->pdo->prepare("SELECT username, fullname, email, role, access_level FROM employee WHERE username = :user UNION SELECT username, cfullname, email, NULL as role, access_level FROM customer WHERE username = :user");
            $getUserDetails->execute(array(':user' => $username));
            $row = $getUserDetails->fetch(PDO::FETCH_ASSOC);
            if($row['access_level'] == 1) {
                $access = 'Customer';
            }
            elseif ($row['access_level'] == 2) {
                $access = 'Employee';
            }
            elseif ($row['access_level'] == 3) {
                $access = 'Project Manager';
            }
            elseif ($row['access_level'] == 4) {
                $access = 'Administrator';
            }
            else {
                $access = 'Error';
            }
            echo <<<_END
                    <div id="overlay">
                        <div class="form-container form-container-e">
                            <form method="post" action="http://dominichart.uk/classes/user.php">
                               <div class="dialog-content clearfix">
                               <h4>Update $row[fullname]</h4>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for="username3">Username</label>
                                            <input type="text" class="form-control" name="username" id="username3" value="$row[username]">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="fullname2">Fullname</label>
                                            <input type="text" class="form-control" name="fullname" id="fullname2" value="$row[fullname]">
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="email">Email</label>
                                            <input type="text" class="form-control" name="email" id="email" value="$row[email]" required>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="role2">Role</label>
                                            <p class="ehint">Customer role is customer</p>
                                            <input type="text" class="form-control" name="role" id="role2" value="$row[role]" required>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="access">Access</label>
                                            <select class="form-control" name="access" id="access" required>
                                                <option value="$row[access_level]" selected>$access</option>
                                                <option value="1">Customer</option>
                                                <option value="2">Employee</option>
                                                <option value="3">Project Manager</option>
                                                <option value="4">Administrator</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="password">Password</label>
                                            <p class="ehint">Leave blank unless changing password</p>
                                            <input type="password" class="form-control" name="password" id="password">
                                        </div>
                                    </div>
                                    <input type="hidden" value="$row[username]" name="oldusername">
                                </div>
                                <div class="dialog-buttons">
                                    <div class="row">
                                        <button type="submit" name="updateuser" class="update-yes">Update</button>
                                        <button type="button" onclick="edit_modal()" class="update-no">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div> 
_END;
        } catch (PDOException $e) {
            //echo "Error <br>" . $e;
            echo "Error: Could not get users";
        }
    }
    function updateUser($newusername, $fullname, $email, $role, $access, $newpass, $oldusername) {
        try {
            $db = new db();
            if(empty($newpass)) {
                //echo $fullname."<br>". $newusername."<br>". $email."<br>". $role."<br>". $access."<br>". $oldusername;
                $updateUser = $db->pdo->prepare("UPDATE employee SET username = :username, fullname = :fullname, email = :email, role = :role, access_level = :access WHERE username = :user");
                $updateUser->execute(array(':username' => $newusername, ':fullname' => $fullname, ':email' => $email, ':role' => $role, ':access' => $access, ':user' => $oldusername));
            }
            else {
                $user = new user();
                $user->passwordRequirements($newpass);
                $newpass = password_hash($newpass, PASSWORD_BCRYPT);
                $updateUser= $db->pdo->prepare("UPDATE customer SET username = :username, password = :password, cfullname = :fullname, email = :email, role = :role, access_level = :access WHERE username = :user; UPDATE employee SET username = :username, password = :password, fullname = :fullname, email = :email, role = :role, access_level = :access WHERE username = :user");
                $updateUser->execute(array(':username' => $newusername, ':fullname' => $fullname, ':email' => $email, ':role' => $role, ':access' => $access, ':password' => $newpass, ':user' => $oldusername));
            }
            if($updateUser == true) {
                $_SESSION['alert'] = 'user2';
                echo "<script>window.history.go(-1);</script>";
            }
            else {
                //$_SESSION['alert'] = 'pfail';
                header('location:http://dominichart.uk/404.php');
            }
        } catch (PDOException $e) {
            echo "Error <br>" . $e;
        }
    }
    function getEmployees() {
        try {
            $db = new db();
            $result = $db->pdo->query("SELECT staff_id, username, fullname, role FROM employee");
            $result->execute();
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                if($_SESSION['username'] != $row['username']) {
                    echo <<<_END
                    <option value="$row[username]">$row[fullname] ($row[role])</option>
_END;
                }
            }
        } catch (PDOException $e) {
            //echo "Error <br>" . $e;
            echo "Error: Could not get employees";
        }
    }
    function getProjectManagers() {
        try {
            $db = new db();
            $getPM = $db->pdo->prepare("SELECT username, fullname FROM employee WHERE access_level = :level");
            $getPM->execute(array(':level' => '3'));
            while($row = $getPM->fetch(PDO::FETCH_ASSOC)) {
                if($_SESSION['username'] != $row['username']) {
                    echo <<<_END
                    <option value="$row[username]">$row[fullname]</option>
_END;
                }
            }
        } catch (PDOException $e) {
            echo "Error <br>" . $e;
        }
    }
    function getAllUsers() {
        try {
            $db = new db();
            $result = $db->pdo->query("SELECT username, cfullname FROM customer UNION SELECT username, fullname FROM employee");
            $result->execute();
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                if($_SESSION['username'] != $row['username']) {
                    echo "<option value='" . $row['username'] . "'>" . $row['cfullname'] . "</option>";
                }
            }
        } catch (PDOException $e) {
            //echo "Error <br>" . $e;
            echo "Error: Could not get users";
        }
    }
    function getContacts() {
        try {
            $db = new db();
            $result = $db->pdo->query("SELECT username, cfullname, access_level, NULL as role FROM customer UNION SELECT username, fullname, access_level, role FROM employee");
            $result->execute();
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                if(isset($row['role'])) {
                    $role = $row['role'];
                }
                else {
                    $role = 'Customer';
                }
                //Don't include own account
                if($_SESSION['username'] != $row['username']) {
                    //If the user is an employee or admin, they can message anyone
                    if($_SESSION['access'] == 2 || $_SESSION['access'] == 3) {
                        echo "<option value='".$row['username']."'>".$row['cfullname']." (".$role.")</option>";
                    }
                    else {
                        //Customer's can't message each other
                        if($row['access_level'] != 1) {
                            echo "<option value='".$row['username']."'>".$row['cfullname']." (".$role.")</option>";
                        }
                    }
                }
            }
        } catch (PDOException $e) {
            //echo "Error <br>" . $e;
            echo "Error: Could not get Contacts";
        }
    }
    function changePassword() {
        echo <<<_END
                    <div id="overlay">
                        <div class="form-container form-container-e">
                            <form method="post" action ="http://dominichart.uk/classes/user.php">
                               <div class="dialog-content clearfix">
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label for="currentpassword">Current Password</label>
                                            <input type="password" name="oldpassword" id="currentpassword" class="form-control" required>
                                        </div> 
                                        <div class="form-group password1 col-sm-12">
                                            <label for="password1">New Password</label>
                                            <input type="password" name="password1" id="password1" class="form-control" required>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="password2">Confirm Password</label>
                                            <input type="password" name="password2" id="password2" class="form-control" required>
                                        </div>
                                    </div>
                               </div>
                                <div class="dialog-buttons">
                                    <div class="row">
                                        <button type="submit" name="changepass" class="delete-yes">Change</button>
                                        <button type="button" onclick="edit_modal()" class="delete-no">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
_END;
    }
    function passwordRequirements($password) {
        $Uppercase = preg_match('@[A-Z]@', $password);
        $Lowercase = preg_match('@[a-z]@', $password);
        $Number = preg_match('@[0-9]@', $password);
        $Length = strlen($password < 9);
        if($_SESSION['access'] == 1) {
            $location = 'client.php';
        }
        elseif($_SESSION['access'] == 2) {
            $location = 'projects.php';
        }
        elseif($_SESSION['access'] == 3) {
            $location = 'dashboard.php';
        }
        elseif ($_SESSION['access'] == 4) {
            $location = 'admin.php';
        }
        if(!$Uppercase) {
            header('location:../'.$location.'') ;
            $_SESSION['error'] = 'pr1';
            exit();
        }
        if(!$Lowercase) {
            header('location:../'.$location.'') ;
            $_SESSION['error'] = 'pr2';
            exit();
        }
        if(!$Number) {
            header('location:../'.$location.'') ;
            $_SESSION['error'] = 'pr3';
            exit();
        }
        if(!$Length) {
            header('location:../'.$location.'') ;
            $_SESSION['error'] = 'pr4';
            exit();
        }
    }
    function changePass($oldpass, $newpass) {
        $user = new user();
        $user->passwordRequirements($newpass);
        $newpass = password_hash($newpass, PASSWORD_BCRYPT);
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT username, password FROM employee WHERE username = :username");
            $result->execute(array(':username' => $_SESSION['username']));
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $hash = $row['password'];
            if($_SESSION['access'] == 2) {
                $location = 'projects.php';
            }
            else if($_SESSION['access'] == 3) {
                $location = 'dashboard.php';
            }
            if(password_verify($oldpass, $hash)) {
                $stmt = $db->pdo->prepare("UPDATE employee SET password = :pass WHERE username = :user");
                $stmt->execute(array(':user' => $_SESSION['username'], ':pass' => $newpass));
                if($stmt == true) {
                    header('location:http://dominichart.uk/'.$location.'');
                    $_SESSION['alert'] = 'pass';
                } else {
                    echo "Something went wrong";
                }
            }
            else {
                header('location:../'.$location.'');
                $_SESSION['error'] = 'pass2';
            }
        }
        catch(PDOException $e) {
            echo "Error!:<br>" . $e;
        }
    }
    function addEmployee($fullname, $newusername, $email, $role) {
        try {
            $customer = new customer();
            //Generate random password
            $password = $customer->generatePassword();
            //Hash password for DB
            $newpassword = password_hash($password, PASSWORD_BCRYPT);
            $db = new db();
            //Check if username exists
            $result = $db->pdo->prepare("SELECT username FROM customer WHERE username = :username UNION SELECT username FROM employee WHERE username = :username");
            $result->execute(array(':username' => $newusername));
            $row = $result->fetch(PDO::FETCH_ASSOC);
            if ($row > 0) {
                $_SESSION['error'] = 'c2';
                header('location:http://dominichart.uk/dashboard.php');
            } else {
                //Check if email exists
                $result2 = $db->pdo->prepare("SELECT email FROM customer WHERE email = :email UNION SELECT email FROM employee WHERE email = :email");
                $result2->execute(array(':email' => $email));
                $row2 = $result2->fetch(PDO::FETCH_ASSOC);
                if ($row2 > 0) {
                    $_SESSION['error'] = 'c3';
                    header('location:http://dominichart.uk/dashboard.php');
                } else {
                    //Create employee
                    if($role == 'Project Manager') {
                        $access = '3';
                    }
                    else {
                        $access = '2';
                    }
                    $stmt = $db->pdo->prepare("INSERT INTO employee (username, password, fullname, email, role, access_level) VALUES (:username, :password, :name, :email, :role, :access)");
                    $stmt->execute(array(':username' => $newusername, ':password' => $newpassword, ':name' => $fullname, ':email' => $email, ':role' => $role, ':access' => $access));
                    if ($stmt == true) {
                        $to = $email;
                        $headers = "From: accounts@eproject.com\r\n";
                        $headers .= "MIME-VERSION: 1.0" . "\r\n";
                        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                        $subject = "Your Project Account Login";
                        $message = "
        <html>
            <head>
                <title>" . $subject . "</title>
            </head>
            <body>
                <div>
                    <p>" . $fullname . ",<br>Your employee account has been created, please find your login details below:</p>
                    <p>Username: <b>" . $newusername . "</b><br>Password: <b>" . $password . "</b><br>Role: <b>" . $role . "</b></p>
                    <p>You may login to view your projects <a href='http://localhost/fyp/index.php'>here</a>.</p>
                    <p class='disclaimer'>Project Services, please do not reply to this email as this inbox is not monitored.</p>
                </div>
                <style>
                p { 
                    font-size: 14px;
                    font-family: arial, sans-serif;
                }
                p.disclaimer {
                    font-weight: bold;
                }
                </style>
            </body>
        </html>
        ";
                        mail($to, $subject, $message, $headers);
                        $_SESSION['alert'] = 'e2';
                        header('location:http://dominichart.uk/dashboard.php');
                    } else {
                        echo "Something went wrong";
                    }
                }
            }
        }
        catch(PDOException $e) {
            //header('location:../404.php');
            echo "<br>Error".$e;
        }
    }
    function resetPass($user1, $email) {
        $customer = new customer();
        $password = $customer->generatePassword();
        $newpass = password_hash($password, PASSWORD_BCRYPT);
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT username, fullname, email FROM employee WHERE username = :user AND email = :email");
            $result->execute(array(':user' => $user1, ':email' => $email));
            $row = $result->fetch(PDO::FETCH_ASSOC);
            if($row > 0 ) {
                $stmt = $db->pdo->prepare("UPDATE employee SET password = :pass WHERE username = :user");
                $stmt->execute(array(':pass' => $newpass, ':user' => $user1));
                if($stmt == true) {
                    $to = $email;
                    $headers = "From: accounts@eproject.com\r\n";
                    $headers .= "MIME-VERSION: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $subject = "Requested Password Reset";
                    $message = "
        <html>
            <head>
                <title>" . $subject . "</title>
            </head>
            <body>
                <div>
                    <p>" . $row['fullname'] . ",<br>Your account password has been reset, please find your temporary password below:</p>
                    <p>Password: <b>" . $password . "</b></p>
                    <p>For security reasons, please change your password upon <a href='http://localhost/fyp/index.php'>login</a>.</p>
                    <p class='disclaimer'>Project Services, please do not reply to this email as this inbox is not monitored.</p>
                </div>
                <style>
                p { 
                    font-size: 14px;
                    font-family: arial, sans-serif;
                }
                p.disclaimer {
                    font-weight: bold;
                }
                </style>
            </body>
        </html>
        ";
                    mail($to, $subject, $message, $headers);
                    $_SESSION['alert'] = 'preset';
                    header('location:http://dominichart.uk/index.php');
                }
                else {
                    echo "Something went wrong";
                }
            }
            else {
                $_SESSION['alert'] = 'pfail';
                header('location:http://dominichart.uk/index.php');
            }
        } catch(PDOException $e) {
            echo "Error!:<br>" . $e;
        }
    }
    function toggleTipsEmp() {
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT newbie, access_level FROM employee WHERE staff_id = :id");
            $result->execute(array(':id' => $_SESSION['userID']));
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                if ($row['newbie'] == 'Yes') {
                    $enabled = 'No';
                    $talert = 't1';
                } else {
                    $enabled = 'Yes';
                    $talert = 't2';
                }
                if ($row['access_level'] == '2') {
                    $page = 'projects';
                }
                else {
                    $page = 'dashboard';
                }
                $stmt = $db->pdo->prepare("UPDATE employee SET newbie = :tips WHERE staff_id = :id");
                $stmt->execute(array(':tips' => $enabled, ':id' => $_SESSION['userID']));
                if ($stmt == true) {
                    $_SESSION['alert'] = $talert;
                    header('location:../'. $page .'.php');
                }
                else {
                    header('location:http://dominichart.uk/404.php');
                }
            }
        } catch(PDOException $e) {
            echo "Error!:<br>" . $e;
        }
    }
    function ActivateUser($username) {
        try {
            $db = new db();
            $checkUser = $db->pdo->prepare("SELECT activated FROM employee WHERE username = :user UNION SELECT activated FROM customer WHERE username = :user");
            $checkUser->execute(array(':user' => $username));
            $row = $checkUser->fetch(PDO::FETCH_ASSOC);
            if ($row['activated'] == 0) {
                $activated = 1;
            } elseif ($row['activated'] == 1) {
                $activated = 0;
            } else {
                header('location:http://dominichart.uk/404.php');
            }
            if($username == $_SESSION['username']) {
                echo "<script>location.reload();</script>";
                $_SESSION['error'] = 'user1';
            }
            else {
                $activateUser = $db->pdo->prepare("UPDATE employee SET activated = :activated WHERE username = :user; UPDATE customer SET activated = :activated WHERE username = :user");
                $activateUser->execute(array(':activated' => $activated, ':user' => $username));
                if ($activateUser == true) {
                    echo "<script>location.reload();</script>";
                    $_SESSION['alert'] = 'user1';
                } else {
                    header('location:http://dominichart.uk/404.php');
                }
            }
        } catch(PDOException $e) {
            echo "Error!:<br>" . $e;
        }
    }
    function getThisUserDelete($username) {
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT staff_id as userID, username, fullname as fullname FROM employee WHERE username = :user UNION SELECT customer_id AS userID, username, cfullname as fullname FROM customer WHERE username = :user");
            $result->execute(array(':user' => $username));
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo <<<_END
                    <div id="overlay">
                        <div class="form-container form-container-d">
                            <form method="post" action ="classes/task.php">
                               <div class="dialog-content clearfix">
                                    <p>Are you sure you want to delete user: <strong>$row[fullname]</strong>?</p>
                                    <input type="hidden" value="$row[userID]" name="userID">
                                    <input type="hidden" value="$row[username]" name="username">
                                </div>
                                <div class="dialog-buttons">
                                    <div class="row">
                                        <button type="submit" name="sdeleteuser" class="delete-yes">Yes</button>
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
    public function deleteUser($userID, $username) {
        try {
            $db = new db();
            $stmt = $db->pdo->prepare("DELETE FROM employee WHERE staff_id = :id AND username = :user; DELETE FROM customer WHERE customer_id = :id AND username = :user");
            $stmt->execute(array(':id' => $userID, ':title' => $username));
            if ($stmt == true) {
                echo "<script>window.history.go(-1);</script>";
                $_SESSION['alert'] = 'd5';
            } else {
                header('location:../404.php');
            }
        } catch (PDOException $e) {
            echo "Error: " . $e . "<br>";
        }
    }
}