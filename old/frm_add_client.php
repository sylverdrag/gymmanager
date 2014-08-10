<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/basic.css?<?php echo date("c"); ?>" media="screen" />
    </head>
    <body>
        <div class="main">
            <form action="handlers/gymHandler.php" method="POST" enctype="multipart/form-data" class="dataInput" name="AddUser" onSubmit="return confirmSubmit()">
                <div class="formTitle">Create client</div>
                <fieldset class="formFieldSet">
                    <input type='hidden' name='mandatory' value='client_id;first_name;last_name;address;city;age;sex;phone;email;created_date'>
                    <input type="hidden" value="create_client" name="formPurpose" />

                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Client id*</label>
                        <input type="text" size="38" maxlength="15" name="client_id" value="<?= "clt_" . date("Ymd_Hms"); ?>" class="entryField" readonly /> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            First name*</label>
                        <input type="text" size="38" maxlength="50" name="first_name" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Last name*</label>
                        <input type="text" size="38" maxlength="50" name="last_name" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Address*</label>
                        <textarea cols="28" rows="3" wrap="VIRTUAL" name="address" class="entryField"></textarea> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            City*</label>
                        <input type="text" size="38" maxlength="50" name="city" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Zip</label>
                        <input type="text" size="38" maxlength="10" name="zip" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Age*</label>
                        <input type="text" size="38" maxlength="10" name="age" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Sex*</label>
                        <select size="1" name="sex" class="entryField">
                            <option value="male">male</option><option value="female">female</option></select> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Phone*</label>
                        <input type="text" size="38" maxlength="20" name="phone" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Email*</label>
                        <input type="text" size="38" name="email" value="" class="entryField"/>     
                    </div>
                    <input type="hidden" size="38" maxlength="50" name="created_date" value="<?= date("Y-m-d H:m:s"); ?>" class="entryField"/> 
                </fieldset>
                <center><input type="submit" value="Create new client" name="submit" /></center>
            </form> 

        </div>
    </body>
</html>