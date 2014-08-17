<?php

/*
 * Getting page data
 */
##-F&R 01-##
$template = 'templates/tplt_main.php';

# header variables
$title          = "Create a new trainer";
$keywords       = "";
$description    = "A new trainer must be created every time a new trainer is hired.";
$extra       = "";


# Main content to buffer
ob_start();

##-F&R 02-##

?>
        <div class="main">
            <form action="handlers/gymHandler.php" method="POST" enctype="multipart/form-data" class="dataInput" name="Add new trainer" onSubmit="">
                <div class="formTitle">Add trainer</div>
                <fieldset class="formFieldSet">
                    <input type='hidden' name='mandatory' value='trainer_id;first_name;last_name;sex;age;phone;address;boxing_rate;pilates_rate;yoga_rate;bodyweight_rate;trx_rate;created_date'>
                    <input type="hidden" value="create_trainer" id="formPurpose" name="formPurpose" />
                    <input type="hidden" value="index.php?pge=forms/add_trainer" id="return_page" name="return_page" />

                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Trainer id*</label>
                        <input type="text" size="38" maxlength="15" name="trainer_id" id="trainer_id" value="<?= "tnr_" . date("Ymd_Hms"); ?>" class="entryField" readonly /> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            First name*</label>
                        <input type="text" size="38" maxlength="50" id="first_name" name="first_name" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Last name*</label>
                        <input type="text" size="38" maxlength="50" id="last_name" name="last_name" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Sex*</label>
                        <select size="1" id="sex" name="sex" class="entryField">
                            <option value="male">male</option><option value="female">female</option></select> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Age*</label>
                        <input type="text" size="38" maxlength="50" id="age" name="age" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Phone*</label>
                        <input type="text" size="38" maxlength="20" id="phone" name="phone" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Address*</label>
                        <textarea cols="35" rows="4" wrap="VIRTUAL" id="address" name="address" class="entryField"></textarea> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Is active*</label>
                        <select size="1" id="active" name="active" class="entryField">
                            <option value="yes">Yes</option><option value="no">No</option></select> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Boxing rate*</label>
                        <input type="text" size="38" maxlength="20" id="boxing_rate" name="boxing_rate" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Pilates rate*</label>
                        <input type="text" size="38" maxlength="20" id="pilates_rate" name="pilates_rate" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Yoga rate*</label>
                        <input type="text" size="38" maxlength="20" id="yoga_rate" name="yoga_rate" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Bodyweight rate*</label>
                        <input type="text" size="38" maxlength="20" id="bodyweight_rate" name="bodyweight_rate" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Trx rate*</label>
                        <input type="text" size="38" maxlength="20" id="trx_rate" name="trx_rate" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Comments</label>
                        <textarea cols="35" rows="4" wrap="VIRTUAL" id="comments" name="comments" class="entryField"></textarea> 
                    </div>
                    <input type="hidden" size="38" maxlength="50" id="created_date" name="created_date" value="<?= date("Y-m-d H:m:s"); ?>" class="entryField"/> 
                </fieldset>
                <noscript><center><input type="submit" value="Add new trainer" name="submit" /></center></noscript>
                <div id="bt_add_trainer" class="bt_green_rounded submit_form_data" style="width: 200px; text-align: center;margin: 10px auto;">Add trainer</div>
            </form> 



        </div>
<?php
##-F&R 03-##
$content    = ob_get_contents();
ob_end_clean();

##-F&R 04-##



##-F&R 05-##

?>