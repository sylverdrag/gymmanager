<?php
/*
 * Getting page data
 */
##-F&R 01-##
$template = 'templates/tplt_main.php';

# header variables
$title = "Edit clients";
$keywords = "";
$description = "Edit existing clients.";
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

if (isset($_GET['client_selected']) && $_GET['client_selected'] == "yes")
{
    $order = $_POST["order"];
    $allowed_sort_orders = array();
    $allowed_sort_orders[] = "last_name ASC";
    $allowed_sort_orders[] = "last_name DESC";
    $allowed_sort_orders[] = "created_date ASC";
    $allowed_sort_orders[] = "created_date DESC";

    if (!in_array($order, $allowed_sort_orders))
    {
        $order = "created_date ASC"; // most recent first
    }
    
    $query_type = $_POST["constraint"];
    switch ($query_type)
    {
        case "All":
            $clients_arr = $gym_edits->get_all_clients_for_edit($order);
            break;

        case "OnlyToday":
            $today = date("Y-m-d");
            $clients_arr = $gym_edits->get_clients_for_edit_since($today, $order);
            break;
        
        case "fromDateChk":
            $from_date = $_POST["from_date"];
            $clients_arr = $gym_edits->get_clients_for_edit_since($from_date, $order);
            break;
        
        case "name_is":
            $name = $_POST["name"];
            $clients_arr = $gym_edits->get_clients_for_edit_by_name($name, $order);
            break;
        
        default :
            $clients_arr = $gym_edits->get_all_clients_for_edit($order);
            break;
    }
    //var_dump($clients_arr);
    $clients_edit_table = "";
    If (count($clients_arr) === 0){
        $clients_edit_table = "<p style='color:red; font-weight:bold;'>Sorry, no match. Please expand your search.</p>";  
    }
    Else
    {
        $hide_cols = array();
        $hide_cols[] = "client_id";
        $hide_cols[] = "created_date";
        $primary_key_name = "client_id";
        $db_table_name = "sylver_gymmngr.clients";

        $clients_edit_table = $gym_edits->format_edit_table($clients_arr, $primary_key_name, $db_table_name, $hide_cols);
    }

        
}
# Main content to buffer
ob_start();

##-F&R 02-##
?>
<div class="main">
    <div class="edit_table">
        <?= $clients_edit_table; ?>
    </div>
    <div class="form">
        <p>This form allows you to modify the details of the clients.</p>
        <p class="sectionH">Please select the records to edit</p> 
        <form action="index.php?pge=forms/edit_clients&client_selected=yes" method="post" name="select_clients">
            <table>
                <tr><td colspan="2">
                        <div class="formItem">
                            <input type="radio" value="All" name="constraint" checked="checked" /> All clients
                        </div></td></tr>
                <tr><td colspan="2">
                        <div class="formItem">
                            <input type="radio" value="OnlyToday" name="constraint" /> Only client records created today
                        </div></td></tr>
                <tr><td>
                        <div class="formItem">
                        <input type="radio" value="fromDateChk" name="constraint" /> Only client records created since  </td><td>
                        <input type="text" value="yyyy-mm-dd" name="from_date" onClick="select_clients.fromDate.value = ''" />
                        </div></td></tr>
                <tr><td>
                        <div class="formItem">
                        <input type="radio" value="name_is" name="constraint" /> Client's name contain  </td><td>
                        <input type="text" value="name" name="name" onClick="select_clients.name.value = ''" />
                        </div></td></tr>
                <tr><td style="text-align:right;">
                        <div class="formItem">
                            Order by</td><td>
                        <select name="order">
                            <option value="last_name ASC">Last name (ASC)</option>
                            <option value="last_name DESC">Last name (Desc)</option>
                            <option value="created_date ASC" selected>Creation date (ASC)</option>
                            <option value="created_date DESC">Creation date (DESC)</option>
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