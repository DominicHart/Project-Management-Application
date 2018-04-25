<?php
include 'includes/session.php';
include_once ('classes/user.php');
include_once ('classes/message.php');
$user = new user();
$user->notLoggedin();
$message = new message();
include 'parts/header.php';
?>
<div class="container-fluid">
    <div id="edit-delete"></div>
    <div class="row header">
        <h1>Messages</h1>
        <div class="pull-right">
            <button class="dropdown-toggle" type="button" id="menu1" data-toggle="dropdown"><?php echo $_SESSION['fullname']; ?><span class="caret"></span></button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                <?php if($access == 4) { echo "<li class='head'><a role='menuitem' tabindex='-1' href='admin.php' accesskey='1'>Admin Panel</a></li>"; } ?>
                <li class="presentation"><?php if($access == 1) { echo "<a class='back' href='client.php' accesskey='2'>Projects</a>"; } else if($access == 2) {echo "<a class='back' href='projects.php' accesskey='2'>Projects</a>";} else {echo "<a class='back' href='dashboard.php' accesskey='2'>Projects</a>";} ?></li>
                <?php if($access == 2 || $access == 3) { echo "<li role='presentation'><a role='menuitem' tabindex='-1' href='files.php' accesskey='4'>File Browser </a></li>";} ?>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="#" id="changepassword" accesskey='5'>Change Password</a></li>
                <li><a role="menuitem" tabindex="-1" href="classes/user.php?t1" accesskey='6'>Toggle Tips</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
    <div class="row messages">
        <div id="edit-delete"></div>
        <nav>
            <ul class="nav nav-pills">
                <li><a data-toggle="pill" href="#messages">Conversations</a></li><!--
                --><li><a data-toggle="pill" href="#new">New Conversation</a></li><!--
                --><li><a data-toggle="pill" href="#archive">Archive</a></li>
            </ul>
        </nav>
        <div class="col-sm-6 col-sm-offset-2">
            <div class="tab-content">
                <?php
                if(isset($_SESSION['alert'])) {
                    if($_SESSION['alert'] == 'm1') {
                        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The conversation has been created.</div>";
                    } else if ($_SESSION['alert'] == 'm2') {
                        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>Your reply has been sent.</div>";
                    } else if ($_SESSION['alert'] == 'm3') {
                        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The selected conversations were deleted.</div>";
                    } else if ($_SESSION['alert'] == 'm4') {
                        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The selected conversations were archived.</div>";
                    } else if ($_SESSION['alert'] == 'm5') {
                        echo "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><i class='fa fa-check' aria-hidden='true'></i>The selected conversations were moved to your inbox.</div>";
                    }
                    unset($_SESSION['alert']);
                } ?>
                <div class="tab-pane fade active in" id="messages">
                    <h2>Messages</h2>
                    <?php $message->getMessages(); ?>
                </div>
                <div class="tab-pane fade" id="new">
                    <h2>Create a conversation</h2>
                    <form method="post" action="classes/message.php">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="user">To</label>
                                    <select name="user" id="user" required class="form-control">
                                        <option selected>- Select user -</option>
                                        <?php $user->getContacts(); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="subject">Subject</label>
                                    <input type="text" class="form-control" name="subject" id="subject" maxlength="200">
                                </div>
                                <div class="form-group">
                                    <label for="message">Message</label>
                                    <textarea id="message" name="message" class="form-control" rows="5" required></textarea>
                                </div>
                                <button type="submit" name="newconv">Create Conversation</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="archive">
                    <h2>Archive</h2>
                    <?php $message->getArchive(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'parts/footer.php'; ?>
