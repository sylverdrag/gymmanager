<?php
/*
 * Getting page data
 */
##-F&R 01-##
$template = 'templates/tplt_main.php';

# header variables
$title = "Edit packages";
$keywords = "";
$description = "Edit existing training packages.";
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
require_once ("classes/gym_manager_class.php");
require_once ("classes/gym_edits_class.php");
$gym_edits = new gym_edits_class($dbh);
/* 
 * record extraction logic
 */

if (isset($_GET['packages_selected']) && $_GET['packages_selected'] == "yes")
{
    $order = $_POST["order"];
    $allowed_sort_orders = array();
    $allowed_sort_orders[] = "type ASC";
    $allowed_sort_orders[] = "type DESC";
    $allowed_sort_orders[] = "name ASC";
    $allowed_sort_orders[] = "name DESC";
    $allowed_sort_orders[] = "package_id ASC";
    $allowed_sort_orders[] = "package_id DESC";

    if (!in_array($order, $allowed_sort_orders))
    {
        $order = "package_id DESC"; // most recent first
    }
    
    $query_type = $_POST["constraint"];
    switch ($query_type)
    {
        case "All":
            $packages_arr = $gym_edits->get_all_packages_for_edit($order);
            break;

        case "OnlyToday":
            $today = date("Y-m-d");
            $packages_arr = $gym_edits->get_all_packages_for_edit_since($today, $order);
            break;
        
        case "fromDateChk":
            $from_date = $_POST["from_date"];
            $packages_arr = $gym_edits->get_all_packages_for_edit_since($from_date, $order);
            break;
        
        case "name_is":
            $name = $_POST["name"];
            $packages_arr = $gym_edits->get_packages_for_edit_by_name($name, $order);
            break;
        
        case "type":
            $type = $_POST["type"];
            $packages_arr = $gym_edits->get_packages_for_edit_by_name($type, $order);
            break;
        
        default :
            $packages_arr = $gym_edits->get_all_packages_for_edit($order);
            break;
    }
    //var_dump($trainers_arr);
    $package_edit_table = "";
    If (count($packages_arr) === 0){
        $package_edit_table = "<p style='color:red; font-weight:bold;'>Sorry, no match. Please expand your search.</p>";  
    }
    Else
    {
        $hide_cols = array();
        $hide_cols[] = "package_id";
        $primary = "package_id";
        $db_table_name = "sylver_gymmngr.training_packages";

        $package_edit_table = $gym_edits->format_edit_table($packages_arr, $primary, $db_table_name,$hide_cols);
    }

        
}
# Main content to buffer
ob_start();

##-F&R 02-##
?>
<div class="main">
    <div class="edit_table">
        <?= $package_edit_table; ?>
    </div>
    <div class="form">
        <p>This form allows you to modify the details of the trainers.</p>
        <p class="sectionH">Please select the records to edit</p> 
        <form action="index.php?pge=forms/edit_training_packages&packages_selected=yes" method="post" name="select_packages">
            <table>
                <tr><td colspan="2">
                        <div class="formItem">
                            <input type="radio" value="All" name="constraint" checked="checked" /> All training packages
                        </div></td></tr>
    <!--            <tr><td colspan="2">
                        <div class="formItem">
                            <input type="radio" value="OnlyToday" name="constraint" /> Only packages records created today
                        </div></td></tr>
                <tr><td>
                        <div class="formItem">
                        <input type="radio" value="fromDateChk" name="constraint" /> Only packages records created since  </td><td>
                        <input type="text" value="yyyy-mm-dd" name="from_date" onClick="select_packages.fromDate.value = ''" />
                        </div></td></tr>           
    -->
                <tr><td>
                        <div class="formItem">
                        <input type="radio" value="name_is" name="constraint" /> Package's name </td><td>
                        <input type="text" value="name" name="name" onClick="select_packages.name.value = ''" />
                        </div></td></tr>
                <tr><td>
                        <div class="formItem">
                        <input type="radio" value="type" name="constraint" /> Training type </td><td>
                        <select size="1" id="type" name="type" class="">
                            <option value="Pilates">Pilates</option>
                            <option value="Boxing">Boxing</option>
                            <option value="Yoga">Yoga</option>
                            <option value="TRX">TRX</option>
                            <option value="Bodyweight">Bodyweight training</option>
                        </select>
                        </div></td></tr>
                <tr><td style="text-align:right;">
                        <div class="formItem">
                            Order by</td><td>
                        <select name="order">
                            <option value="package_id ASC">Date (ASC)</option>
                            <option value="package_id DESC" selected>Date (Desc)</option>
                            <option value="type ASC">Type of training (ASC)</option>
                            <option value="type DESC">Type of training (DESC)</option>
                            <option value="name ASC">Package name (ASC)</option>
                            <option value="name DESC">Package name (DESC)</option>
                        </select> 
                        </div></td></tr>
            </table>
            <div id="submit" class="sectionH" style="padding: 5px 0 5px 120px;">
                <label>&nbsp; </label>
                <input type="submit" value="Display records" style="text-align:center;" />
            </div>
        </form>
    </div>

</div>

<?php
##-F&R 03-##
$content = ob_get_contents();
ob_end_clean();

##-F&R 04-##
##-F&R 05-##
?>