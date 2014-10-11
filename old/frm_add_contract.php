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
$select_client = "<select size=\"1\" name=\"client_id\" class=\"entryField\">" . $select_client . "</select>";

$packages = $gymmngr->get_active_packages_list();
$select_packages = "<option value=''>Select package</option>";
$package_data = htmlentities(json_encode($packages));
for ($i = 0; $i < count($packages); $i++)
{
    $select_packages .= "<option value=\"" . $packages[$i]["package_id"] . "\">" . $packages[$i]["name"] . "</option>";
}
$select_packages = "<select size=\"1\" name=\"package_id\" id=\"package_id\" class=\"entryField\">" . $select_packages . "</select>";




?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/basic.css?<?php echo date("c"); ?>" media="screen" />
        <link href="css/jquery-ui-1.10.3.custom.min.css" rel="stylesheet">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    </head>
    <body> 
        <div class="main">
            <form action="handlers/gymHandler.php" method="POST" enctype="multipart/form-data" class="dataInput" name="create_contract" onSubmit="return confirmSubmit()">
                <div class="formTitle">Create new contract</div>
                <fieldset class="formFieldSet">
                    <input type='hidden' name='mandatory' value='contract_id;client_id;creation_date;training_type;package_id;nb_sessions;price_per_session;start_date;expire_date;remaining_sessions'>
                    <input type="hidden" value="create_contract" name="formPurpose" />

                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Contract id*</label>
                        <input type="text" size="38" maxlength="15" name="contract_id" value="<?= "ctt_" . date("Ymd_Hms"); ?>" class="entryField" readonly /> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Branch*</label>
                        <select size="1" name="branch" class="entryField">
                            <option value="Punna">Punna</option>
                            <option value="Meechoke">Meechoke</option>
                        </select> 
                    </div>
                    
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Client*</label>
                        <?= $select_client; ?> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Package*</label>
                        <?= $select_packages; ?>
                    </div>
                    <div id="package_details" style="display: none;">
                        <div class="entry">
                            <label class="lbl_regular" style="">
                                Training type*</label>
                            <input type="text" size="38" maxlength="50" id="training_type" name="training_type" value="" class="entryField" readonly />
                        </div>
                        <div class="entry">
                            <label class="lbl_regular" style="">
                                Number of sessions*</label>
                            <input type="text" size="38" maxlength="50" id="nb_sessions" name="nb_sessions" value="" class="entryField" readonly/>
                        </div>
                        <div class="entry">
                            <label class="lbl_regular" style="">
                                Price per session*</label>
                            <input type="text" size="38" maxlength="50" id="price_per_session" name="price_per_session" value="" class="entryField" readonly/>
                        </div>
                        <div class="entry">
                            <label class="lbl_regular" style="">
                                Total*</label>
                            <input type="text" size="38" maxlength="50" id="total" name="total" value="" class="entryField" readonly/>
                        </div>
                        <div class="entry" style="display: none;">
                            <label class="lbl_regular" style="">
                            Remaining sessions*</label>
                            <input type="text" size="38" maxlength="50" id="remaining_sessions" name="remaining_sessions" value="" class="entryField"/> 
                        </div>
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Start date*</label>
                        <input type="text" size="38" maxlength="50" id="start_date" name="start_date" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Expire date*</label>
                        <input type="text" size="38" maxlength="50" id="expire_date" name="expire_date" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Trainer rate modifier</label>
                        <input type="text" size="38" maxlength="50" name="trainer_rate_modifier" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Comments</label>
                        <textarea cols="35" rows="8" wrap="VIRTUAL" name="comments" class="entryField"></textarea> 
                    </div>
                    <input type="hidden" size="38" maxlength="50" name="creation_date" value="<?= date("Y-m-d H:m:s"); ?>" class="entryField"/> 
                </fieldset><center><input type="submit" value="Submit" name="submit" /></center></form> 

        </div>
        
    <!-- Game data in JSON format for use with javascript -->
    <div id="package_data" style="display: none;"><?= $package_data; ?></div>
    
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script>!window.jQuery && document.write('<script src="<?= $pathRoot ?>js/jquery-1.11.1.min.js"><\/script>')</script>
    <script src="js/jquery-ui-1.10.4.custom.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="js/gymmngr.js" type="text/javascript" charset="utf-8"></script>

    </body>
</html>