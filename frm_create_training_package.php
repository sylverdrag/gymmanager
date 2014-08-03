<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/basic.css?<?php echo date("c"); ?>" media="screen" />
    </head>
    <body> 
        <div class="main">
            <form action="handlers/gymHandler.php" method="POST" enctype="multipart/form-data" class="dataInput" name="Create training package" onSubmit="return confirmSubmit()">
                <div class="formTitle">Create a training package</div>
                <fieldset class="formFieldSet">
                    <input type='hidden' name='mandatory' value='package_id;name;nb_sessions;price_per_session;active'>
                    <input type="hidden" value="create_package" name="formPurpose" />

                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Package id*</label>
                        <input type="text" size="38" maxlength="19" name="package_id" id="package_id" value="<?= "pkg_" . date("Ymd_Hms"); ?>" class="entryField" readonly /> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Name*</label>
                        <input type="text" size="38" maxlength="50" name="name" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Nb sessions*</label>
                        <input type="text" size="38" maxlength="50" name="nb_sessions" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Price per session*</label>
                        <input type="text" size="38" maxlength="50" name="price_per_session" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Active*</label>
                        <select size="1" name="active" class="entryField">
                            <option value="yes">yes</option><option value="no">no</option></select> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Discount</label>
                        <input type="text" size="38" maxlength="50" name="discount" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Comments</label>
                        <textarea cols="35" rows="8" wrap="VIRTUAL" name="comments" class="entryField boxsizingBorder"></textarea> 
                    </div>
                </fieldset><center><input type="submit" value="Submit" name="submit" id="bt_submit"/></center>
            </form> 



        </div>
    </body>
</html>