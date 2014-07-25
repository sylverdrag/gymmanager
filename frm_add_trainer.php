<html>
<head>
<style type="text/css">
.lbl_regular
{
	display: inline-block;
	vertical-align:top;
	color: navy;
	min-width: 250px;
}
</style>
</head>
<body>
 <form action="handlers/adminFormHandler.php" method="POST" enctype="multipart/form-data" class="dataInput" name="Add new trainer" onSubmit="">
<div class="formTitle">Add trainer</div>
<fieldset class="formFieldSet">
<input type='hidden' name='mandatory' value='trainer_id;first_name;last_name;sex;age;phone;address;boxing_rate;pilates_rate;yoga_rate;bodyweight_rate;trx_rate;comments;creation_date'>
  <input type="hidden" value="addArticle" name="formPurpose" />
  
<div class="entry">
<label class="lbl_regular" style="">
Trainer id*</label>
<input type="text" size="38" maxlength="15" name="trainer_id" value="<?= date("Ymd_Hms"); ?>" class="entryField" readonly /> 
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
Sex*</label>
<select size="1" name="sex">
<option value="male">male</option><option value="female">female</option></select> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Age*</label>
<input type="text" size="38" maxlength="50" name="age" value="" class="entryField"/> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Phone*</label>
<input type="text" size="38" maxlength="20" name="phone" value="" class="entryField"/> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Address*</label>
<textarea cols="35" rows="8" wrap="VIRTUAL" name="address" class="entryField"></textarea> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Boxing rate*</label>
<input type="text" size="38" maxlength="20" name="boxing_rate" value="" class="entryField"/> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Pilates rate*</label>
<input type="text" size="38" maxlength="20" name="pilates_rate" value="" class="entryField"/> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Yoga rate*</label>
<input type="text" size="38" maxlength="20" name="yoga_rate" value="" class="entryField"/> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Bodyweight rate*</label>
<input type="text" size="38" maxlength="20" name="bodyweight_rate" value="" class="entryField"/> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Trx rate*</label>
<input type="text" size="38" maxlength="20" name="trx_rate" value="" class="entryField"/> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Comments*</label>
<textarea cols="35" rows="8" wrap="VIRTUAL" name="comments" class="entryField"></textarea> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Creation date*</label>
<input type="text" size="38" maxlength="50" name="creation_date" value="" class="entryField"/> 
</div>
</fieldset><center><input type="submit" value="Submit" name="submit" /></center>
</form> 




 </body>
 </html>