<?php
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
##-## Connected! ##-##

require_once ("classes/gym_manager_class.php");

$gymmngr = new gym_manager_class($dbh);
$client_list = $gymmngr->get_client_list();
$select_client = "<option value='' selected=\"selected\">Select client</option>";
for ($i = 0; $i < count($client_list); $i++)
{
    $client_name =  $client_list[$i]["last_name"] . " " . $client_list[$i]["first_name"];
    $select_client .= "<option value=\"" . $client_list[$i]["client_id"] . "\">" . $client_name . "</option>";
}
$select_client = "<select size=\"1\" id=\"client_id\" name=\"client_id\" class=\"entryField\">" . $select_client . "</select>";

$trainer_list = $gymmngr->get_trainer_list();
$select_trainer = "<option value='' selected=\"selected\">Select trainer</option>";
for ($i = 0; $i < count($trainer_list); $i++)
{
    $trainer_name =  $trainer_list[$i]["last_name"] . " " . $trainer_list[$i]["first_name"];
    $select_trainer .= "<option value=\"" . $trainer_list[$i]["trainer_id"] . "\">" . $trainer_name . "</option>";
}
$select_trainer = "<select size=\"1\" id=\"trainer_id\" name=\"trainer_id\" class=\"entryField\">" . $select_trainer . "</select>";

/*
 * Getting page data
 */
##-F&R 01-##
$template = 'templates/tplt_main.php';

# header variables
$title          = "Log session";
$keywords       = "";
$description    = "Log a training session.";
$extra       = "";


# Main content to buffer
ob_start();

##-F&R 02-##

?>

<div class="main">
    <form action="handlers/gymHandler.php" method="POST" enctype="multipart/form-data" class="dataInput" name="AddUser" onSubmit="return confirmSubmit()">
        <div class="formTitle">Log session</div>
        <fieldset class="formFieldSet">
            <input type='hidden' id='mandatory' name='mandatory' value='session_id;date;client_id;contract_id;trainer_id'>
            <input type="hidden" value="log_session" id="formPurpose" name="formPurpose" />
            <input type="hidden" value="index.php?pge=forms/log_session" id="return_page" name="return_page" />

            <div class="entry">
                <label class="lbl_regular" style="">
                    Session id*</label>
                <input type="text" size="38" maxlength="15" name="session_id" id="session_id" value="<?= "ses_" . date("Ymd_Hms"); ?>" class="entryField"/> 
            </div>
            <div class="entry">
                <label class="lbl_regular" style="">
                    Date*</label>
                <input type="text" size="38" maxlength="50" name="date" id="date" value="<?= date("Y-m-d H:m:s"); ?>" class="entryField"/> 
            </div>
            <div class="entry">
                <label class="lbl_regular" style="">
                    Client*</label>
                <?= $select_client; ?> 
            </div>
            <div class="entry">
                <label class="lbl_regular" style="">
                    Active contracts*</label>
                <select size="1" id="contract_id" name="contract_id" class="entryField"> </select>
            </div>
            <div class="entry">
                <label class="lbl_regular" style="">
                    Training type*</label>
                <input type="text" size="38" maxlength="50" id="training_type" name="training_type" value="" class="entryField" readonly />
            </div>                    
            <div class="entry">
                <label class="lbl_regular" style="">
                    Trainer*</label>
                    <?= $select_trainer; ?> 
            </div>
            <div class="entry">
                <label class="lbl_regular" style="">
                    Comments</label>
                <textarea cols="35" rows="8" wrap="VIRTUAL" id="comments" name="comments" class="entryField"></textarea> 
            </div>
        </fieldset>
        <noscript><center><input type="submit" value="Log Session" name="submit" /></center></noscript>
        <div id="bt_log_session" class="bt_green_rounded submit_form_data" style="width: 200px; text-align: center;margin: 10px auto;">Log Session</div></form> 



</div>
        

<?php
##-F&R 03-##
$content    = ob_get_contents();
ob_end_clean();

##-F&R 04-##



##-F&R 05-##

?>