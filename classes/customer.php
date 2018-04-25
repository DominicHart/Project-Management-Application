<?php
if (session_status() == PHP_SESSION_NONE){
    session_start();
};
include_once ($_SERVER['DOCUMENT_ROOT'].'./fyp/includes/config.php');
include_once ('user.php');
$customer = new customer();
if(isset($_POST['scustomer'])) {
    $name = filter_var($_POST['fullname'], FILTER_SANITIZE_STRING);
    $newusername = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $customer->addCustomer($name, $newusername, $email);
}
if(isset($_POST['changepass'])) {
    $oldpass = filter_var($_POST['oldpassword'], FILTER_SANITIZE_STRING);
    $newpass1 = filter_var($_POST['password1'], FILTER_SANITIZE_STRING);
    $newpass2 = filter_var($_POST['password2'], FILTER_SANITIZE_STRING);
    if($newpass1 != $newpass2) {
        header('../client.php');
        $_SESSION['error'] = 'pass1';
    }
    else {
        $customer->changePass($oldpass, $newpass1);
    }
}
if(isset($_POST['resetc'])) {
    $user = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $customer->resetPass($user, $email);
}
class customer {
    function __construct(){}
    function getCustomers() {
        try {
            $db = new db();
            $result = $db->pdo->query("SELECT customer_id, username, cfullname FROM customer");
            $result->execute();
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo <<<_END
                <option value="$row[username]">$row[cfullname]</option>
_END;
            }
        } catch (PDOException $e) {
            echo "<br>".$e;
        }
    }
    function checkUsername($name) {
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT username FROM customer WHERE username = :username UNION SELECT username FROM employee WHERE username = :username");
            $result->execute(array(':username' => $name));
            $row = $result->fetch(PDO::FETCH_ASSOC);
            if($row > 0) {
                echo "<p class='error'>Username ".$name." is already taken.</p>";
            }
            else {
                echo "<p class='ok'>Username ".$name." is available!</p>";
            }
        } catch (PDOException $e) {
            echo "<br>".$e;
        }
    }
    function checkEmail($email) {
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT email FROM customer WHERE email = :email UNION SELECT email FROM employee WHERE email = :email");
            $result->execute(array(':email' => $email));
            $row = $result->fetch(PDO::FETCH_ASSOC);
            if($row > 0) {
                echo "<p class='error'>Email address ".$email." is already in use.</p>";
            }
            else {
                echo "<p class='ok'>Email address ".$email." is available!</p>";
            }
        } catch (PDOException $e) {
            echo "<br>".$e;
        }
    }
    function generatePassword($length = 9, $add_dashes = false, $available_sets = 'luds') {
        $sets = array();
        if(strpos($available_sets, 'l') !== false)
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        if(strpos($available_sets, 'u') !== false)
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        if(strpos($available_sets, 'd') !== false)
            $sets[] = '23456789';
        if(strpos($available_sets, 's') !== false)
            $sets[] = '!@#$%&*?';
        $all = '';
        $password = '';
        foreach($sets as $set)
        {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++)
            $password .= $all[array_rand($all)];
        $password = str_shuffle($password);
        if(!$add_dashes)
            return $password;
        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while(strlen($password) > $dash_len)
        {
            $dash_str .= substr($password, 0, $dash_len) . '-';
            $password = substr($password, $dash_len);
        }
        $dash_str .= $password;
        return $dash_str;
    }
    function addCustomer($fullname, $newusername, $email) {
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
                header('location:../dashboard.php');
            } else {
                //Check if email exists
                $result2 = $db->pdo->prepare("SELECT email FROM customer WHERE email = :email UNION SELECT email FROM employee WHERE email = :email");
                $result2->execute(array(':email' => $email));
                $row2 = $result2->fetch(PDO::FETCH_ASSOC);
                if ($row2 > 0) {
                    $_SESSION['error'] = 'c3';
                    header('location:../dashboard.php');
                } else {
                    //Create customer
                    $stmt = $db->pdo->prepare("INSERT INTO customer (username, password, cfullname, email) VALUES (:username, :password, :name, :email)");
                    $stmt->execute(array(':username' => $newusername, ':password' => $newpassword, ':name' => $fullname, ':email' => $email));
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
                    <p>" . $fullname . ",<br>Your client account has been created, please find your login details below:</p>
                    <p>Username: <b>" . $newusername . "</b><br>Password: <b>" . $password . "</b></p>
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
                        $_SESSION['alert'] = 'c2';
                        header('location:../dashboard.php');
                    } else {
                        echo "Something went wrong";
                    }
                }
            }
        }
        catch(PDOException $e) {
            header('location:../404.php');
        }
    }
    function changePassword() {
        echo <<<_END
                    <div id="overlay">
                        <div class="form-container form-container-e">
                            <form method="post" action ="classes/customer.php" class="changepass">
                               <div class="dialog-content clearfix">
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <label for="currentpassword">Current Password</label>
                                            <input type="password" name="oldpassword" id="currentpassword" class="form-control" required autofocus>
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
    function changePass($oldpass, $newpass) {
        $user = new user();
        $user->passwordRequirements($newpass);
        $newpass = password_hash($newpass, PASSWORD_BCRYPT);
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT username, password FROM customer WHERE username = :username");
            $result->execute(array(':username' => $_SESSION['username']));
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $hash = $row['password'];
            if(password_verify($oldpass, $hash)) {
                $stmt = $db->pdo->prepare("UPDATE customer SET password = :pass WHERE username = :user");
                $stmt->execute(array(':user' => $_SESSION['username'], ':pass' => $newpass));
                if($stmt == true) {
                    header('location:../client.php');
                    $_SESSION['alert'] = 'pass';
                } else {
                    echo "Something went wrong";
                }
            }
            else {
                header('location:../client.php');
                $_SESSION['error'] = 'pass2';
            }
        } catch(PDOException $e) {
            echo "Error!:<br>" . $e;
        }
    }
    function resetPass($user, $email) {
        $customer = new customer();
        $password = $customer->generatePassword();
        $newpass = password_hash($password, PASSWORD_BCRYPT);
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT username, cfullname, email FROM customer WHERE username = :user AND email = :email");
            $result->execute(array(':user' => $user, ':email' => $email));
            $row = $result->fetch(PDO::FETCH_ASSOC);
            if($row > 0 ) {
                $stmt = $db->pdo->prepare("UPDATE customer SET password = :pass WHERE username = :user");
                $stmt->execute(array(':pass' => $newpass, ':user' => $user));
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
                    <p>" . $row['cfullname'] . ",<br>Your account password has been reset, please find your temporary password below:</p>
                    <p>Password: <b>" . $password . "</b></p>
                    <p>For security reasons, please change your password upon <a href='http://dominichart.uk/index.php'>login</a>.</p>
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
                    header('location:../index.php');
                }
                else {
                    echo "Something went wrong";
                }
            }
            else {
                $_SESSION['alert'] = 'pfail';
                header('location:../index.php');
            }
        } catch(PDOException $e) {
            echo "Error!:<br>" . $e;
        }
    }
}