<?php
include 'includes/session.php';
include_once ('classes/user.php');
include_once ('classes/project.php');
$user = new user();
$user->notLoggedin();
$project = new project();
if($access == 1) {
    header('location:client.php');
}
include 'parts/header.php';
?>
<div class="container-fluid">
    <div id="edit-delete"></div>
    <div class="row header">
        <h1>Your Files</h1>
        <div class="pull-right">
            <button class="dropdown-toggle" type="button" id="menu1" data-toggle="dropdown"><?php echo $_SESSION['fullname']; ?><span class="caret"></span></button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                <?php if($access == 4) { echo "<li class='head'><a role='menuitem' tabindex='-1' href='admin.php' accesskey='1'>Admin Panel</a></li>"; } ?>
                <li class="presentation"><?php if($access == 1) { echo "<a class='back' href='client.php' accesskey='2'>Projects</a>"; } else if($access == 2) {echo "<a class='back' href='projects.php' accesskey='2'>Projects</a>";} else {echo "<a class='back' href='dashboard.php' accesskey='2'>Projects</a>";} ?></li>
                <li class="head" role="presentation"><a role="menuitem" tabindex="-1" href="messages.php" accesskey='3'>Conversations</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="#" id="changepassword" accesskey='5'>Change Password</a></li>
                <li><a role="menuitem" tabindex="-1" href="classes/user.php?t1" accesskey='6'>Toggle Tips</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
    <div class="row dashboard files">
        <div>
            <nav class="nav-menu">
                <ul class="nav nav-pills">
                    <li class="active"><a data-toggle="tab" href="#browse-files">Browse Files</a></li>
                    <li ><a data-toggle="tab" href="#upload-file">Upload File</a></li>
                </ul>
            </nav>
        </div>
        <div class="col-sm-11">
            <div class="tab-content">
                <?php include 'includes/alerts.php'; ?>
                <div class="tab-pane fade in active" id="browse-files">
                    <h2>Your files</h2>
                    <p>You can view all files for your projects. Click on a file name to download it.</p>
                    <?php $project->getFiles(); ?>
                </div>
                <div class="tab-pane fade" id="upload-file">
                    <h2>Upload files</h2>
                    <form enctype="multipart/form-data" action="classes/project.php" method="post" autocomplete="off">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="project3">Project&nbsp;<b>*</b></label>
                                <select name="project" id="project3" class="form-control" required>
                                    <?php $project->getProjectTitles(); ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="file">Select File&nbsp;<b>*</b></label>
                                <input type="file" class="form-control" name="uploadFile" id="file" required>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" name="sfile" id="sfile">Upload File</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'parts/footer.php'; ?>
