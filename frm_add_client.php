<form action="handlers/adminFormHandler.php" method="POST" enctype="multipart/form-data" class="dataInput" name="AddUser" onSubmit="return confirmSubmit()">
<div class="formTitle">Create client</div>
<fieldset class="formFieldSet">
<input type='hidden' name='mandatory' value='client_id;first_name;last_name;address;city;zip;age;sex;phone;email;created_date'>
<input type="hidden" value="addArticle" name="formPurpose" />
  
<div class="entry">
<label class="lbl_regular" style="">
Client id*</label>
<input type="text" size="38" maxlength="15" name="client_id" value="" class="entryField"/> 
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
<textarea cols="35" rows="8" wrap="VIRTUAL" name="address" class="entryField"></textarea> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
City*</label>
<input type="text" size="38" maxlength="50" name="city" value="" class="entryField"/> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Zip*</label>
<input type="text" size="38" maxlength="10" name="zip" value="" class="entryField"/> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Age*</label>
<input type="text" size="38" maxlength="10" name="zip" value="" class="entryField"/> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Sex*</label>
<select size="1" name="sex">
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
<textarea cols="35" rows="3" wrap="VIRTUAL" name="email" class="entryField"></textarea> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Created date*</label>
<input type="text" size="38" maxlength="50" name="created_date" value="" class="entryField"/> 
</div>
</fieldset><center><input type="submit" value="Submit" name="submit" /></center>
</form> 
