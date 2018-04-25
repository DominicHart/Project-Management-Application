<?php
if (session_status() == PHP_SESSION_NONE){
    session_start();
};
include_once ($_SERVER['DOCUMENT_ROOT'].'./fyp/includes/config.php');
include_once ('message.php');
$mess = new message();
if(isset($_POST['newconv'])) {
    $receiver = filter_var($_POST['user'], FILTER_SANITIZE_STRING);
    $subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
    $mess->newConversation($receiver, $subject, $message);
}
if(isset($_POST['sreply'])) {
    $conversation = filter_var($_POST['conversation'], FILTER_SANITIZE_STRING);
    $receiver = filter_var($_POST['receiver'], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['reply-message'], FILTER_SANITIZE_STRING);
    $mess->newMessage($conversation, $receiver, $message);
}
if(isset($_POST['sarchivemessages'])) {
    $ids = filter_var($_POST['ids'], FILTER_SANITIZE_STRING);
    $mess->archiveMessage($ids);
}
if(isset($_POST['sactivatemessages'])) {
    $ids = filter_var($_POST['ids'], FILTER_SANITIZE_STRING);
    $mess->activateMessage($ids);
}
if(isset($_POST['sdeletemessages'])) {
    $ids = filter_var($_POST['ids'], FILTER_SANITIZE_STRING);
    $mess->deleteMessage($ids);
}
class message
{
    function __construct(){}
    function newConversation($receiver, $subject, $message) {
        try {
           $db = new db();
            $stmt = $db->pdo->prepare("INSERT INTO conversations (author, receiver, subject, text) VALUES (:author, :receiver, :subject, :text)");
            $stmt->execute(array(':author' => $_SESSION['username'], ':receiver' => $receiver, ':subject' => $subject, ':text' => $message));
            if ($stmt == true) {
                header('location:../messages.php');
                $_SESSION['alert'] = 'm1';
            }
            else {
                echo "Something went wrong";
            }
        } catch (PDOException $e) {
            echo "Error <br>" . $e;
        }
    }
    function newMessage($conversation, $receiver, $message) {
        try {
            $db = new db();
            $stmt = $db->pdo->prepare("INSERT INTO messages (author, receiver, text, conversation) VALUES (:author, :receiver, :text, :conversation)");
            $stmt->execute(array(':author' => $_SESSION['username'], ':receiver' => $receiver, ':text' => $message, ':conversation' => $conversation));
            if ($stmt == true) {
                header('location:../messages.php');
                $_SESSION['alert'] = 'm2';
            }
            else {
                echo "Something went wrong";
            }
        } catch (PDOException $e) {
            echo "Error <br>" . $e;
        }
    }
    function getMessages() {
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT conversations.conversation_id, conversations.author, conversations.receiver, conversations.subject, conversations.text, conversations.date, conversations.archived, conversations.deleted_by_sender, conversations.deleted_by_receiver, emp1.username, emp1.fullname, cust1.username, cust1.cfullname FROM conversations LEFT JOIN employee emp1 ON conversations.author = emp1.username LEFT JOIN employee emp2 ON conversations.receiver = emp2.username LEFT JOIN customer cust1 ON conversations.author = cust1.username LEFT JOIN customer cust2 ON conversations.receiver = cust2.username WHERE (archived IS NULL) AND (author = :user OR receiver = :user) AND (deleted_by_sender != :user AND deleted_by_receiver != :user) ORDER BY date DESC");
            $result->execute(array(':user' => $_SESSION['username']));
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            if(empty($rows)) {
                echo "<h3>You do not have any active conversations. You can create one <a data-toggle='pill' class='alert-link' href='#new'>here</a>.</h3>";
            }
            else {
                echo <<<_END
        <label><input type="checkbox" value="selectall" id="selectall">Select all</label>
_END;
                $timeCheck = strtotime("-24 hours");
                $weekCheck = strtotime("-7 days");
                while($row = array_shift($rows)) {
                    $oDate = strtotime($row['date']);
                    if($oDate >= $weekCheck) {
                        if ($oDate <= $timeCheck) {
                            $date = date("l", strtotime($row['date']));
                        } else {
                            $date = date("H:i", strtotime($row['date']));
                        }
                    } else {
                        $date = date("d/m/y", strtotime($row['date']));
                    }
                    $author = $row['author'];
                    $receiver = $row['receiver'];
                    if($author == $_SESSION['username']) {
                        $author = 'you';
                    }
                    if($receiver == $_SESSION['username']) {
                        $receiver = 'you';
                    }
                    echo <<<_END
                                <div class="conversation">
                                    <input type="checkbox" value="$row[conversation_id]" class="selectmessage"><h4><a href='#'>$author - $receiver</a> $row[subject]<b class="pull-right">$date</b></h4>
_END;
                    echo "<div><p>".nl2br($row['text'])."<b class='pull-right'>".$author." ".$date."</b></p>";
                    $replies = $db->pdo->prepare("SELECT messages.message_id, messages.author, messages.receiver, messages.text, messages.mdate, messages.conversation, emp1.username, emp1.fullname, cust1.username, cust1.cfullname FROM messages LEFT JOIN employee emp1 ON messages.author = emp1.username LEFT JOIN employee emp2 ON messages.receiver = emp2.username LEFT JOIN customer cust1 ON messages.author = cust1.username LEFT JOIN customer cust2 ON messages.receiver = cust2.username WHERE messages.conversation = :id");
                    $replies->execute(array(':id' => $row['conversation_id']));
                    while($reply = $replies->fetch(PDO::FETCH_ASSOC)) {
                        $mDate = strtotime($reply['mdate']);
                        if($mDate >= $weekCheck) {
                            if ($mDate <= $timeCheck) {
                                $date2 = date("l", strtotime($reply['mdate']));
                            } else {
                                $date2 = date("H:i", strtotime($reply['mdate']));
                            }
                        } else {
                            $date2 = date("d/m/y", strtotime($reply['mdate']));
                        }
                        $author = $reply['author'];
                        if($author == $_SESSION['username']) {
                            $author = '<p>'.nl2br($reply["text"]).'<b class="pull-right">you '.$date2.'</b></p>';
                        }
                        else {
                            $author = '<p class="them">'.nl2br($reply["text"]).'<b class="pull-right them">'.$reply['author'].' '.$date2.'</b></p>';
                        }
                        echo "<div class='reply'>$author</div>";
                    }
                    echo <<<_END
                            <form class="new-reply" method="post" action="classes/message.php">
                                <div class="form-group">
                                    <label class="sr-only" for="reply-message">Reply</label>
                                    <textarea name="reply-message" id="reply-message" class="form-control" maxlength="1000" rows="2" required placeholder="Write a reply"></textarea>
                                </div>
                                <input type="hidden" name="conversation" value="$row[conversation_id]">
                                <input type="hidden" name="receiver" value="$row[receiver]">
                                <button type="submit" name="sreply" class="reply-button">Send Reply</button>
                            </form>
                        </div>
                    </div>
_END;
                }
            }
        } catch (PDOException $e) {
            echo "Error <br>" . $e;
        }
    }
    function getArchive() {
        try {
            $db = new db();
            $result = $db->pdo->prepare("SELECT * FROM conversations WHERE (archived = :archived) AND (author = :user OR receiver = :user) ORDER BY date DESC");
            $result->execute(array(':user' => $_SESSION['username'], ':archived' => '1'));
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            if(empty($rows)) {
                echo "<h3>You do not have any archived conversations.</h3>";
            }
            else {
                echo <<<_END
        <label><input type="checkbox" value="selectarchive" id="selectarchive">Select all</label>
_END;
                while($row = array_shift($rows)) {
                    $date = date("d/m/y", strtotime($row['date']));
                    $author = $row['author'];
                    $receiver = $row['receiver'];
                    if($author == $_SESSION['username']) {
                        $author = 'you';
                    }
                    if($receiver == $_SESSION['username']) {
                        $receiver = 'you';
                    }
                    echo <<<_END
                                <div class="conversation">
                                    <input type="checkbox" value="$row[conversation_id]" class="selectitem"><h4><a href='#'>$author - $receiver</a> $row[subject]<b class="pull-right">$date</b></h4>
_END;
                    echo "<div><p>".nl2br($row['text'])."<b class='pull-right'>".$author." ".$date."</b></p>";
                    $replies = $db->pdo->prepare("SELECT * FROM messages WHERE conversation = :id");
                    $replies->execute(array(':id' => $row['conversation_id']));
                    while($reply = $replies->fetch(PDO::FETCH_ASSOC)) {
                        $date2 = date("d/m/y", strtotime($reply['mdate']));
                        $author = $reply['author'];
                        if($author == $_SESSION['username']) {
                            $author = '<p>'.nl2br($reply["text"]).'<b class="pull-right">you '.$date2.'</b></p>';
                        }
                        else {
                            $author = '<p class="them">'.nl2br($reply["text"]).'<b class="pull-right them">'.$reply['author'].' '.$date2.'</b></p>';
                        }
                        echo "<div class='reply'>$author</div>";
                    }
                    echo "<p><strong>This conversation is archived. To reply, move it to your inbox.</strong></p></div></div>";
                }
            }
        } catch (PDOException $e) {
            echo "Error <br>" . $e;
        }
    }
    function getMessageEdit($id) {

    }
    function editMessage($id) {

    }
    function getMessageArchive($ids) {
        try {
            echo <<<_END
                   <div id="overlay">
                       <div class="form-container">
                           <form method="post" action="classes/message.php">
                              <div class="dialog-content clearfix">
                                   <p>You are about to archive the selected conversations. To create replies, you will need to move them to your inbox</strong>.</p>
                                   <input type="hidden" value="$ids" name="ids">
                               </div>
                               <div class="dialog-buttons">
                                   <div class="row">
                                       <button type="submit" name="sarchivemessages" class="delete-yes">Archive</button>
                                       <button type="button" onclick="edit_modal()" class="delete-no">Cancel</button>
                                   </div>
                               </div>
                           </form>
                       </div>
                   </div>
_END;
        } catch (PDOException $e) {
            echo "Error: " . $e . "<br>";
        }
    }
    function getMessageActive($ids) {
        try {
            echo <<<_END
                   <div id="overlay">
                       <div class="form-container">
                           <form method="post" action="classes/message.php">
                              <div class="dialog-content clearfix">
                                   <p>You are about to move the selected conversations to your inbox</strong>.</p>
                                   <input type="hidden" value="$ids" name="ids">
                               </div>
                               <div class="dialog-buttons">
                                   <div class="row">
                                       <button type="submit" name="sactivatemessages" class="delete-yes">Move to inbox</button>
                                       <button type="button" onclick="edit_modal()" class="delete-no">Cancel</button>
                                   </div>
                               </div>
                           </form>
                       </div>
                   </div>
_END;
        } catch (PDOException $e) {
            echo "Error: " . $e . "<br>";
        }
    }
    function archiveMessage($ids) {
        try {
            $db = new db();
            $conversations = explode(",",$ids);
            foreach ($conversations as $conversation):
                $stmt = $db->pdo->prepare("UPDATE conversations SET archived = :archived WHERE conversation_id = :id AND author = :author");
                $stmt->execute(array(':archived' => '1', ':id' => $conversation, ':author' => $_SESSION['username']));
                if($stmt == true) {
                    header('location:../messages.php');
                    $_SESSION['alert'] = 'm4';
                }
                else {
                    echo "Something went wrong";
                }
            endforeach;
        } catch (PDOException $e) {
            echo "Error: " . $e . "<br>";
        }
    }
    function activateMessage($ids) {
        try {
            $db = new db();
            $conversations = explode(",",$ids);
            foreach ($conversations as $conversation):
                $stmt = $db->pdo->prepare("UPDATE conversations SET archived = NULL WHERE conversation_id = :id AND author = :author");
                $stmt->execute(array(':id' => $conversation, ':author' => $_SESSION['username']));
                if($stmt == true) {
                    header('location:../messages.php');
                    $_SESSION['alert'] = 'm5';
                }
                else {
                    echo "Something went wrong";
                }
            endforeach;
        } catch (PDOException $e) {
            echo "Error: " . $e . "<br>";
        }
    }
    function getMessageDelete($ids) {
        try {
            echo <<<_END
                   <div id="overlay">
                       <div class="form-container form-container-d">
                           <form method="post" action ="classes/message.php">
                              <div class="dialog-content clearfix">
                                   <p>You are about to delete the selected conversations</strong>.</p>
                                   <input type="hidden" value="$ids" name="ids">
                               </div>
                               <div class="dialog-buttons">
                                   <div class="row">
                                       <button type="submit" name="sdeletemessages" class="delete-yes">Delete</button>
                                       <button type="button" onclick="edit_modal()" class="delete-no">Cancel</button>
                                   </div>
                               </div>
                           </form>
                       </div>
                   </div>
_END;
        } catch (PDOException $e) {
            echo "Error: " . $e . "<br>";
        }
    }
    function deleteMessage($ids) {
        try {
            $db = new db();
            $conversations = explode(",",$ids);
            foreach ($conversations as $conversation):
                $checkMessage = $db->pdo->prepare("SELECT conversation_id, author, receiver FROM conversations WHERE conversation_id = :id");
                $checkMessage->execute(array(':id' => $conversation));
                while($row = $checkMessage->fetch(PDO::FETCH_ASSOC)) {
                    $value = $_SESSION['username'];
                    if($row['author'] == $_SESSION['username']) {
                        $deleteMessage = $db->pdo->prepare("UPDATE conversations SET deleted_by_sender = :value");
                        $deleteMessage->execute(array(':value' => $value));
                    }
                    elseif($row['receiver'] == $_SESSION['username']) {
                        $deleteMessage = $db->pdo->prepare("UPDATE conversations SET deleted_by_receiver = :value");
                        $deleteMessage->execute(array(':value' => $value));
                    }
                    else {
                        echo "Something went wrong";
                    }
                    $getDeleted = $db->pdo->prepare("SELECT deleted_by_sender, deleted_by_receiver FROM conversations WHERE conversation_id = :id");
                    $getDeleted->execute(array(':id' => $conversation));
                    $message = $checkMessage->fetch(PDO::FETCH_ASSOC);
                    if($message['deleted_by_sender'] != '0' && ($message['deleted_by_receiver'] != '0')) {
                        $delete = $db->pdo->prepare("DELETE FROM conversations WHERE conversation_id = :id");
                        $delete->execute(array(':id' => $conversation));
                    }
                }
                if($deleteMessage == true) {
                    header('location:../messages.php');
                    $_SESSION['alert'] = 'm3';
                }
                else {
                    echo "Something went wrong";
                }
            endforeach;
        } catch (PDOException $e) {
            echo "Error: " . $e . "<br>";
        }
    }
}