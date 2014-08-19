<?php

/*
 * Getting page data
 */
##-F&R 01-##
$template = 'templates/tplt_main.php';

# header variables
$title          = "Sales overview";
$keywords       = "";
$description    = "Shows all sales for a specific period.";
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
if (isset($_GET['branch']))
{
    $branch = $_GET['branch'];
}
 else 
{
     $branch = "all";
}

# Main content to buffer
ob_start();

##-F&R 02-##

?>
<div class="main">
    <form method="POST" enctype="multipart/form-data" class="dataInput" name="sales_overview" >
        <div class="formTitle">Sales overview</div>
        <fieldset class="formFieldSet">
            <input type="hidden" value="sales_overview" name="report_type" id="report_type" />
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
            <div class="entry">
                <label class="lbl_regular" style="">
                    Branch</label>
                <select size="1" id="branch" name="branch" class="entryField">
                    <option value="all">All</option>
                    <option value="Punna">Punna</option>
                    <option value="Meechoke">Meechoke</option>
                </select> 
            </div> 
        </fieldset>
        <div id="bt_display_sales" class="bt_green_rounded" style="width: 200px; text-align: center;margin: 10px auto;">Display report</div>
    </form> 
    <div id="report_area">
         <h2>Sales</h2>
<?php
    $sales = $gym_reports->get_sales_by_branch($period_start_date, $period_end_date, $branch, "");
    
    echo $gym_reports->results_to_table($sales, 
                                        "Sales", 
                                        "Sales report from " . $period_start_date . " to ". $period_end_date, 
                                        $ignore_cols);
    
    $sales_by_client = $gym_reports->get_all_sales_by_client("");
    $ignore_cols = array();
    $ignore_cols[] = "Date";
    $ignore_cols[] = "Training type";
    echo $gym_reports->results_to_table($sales_by_client, "Sales", "Sales by client (All time/All branches)", $ignore_cols);
?>        
            
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