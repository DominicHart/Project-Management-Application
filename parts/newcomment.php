<?php
include '../classes/comment.php';
if ($_REQUEST['js_submit_value']) {
    $id = $_REQUEST['js_submit_value'];
}
$comment = new comment();
$comment->newComment($id);