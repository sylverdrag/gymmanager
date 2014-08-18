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
    $query_type = $_POST["constraint"];
    switch ($query_type)
    {
        case "All":
            $clients_arr = $gym_edits->get_all_clients_for_edit();
            //var_dump($clients_arr);
            $clients_edit_table = "";
            If ($clients_arr[0]===""){
		$clients_edit_table = "<p>Sorry. There is no client in the database. Please add clients first</p>";  
            }
            Else
            {
                $hide_cols = array();
                $hide_cols[] = "client_id";
                $hide_cols[] = "created_date";
                $clients_edit_table  = "<form action=\"index.php?pge=forms/edit_clients&update_client=yes\" method=\"post\" name=\"select_clients\">\n";
                $clients_edit_table .= '<input type="hidden" value="sylver_gymmngr.clients" id="db_table_name" name="db_table_name" />';
                $clients_edit_table .= "<div id='client_edit_table' class='edit_table'>\n
                                            <div class='tbl_body'>\n";
                for ($i = 0; $i < count($clients_arr); $i++)
                {
                    $primary_key = $clients_arr[$i]["client_id"];
                    if ($i%2 == 0)
                    {
                        $records .=  "\n<fieldset id='".$primary_key."' class='record record_even clearfix'>\n" .
                                        "\n<div id='record_$i' class='record_data'>";
                    }
                    else 
                    {
                        $records .= "\n<fieldset id='".$primary_key."' class='record record_odd clearfix'>\n" .
                                        "\n<div id='record_$i' class='record_data'>";
                    }
                    
                    
                    foreach ($clients_arr[$i] as $key => $value)
                    {
                        if (!in_array($key, $hide_cols))
                        {
                            switch ($key)
                            {
                                case "sex":
                                    $enum_values = array("male", "female");
                                    $options = "<option value=\"" . $value . "\">" . $value . "</option>";
                                    foreach ($enum_values as $enum_key => $enum_value)
                                    {
                                        if ($enum_value !== $value)
                                        {
                                            $options .= "<option value=\"" . $enum_value . "\">" . $enum_value . "</option>";
                                        }
                                    }
                                    $selector = "<select class='edit_input $primary_key' size=\"1\" id=\"$key\" name=\"$key\">" . $options . "</select>";
                                    $record .= "\n<div class='$key record_entry'>\n" . 
                                        "<label class='lbl_regular'>" .$key . "</label> " .
                                        $selector .
                                        "\n</div>\n";
                                    break;
                                
                                default :
                                $record .= "<div class='$key record_entry'>\n" . 
                                        "<label class='lbl_regular'>" .$key . "</label> " .
                                        '<input class="edit_input ' . $primary_key .'" type="text" name="'. $key .'" id="'. $key .'" value="'. $value .'" class=""/> ' . 
                                        "\n</div>\n";
                                    break;
                            }
                        }
                        else 
                        {
                            $record .= "<div class='$key record_entry' style='display:none'>\n" . 
                                    '<input class="edit_input '.$primary_key.'" type="text" name="'. $key .'" id="'. $key .'" value="'. $value .'" class=""/> ' .  
                                    "\n</div>\n";
                        }
                    }
// No delete function for now. Consider an option to make a client inactive.                   
//                    $record_delete =    "<div id='".$clients_arr[$i]["client_id"]."' class='bt_delete'>". 
//                                        "<img src='images/operation_delete.png' alt='Delete'></div>"; 
                    $record_update =    "\n<div id='" . $clients_arr[$i]["client_id"] ."' class='bt_update'>\n" .
                                        "<img src='images/operation_update.png' alt='Update'></div>";
                    $records .= $record . "\n</div>\n" . 
//                                $record_delete . 
                                $record_update;
                    $records .= "\n</fieldset>\n";
                    $record = "";
                }
                $clients_edit_table .= $records . "\n</div>\n<caption>$caption</caption>\n</div>\n";
                $clients_edit_table .= "\n</form>\n";
                }
            break;
        
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
                        <input type="text" value="yyyy-mm-dd" name="fromDate" onClick="selectLots.fromDate.value = ''" />
                        </div></td></tr>
                <tr><td>
                        <div class="formItem">
                        <input type="radio" value="nameIs" name="constraint" /> Client last name is  </td><td>
                        <input type="text" value="Last name" name="name" onClick="selectLots.name.value = ''" />
                        </div></td></tr>
                <tr><td style="text-align:right;">
                        <div class="formItem">
                            Order by</td><td>
                        <select name="Order">
                            <option value="last_name" selected>Last name</option>
                            <option value="created_date">Creation date</option>
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