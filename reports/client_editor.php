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
$extra_script   = '<script src="js/gym_reports.js" type="text/javascript" ></script>';

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
include 'classes/dBug.php';
require_once ("classes/gym_manager_class.php"); // The path needs to be changed if it is called from the index page!!!

$gymmngr = new gym_manager_class($dbh);

require("classes/gym_reports_class.php");
$gym_reports = new gym_reports_class($dbh);

require("classes/data_formatting_class.php");
$data_formatter = new data_formatting_class($dbh);

$client_nb = $_GET["client_nb"]; // The client number is the row number of the client in client table, not the client ID (makes it easier to navigate through the clients
if (preg_match("/^\d+$/", $client_nb) == 0)
{
    echo 'The client number is not valid. Displaying the first client by default';
    $client_nb = 0;
}
$client_nb = intval($client_nb);
$total_nb_clients = $gym_reports->count_clients();
$client_arr = $gym_reports->get_client_details_by_number($client_nb);

$client_tbl = $data_formatter->array_to_editable_2col_table($client_arr, "clients"); // Client table
$client_tbl = '<div id="client" data-client_id="'.$client_arr["client_id"].'" style=""><h2>Client:</h2><br>' . $client_tbl . "<hr></div>";

/* $client_contracts_and_sessions_tbl contains the formatted data for the client, 
 * all his contracts and all the sessions related to each contract.
 */
$client_contracts_and_sessions_tbl = $client_tbl; 

$client_contracts = $gym_reports->get_all_contracts_for_client($client_arr["client_id"], "");
if (count($client_contracts) > 0 )
{
    $client_sessions = array();
    
    foreach ($client_contracts as $key => $value)
    {
        $client_contracts_and_sessions_tbl .= '<div id="'.$value["contract_id"].'" data-contract_id="'.$value["contract_id"].'" class="contract" style="float: left;"><h2>Contract:</h2><br>'; 
        $contracts_tbl = $data_formatter->array_to_editable_2col_table($value, "contracts"); // Contract table
        $client_contracts_and_sessions_tbl .= $contracts_tbl  . "</div>";
        $contract_id = $value["contract_id"];
        $sessions = $gym_reports->get_all_sessions_for_contract($contract_id, "");
        $client_sessions = array_merge($client_sessions, $sessions);
        $sessions_tbl = $data_formatter->twodim_array_to_editable_table($client_sessions, "sessions");
        $client_contracts_and_sessions_tbl .= '<div id="session" class="session_for_'.$value["contract_id"].'" style=""><h2>Sessions:</h2><br>' . $sessions_tbl . "</div><hr style='float:none; clear:both' />";
        $client_sessions = array(); // reset for the next contract
    }
}
else
{
    $client_contracts = "No contracts found for this client";
}

# Main content to buffer
ob_start();

##-F&R 02-##

?>
<div class="main">
    <form method="POST" enctype="multipart/form-data" class="dataInput" name="client_summary" >
        <div class="formTitle">Client Editor</div>
        <fieldset class="formFieldSet" style="width: 450px;margin: 0 auto;text-align: center;">
            <input type="hidden" value="client_editor" name="report_type" id="report_type" />
            <div class="entry">
                <div id="bt_first_client" class="bt_nav_rounded" style="width: 60px; text-align: center;margin: 10px 5px;float: left;">
                    <a href="<?php echo $_SERVER["PHP_SELF"] . "?pge=reports/client_editor&client_nb=0" ?>">
                        &lt;&lt;
                    </a>
                </div>
                <div id="bt_previous_client" class="bt_nav_rounded" style="width: 60px; text-align: center;margin: 10px 5px;float: left;">
                    <a href="<?php if ($client_nb > 0){ echo $_SERVER["PHP_SELF"] . "?pge=reports/client_editor&client_nb=" . ($client_nb-1); } else { echo $_SERVER["PHP_SELF"] . "?pge=reports/client_editor&client_nb=" . $client_nb; } ?>">
                        &lt;
                    </a>
                </div>
                <div id="bt_next_client" class="bt_nav_rounded" style="width: 60px; text-align: center;margin: 10px 5px;float: left;">
                    <a href="<?php 
                                if ($client_nb < $total_nb_clients){ 
                                    echo $_SERVER["PHP_SELF"] . "?pge=reports/client_editor&client_nb=" . ($client_nb+1); 
                                } else { 
                                    echo $_SERVER["PHP_SELF"] . "?pge=reports/client_editor&client_nb=" . ($total_nb_clients-1); 
                                    
                                } 
                             ?>">
                        &gt;
                    </a>
                </div>
                <div id="bt_last_client" class="bt_nav_rounded" style="width: 60px; text-align: center;margin: 10px 5px;float: left;">
                    <a href="<?php echo $_SERVER["PHP_SELF"] . "?pge=reports/client_editor&client_nb=" . ($total_nb_clients-1); ?>">
                        &gt;&gt;
                    </a>
                </div>
                <div id="bt_update_all" class="bt_nav_rounded" style="width: 150px; text-align: center;margin: 10px 5px;float: left;">
                    Update records
                </div>    
            </div> 
        </fieldset>
    </form>  
    <div id="report_area">
        <?= $client_contracts_and_sessions_tbl; ?>
<?php

    //new dBug($client_contracts);
//    echo "<pre>";
//    print_r($client_sessions);
//    echo "</pre>";
?>
    </div>
  
</div>

<?php
##-F&R 03-##
$content    = ob_get_contents();
ob_end_clean();

//echo $content; #######################!! For testing purposes !!##################################
##-F&R 04-##



##-F&R 05-##

?>