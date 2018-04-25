<?php
if (session_status() == PHP_SESSION_NONE){
    session_start();
};
include_once ($_SERVER['DOCUMENT_ROOT'].'./fyp/includes/config.php');
$comment  = new comment();
if(isset($_POST['add-comment'])) {
    $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
    $id = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
    $comment->addComment($message, $id);
}

class comment {
    public function newComment($id) {
        echo <<<_END
                    <div id="overlay">
                        <div class="form-container form-container-e">
                            <form method="post" action="classes/comment.php">
                                <div class="dialog-header">
                                    <button type="button" onclick ="edit_modal()" class="pull-right close" >&times;</button>
                                    <h3>Add Comment</h3>
                               </div>
                               <div class="dialog-content clearfix">
                                    <div class="form-group">
                                        <label for="message">Comment</label>
                                        <textarea class="form-control" id="message" name="message" rows="5" cols="100"></textarea>
                                    </div>
                                    <input type="hidden" value="$id" name="id">
                                </div>
                                <div class="dialog-buttons">
                                    <div class="row">
                                        <button type="submit" name="add-comment" class="update-yes">Add Comment</button>
                                        <button type="button" onclick="edit_modal()" class="update-no">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>       
_END;
    }
    public function addComment($message, $id)
    {
        try {
            $db = new db();
            $stmt = $db->pdo->prepare("INSERT INTO comment (milestone_id, fullname, comment_text) VALUES (:id, :name, :comment)");
            $stmt->execute(array(':id' => $id, ':name' => $_SESSION['fullname'], ':comment' => $message));
            if ($stmt == true) {
                header('location:../index.php');
            } else {
                header('location:../404.php');
            }
        } catch (PDOException $e) {
            echo "Error: " . $e . "<br>";
        }
    }

}