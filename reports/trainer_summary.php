<?php

/*
 * Getting page data
 */
##-F&R 01-##
$template = 'templates/tplt_main.php';

# header variables
$title          = "Trainer summary";
$keywords       = "";
$description    = "Shows all sessions for a given trainer.";
$extra          = "css/reporting.css";

##-## Database connection to sylver_gymmngr ##-##  
include_once('/home/sylver/includes/sylverp.php');
$db_name="sylver_gymmngr";
try {
    $dbh = new PDO('mysql:host=' .$hostname .';dbname='. $db_name, $user, $password);
}
catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

# Load classes 
require_once ("classes/gym_manager_class.php");

$gymmngr = new gym_manager_class($dbh);

require("classes/gym_reports_class.php");
$gym_reports = new gym_reports_class($dbh);

$trainer_list = $gymmngr->get_trainer_list();
$select_trainer = "<option value='' selected=\"selected\">Select trainer</option>";
for ($i = 0; $i < count($trainer_list); $i++)
{
    $trainer_name =  $trainer_list[$i]["last_name"] . " " . $trainer_list[$i]["first_name"];
    $select_trainer .= "<option value=\"" . $trainer_list[$i]["trainer_id"] . "\">" . $trainer_name . "</option>";
}
$select_trainer = "<select size=\"1\" id=\"trainer_id\" name=\"trainer_id\" class=\"entryField\">" . $select_trainer . "</select>";

 // Dodgy month handling - work out something better when internet is available
$period_start_date = date("Y-m") . "-01";
$months_with_31_days = array("01", "03", "05","07","08", "10", "12");
if (in_array(date("m"),$months_with_31_days))
{
    $period_end_date = date("Y-m") . "-31";
}
elseif (date("m")==="02") 
{
    $period_end_date = date("Y-m") . "-28";
}
else
{
    $period_end_date = date("Y-m") . "-30";
}

# Main content to buffer
ob_start();

##-F&R 02-##

?>
<div class="main">
    <form method="POST" enctype="multipart/form-data" class="dataInput" name="client_summary" >
        <div class="formTitle">Trainer summary</div>
        <fieldset class="formFieldSet">
            <input type="hidden" value="client_summary" name="report_type" id="report_type" />
            <div class="entry">
                <label class="lbl_regular" style="">
                    Trainer</label>
                    <?= $select_trainer; ?>
            </div> 
            <div class="entry">
                <label class="lbl_regular" style="">
                    From</label>
                <input type="text" size="38" maxlength="50" id="from_date" name="start_date" value="<?= $period_start_date; ?>" class="entryField"/> 
            </div>
            <div class="entry">
                <label class="lbl_regular" style="">
                    To</label>
                <input type="text" size="38" maxlength="50" id="to_date" name="end_date" value="<?= $period_end_date; ?>" class="entryField"/> 
            </div>
        </fieldset>
        <div id="bt_display_trainer_summary" class="bt_green_rounded" style="width: 200px; text-align: center;margin: 10px auto;">Display report</div>
    </form>  
    <div id="report_area">
        <h2>Trainer summary</h2>
    </div>
</div>
<script src="js/gym_reports.js" type="text/javascript" charset="utf-8"></script>

<?php
##-F&R 03-##
$content    = ob_get_contents();
ob_end_clean();

##-F&R 04-##



##-F&R 05-##

?>