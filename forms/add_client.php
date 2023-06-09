<?php

/*
 * Getting page data
 */
##-F&R 01-##
$template = 'templates/tplt_main.php';

# header variables
$title          = "Create a new client";
$keywords       = "";
$description    = "Create a new client. A client must be created before you can sell him a package or log a session.";
$extra       = "";


# Main content to buffer
ob_start();

##-F&R 02-##

?>
<div class="main">
            <form action="handlers/gymHandler.php" method="POST" enctype="multipart/form-data" class="dataInput" name="AddUser" onSubmit="return confirmSubmit()">
                <div class="formTitle">Create client</div>
                <fieldset class="formFieldSet">
                    <input type='hidden' id='mandatory' name='mandatory' value='client_id;first_name;last_name;address;city;age;sex;phone;email;created_date'>
                    <input type="hidden" value="create_client" name="formPurpose" id="formPurpose" />
                    <input type="hidden" value="index.php?pge=forms/add_client" id="return_page" name="return_page" />

                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Client id*</label>
                        <input type="text" size="38" maxlength="15" id="client_id" name="client_id" value="<?= "clt_" . date("Ymd_Hms"); ?>" class="entryField" readonly /> 
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
                            Address*</label>
                        <textarea cols="28" rows="3" wrap="VIRTUAL" id="address" name="address" class="entryField"></textarea> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            City*</label>
                        <input type="text" size="38" maxlength="50" id="city" name="city" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Zip</label>
                        <input type="text" size="38" maxlength="10" id="zip" name="zip" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Age*</label>
                        <input type="text" size="38" maxlength="10" id="age" name="age" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Sex*</label>
                        <select size="1" id="sex" name="sex" class="entryField">
                            <option value="male">male</option><option value="female">female</option></select> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Phone*</label>
                        <input type="text" size="38" maxlength="20" id="phone" name="phone" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Email*</label>
                        <input type="text" size="38" id="email" name="email" value="" class="entryField"/>     
                    </div>
                    <input type="hidden" size="38" maxlength="50" id="created_date" name="created_date" value="<?= date("Y-m-d H:m:s"); ?>" class="entryField"/> 
                </fieldset>
                <noscript><center><input type="submit" value="Create new client" id="submit" name="submit" /></center></noscript>
                <div id="bt_add_client" class="bt_green_rounded submit_form_data" style="width: 200px; text-align: center;margin: 10px auto;">Add client</div>
            </form> 

        </div>

<?php
##-F&R 03-##
$content    = ob_get_contents();
ob_end_clean();

##-F&R 04-##



##-F&R 05-##

?>