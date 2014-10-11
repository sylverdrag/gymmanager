<?php
/* 
This page displays the login form 
*/
##-F&R 01-##
$template = 'templates/tplt_main.php';

# header variables
$title          = "Dashboard";
$keywords       = "";
$description    = "Welcome to the Gym Manager.";
$extra       = "css/reporting.css";

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

# Load class
require("classes/gym_reports_class.php");
$gym_reports = new gym_reports_class($dbh);
function is_date($date_to_check)
{
    $pattern = "/^\d{4}-\d{2}-\d{2}/";
    if (preg_match($pattern, $date_to_check) == 1){
            return true;
    } else {
            return false;
    }
}

if (is_date($_GET["start_date"]) && is_date($_GET["end_date"]))
{
    $period_start_date = $_GET["start_date"];
    $period_end_date   = $_GET["end_date"];
}
else
{
    
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
}
# Main content to buffer
ob_start();

##-F&R 02-##

?>
<div id="page">
   
<?php
// GI
    $gi = $gym_reports->calculate_GI($period_start_date, $period_end_date);
    $gi = "THB " . number_format($gi);
// Trainers fees
    $total_trainer_fees = $gym_reports->calculate_total_trainer_fees($period_start_date, $period_end_date);
    $total_trainer_fees = "THB " . number_format($total_trainer_fees);
// VSD
    $vsd = $gym_reports->calculate_VSD($period_start_date, $period_end_date);
    $vsd = "THB " . number_format($vsd);

// Nb sessions
    $nb_sessions = $gym_reports->get_nb_sessions_in_period($period_start_date, $period_end_date);
?>    
    
    <p style="text-align: center;clear: both;">
        <label class="" style="">
            From</label>
        <input type="text" size="15" maxlength="50" id="from_date" name="start_date" value="<?= $period_start_date; ?>" style="width: 150px;padding: 4px;"/> 
        <label class="" style="">
            To</label>
        <input type="text" size="15" maxlength="50" id="to_date" name="end_date" value="<?= $period_end_date; ?>"  style="width: 150px;padding: 4px;" /> 
    </p>
        <div id="bt_update_dashboard" class="bt_green_rounded" style="width: 100px; height: 50px; text-align: center;margin: 10px auto;">Update</div>
    <div id="key_metrics" class="dashboard_widget">
        <h2>Key metrics</h2>
        <p style="text-align: center;"><?= "From " .$period_start_date . " to " . $period_end_date; ?></p>
        <ul style="list-style: none">
            <li>GI: <span id="gi_figure" class="key_data"><?= $gi; ?></span></li>
            <li>Total trainer fees: <span id="trainer_fees" class="key_data"><?= $total_trainer_fees; ?></span></li>
            <li>VSD: <span id="vsd_figure" class="key_data"><?= $vsd; ?></span></li>
            <li># Sessions: <span id="sessions_figure" class="key_data"><?= $nb_sessions; ?></span></li>
        </ul>
    </div>

    <div id="mini_sales_report" class="dashboard_widget">
         <h2>Sales</h2>
<?php
    $sales = $gym_reports->get_sales($period_start_date, $period_end_date, "");
    $ignore_cols[] = "Contract ID";
    echo $gym_reports->results_to_table($sales, 
                                        "Sales", 
                                        "Sales report from " . $period_start_date . " to ". $period_end_date, 
                                        $ignore_cols);
?>        
    </div>
    
    
    <div id="sessions_report" class="dashboard_widget">
         <h2>Sessions</h2>
<?php
    $ignore_cols = array();
    $client_sessions = $gym_reports->clients_sessions_in_period($period_start_date, $period_end_date, "");
    echo $gym_reports->results_to_table($client_sessions, 
            "Sessions", 
            "Client sessions from " . $period_start_date . " to ". $period_end_date, 
            $ignore_cols);

?>        
    </div>

    <div id="trainers_report" class="dashboard_widget">
         <h2>Trainers activity</h2>
<?php
$ignore_cols = array();
$all_trainer_activity = $gym_reports->all_trainer_activity_in_period($period_start_date, $period_end_date, $limit);
echo $gym_reports->results_to_table($all_trainer_activity, 
                                    "All_Trainer_Activity", 
                                    "All trainer activity from " . $period_start_date . " to ". $period_end_date, 
                                    $ignore_cols);
?>        
    </div>
    
    <div id="days_since_last_session" class="dashboard_widget">
         <h2>Days since last session</h2>
<?php
$ignore_cols = array();
// Adjust these values after feedback from Venus
$min = 0;
$max = 120;
$days_since_last_session = $gym_reports->days_since_last_session($min, $max, "");
echo $gym_reports->results_to_table($days_since_last_session, 
                                    "Days_since_last_session", 
                                    "Days since last session", 
                                    $ignore_cols);
?>        
    </div>
</div>

<?php
##-F&R 03-##
$content    = ob_get_contents();
ob_end_clean();

##-F&R 04-##



##-F&R 05-##

?>
