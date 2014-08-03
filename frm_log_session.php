<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/basic.css?<?php echo date("c"); ?>" media="screen" />
    </head>
    <body>
        <div class="main">
            <form action="handlers/gymHandler.php" method="POST" enctype="multipart/form-data" class="dataInput" name="AddUser" onSubmit="return confirmSubmit()">
                <div class="formTitle">Log session</div>
                <fieldset class="formFieldSet">
                    <input type='hidden' name='mandatory' value='session_id;date;client_id;contract_id;trainer_id;type'>
                    <input type="hidden" value="logSession" name="formPurpose" />

                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Session id*</label>
                        <input type="text" size="38" maxlength="15" name="session_id" id="session_id" value="<?= "ses_" . date("Ymd_Hms"); ?>" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Date*</label>
                        <input type="text" size="38" maxlength="50" name="date" value="<?= date("Y-m-d H:m:s"); ?>" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Client id*</label>
                        <input type="text" size="38" maxlength="15" name="client_id" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Contract id*</label>
                        <input type="text" size="38" maxlength="15" name="contract_id" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Trainer id*</label>
                        <input type="text" size="38" maxlength="15" name="trainer_id" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Type*</label>
                        <input type="text" size="38" maxlength="20" name="type" value="" class="entryField"/> 
                    </div>
                    <div class="entry">
                        <label class="lbl_regular" style="">
                            Comments</label>
                        <textarea cols="35" rows="8" wrap="VIRTUAL" name="comments" class="entryField"></textarea> 
                    </div>
                </fieldset><center><input type="submit" value="Log Session" name="submit" /></center></form> 



        </div>
    </body>
</html>