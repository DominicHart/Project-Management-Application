<?php
    include 'includes/session.php';
    if(isset($thisuser)) {
        if($access == 1) {
            header('location:client.php');
        } else if ($access == 2) {
            header('location:projects.php');
        } else {
            header('location:dashboard.php');
        }
    }
    include 'parts/header.php';
?>
<div class="login">
    <div class="content">
        <h2 class="title">Project Login</h2>
        <ul class="nav nav-pills">
            <li class="active"><a data-toggle="tab" href="#stafflogin" title="Login as an employee">Employee</a></li>
            <li><a data-toggle="tab" href="#clientlogin" title="Login as a customer">Customer</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="stafflogin">
                <form action="classes/user.php" method="post" autocomplete="off" id="loginemp">
                    <div class="form-group">
                        <label for="username" class="sr-only">Username</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="Username" autofocus required>
                    </div>
                    <div class="form-group passwordemp">
                        <label for="password" class="sr-only">Password</label>
                        <input type="password" class="form-control passwordlogin" name="password" id="passwordemp" placeholder="Password" autocomplete="new-password" required>
                    </div>
                    <button type="submit" name="login">Login</button>
                    <a href="resete.php">Forgot your password?</a>
                </form>
            </div>
            <div class="tab-pane fade" id="clientlogin">
                <form action="classes/user.php" method="post" autocomplete="off" id="logincust">
                    <div class="form-group">
                        <label for="username" class="sr-only">Username</label>
                        <input type="text" class="form-control" name="username" id="username2" placeholder="Username" required>
                    </div>
                    <div class="form-group passwordcust">
                        <label for="password2" class="sr-only">Password</label>
                        <input type="password" class="form-control passwordlogin" name="password" id="passwordcust" placeholder="Password" autocomplete="new-password" required>
                    </div>
                    <button type="submit" name="login2">Login</button>
                    <a href="resetc.php">Forgot your password?</a>
                </form>
            </div>
        </div>
            <?php if(isset($_SESSION['error'])) {
                $error = $_SESSION['error'];
                if($error == 1)
                {
                    echo "<p class='error'>Incorrect username or password</p>";
                } else if($error == 2) {
                    echo "<p class='error'>Your account has been deactivated, please contact an admin if you think this is an error.</p>";
                }
                unset($_SESSION['error']);
            }
            if(isset($_SESSION['alert'])) {
                if($_SESSION['alert'] == 'preset') {
                    echo "<p class='ok'>A reset email has been sent.</p>";
                }
                else if($_SESSION['alert'] == 'pfail') {
                    echo "<p class='error'>Incorrect username or email address.</p>";
                }
                unset($_SESSION['alert']);
            }?>
    </div>
</div>
<?php include 'parts/footer.php'; ?>
