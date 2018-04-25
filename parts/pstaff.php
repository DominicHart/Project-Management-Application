<?php
include_once ('../classes/project.php');
if ($_REQUEST['js_submit_value']) {
    $id = $_REQUEST['js_submit_value'];
}
$project = new project();
echo <<<_END
    <div class="form-group col-sm-12">
        <label for="phase">Allocate Employee</label>
        <select name="pemp" id="pemp" class="form-control">
        <option selected>Select Employee</option>
_END;
$project->getPStaff($id);
echo <<<_END
        </select>
    </div>
_END;
