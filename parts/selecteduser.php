<?php
include_once ('../classes/user.php');
if ($_REQUEST['js_submit_value']) {
    $username = $_REQUEST['js_submit_value'];
}
$user = new user();
echo <<<_END
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr><th>Username</th><th>Fullname</th><th>Email</th><th>Role</th><th>Last Session</th><th>Activated</th></tr>
            </thead>
_END;
$user->viewUser($username);
echo <<<_END
            <tbody>
            </tbody>
        </table>
    </div>
_END;
