<?php

/*
 * Getting page data
 */
##-F&R 01-##
$template = 'templates/tplt_main.php';

# header variables
$title          = "Create a new training package";
$keywords       = "";
$description    = "All services are sold as packages. Before you can sell anything, you need to create a training package. Afterward, you can select the training package when you create a contract.";
$extra       = "";


# Main content to buffer
ob_start();

##-F&R 02-##

?>

<div class="main">
            <form action="handlers/gymHandler.php" method="POST" enctype="multipart/form-data" class="dataInput" name="Create training package" onSubmit="return confirmSubmit()">
                <div class="formTitle">Create a training package</div>
                <fieldset class="formFieldSet">
                    <input type='hidden' id='mandatory' name='mandatory' value='package_id;name;nb_sessions;price_per_session;active'>
                    <input type="hidden" value="create_package" id="formPurpose" name="formPurpose" />
                    <input type="hidden" value="index.php?pge=forms/add_training_package" id="return_page" name="return_page" />

                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Package id*</label>
                        <input type="text" size="38" maxlength="19" name="package_id" id="package_id" value="<?= "pkg_" . date("Ymd_Hms"); ?>" class="entryField" readonly /> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Name*</label>
                        <input type="text" size="38" maxlength="50" id="name" name="name" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Type*</label>
                        <select size="1" id="type" name="type" class="entryField">
                            <option value="Pilates">Pilates</option>
                            <option value="Boxing">Boxing</option>
                            <option value="Yoga">Yoga</option>
                            <option value="TRX">TRX</option>
                            <option value="Bodyweight">Bodyweight training</option>
                        </select> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Nb sessions*</label>
                        <input type="text" size="38" maxlength="50" id="nb_sessions" name="nb_sessions" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Price per session*</label>
                        <input type="text" size="38" maxlength="50" id="price_per_session" name="price_per_session" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Active*</label>
                        <select size="1" id="active" name="active" class="entryField">
                            <option value="yes">yes</option><option value="no">no</option></select> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Discount</label>
                        <input type="text" size="38" maxlength="50" id="discount" name="discount" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Comments</label>
                        <textarea cols="35" rows="8" wrap="VIRTUAL" id="comments" name="comments" class="entryField boxsizingBorder"></textarea> 
                    </div>
                </fieldset>
                <noscript><center><input type="submit" value="Submit" name="submit" id="bt_submit"/></center></noscript>
                <div id="bt_add_training_package" class="bt_green_rounded submit_form_data" style="width: 300px; text-align: center;margin: 10px auto;">Add training package</div>
            </form> 



        </div>

<?php
##-F&R 03-##
$content    = ob_get_contents();
ob_end_clean();

##-F&R 04-##



##-F&R 05-##

?>