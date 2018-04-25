<?php
include_once ('../classes/milestone.php');
if ($_REQUEST['js_submit_value']) {
    $id = $_REQUEST['js_submit_value'];
}
$milestone = new milestone();
echo <<<_END
    <div class="form-group">
        <label for="milestone">Select Milestone&nbsp;<b>*</b></label>
        <select name="milestone" id="milestone" class="form-control">
        <option selected>Select Milestone</option>
_END;
$milestone->getMilestoneTitles($id);
echo <<<_END
        </select>
    </div>
_END;
