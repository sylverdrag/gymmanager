<?php

/*
 * Getting page data
 */
##-F&R 01-##
$template = 'templates/tplt_main.php';

# header variables
$title          = "Client summary";
$keywords       = "";
$description    = "Shows all contracts and sessions for a given client.";
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

# load client select
$client_list = $gymmngr->get_client_list();
$select_client = "<option value='' selected=\"selected\">Select client</option>";
for ($i = 0; $i < count($client_list); $i++)
{
    $client_name =  $client_list[$i]["last_name"] . " " . $client_list[$i]["first_name"];
    $select_client .= "<option value=\"" . $client_list[$i]["client_id"] . "\">" . $client_name . "</option>";
}
$select_client = "<select size=\"1\" id=\"client_id\" name=\"client_id\" class=\"entryField\">" . $select_client . "</select>";


# Main content to buffer
ob_start();

##-F&R 02-##

?>
<div class="main">
    <form method="POST" enctype="multipart/form-data" class="dataInput" name="client_summary" >
        <div class="formTitle">Client summary</div>
        <fieldset class="formFieldSet">
            <input type="hidden" value="client_summary" name="report_type" id="report_type" />
            <div class="entry">
                <label class="lbl_regular" style="">
                    Client</label>
                    <?= $select_client; ?>
            </div> 
        </fieldset>
        <div id="bt_display_client_summary" class="bt_green_rounded" style="width: 200px; text-align: center;margin: 10px auto;">Display report</div>
    </form>  
    <div id="report_area">
        <h2>Client summary</h2>
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