<?php
include_once ('../classes/user.php');
if ($_REQUEST['js_submit_value']) {
    $id = $_REQUEST['js_submit_value'];
}
$user = new user();
echo <<<_END
<div id="overlay">
    <div class="form-container form-container-d">
        <form method="post" action="../classes/project.php">
            <div class="dialog-content clearfix">
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label for="selectpm">Select Project Manager&nbsp;<b>*</b></label>
                        <select name="selectpm" id="selectpm" class="form-control">
                            <option selected>Select Project Manager</option>
_END;
$user->getProjectManagers($id);
echo <<<_END
                        </select>
                    </div>
                </div>
                <input type="hidden" value="$id" name="id">
            </div>
            <div class="dialog-buttons">
                <div class="row">
                    <button type="submit" name="transfer" class="transfer">Transfer</button>
                    <button type="button" onclick="edit_modal()" class="update-no">Cancel</button>
                </div>
            </div>
        </form>
     </div>
</div> 
_END;
