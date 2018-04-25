<?php
include_once ('../classes/project.php');
if ($_REQUEST['js_submit_value']) {
    $id = $_REQUEST['js_submit_value'];
}
$project = new project();
echo <<<_END
    <div class="form-group col-sm-12">
        <label for="albudget">Allocate Budget</label>
_END;
$project->getBudget($id);
echo <<<_END
    </div>
_END;
