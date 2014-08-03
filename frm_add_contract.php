<form action="handlers/gymHandler.php" method="POST" enctype="multipart/form-data" class="dataInput" name="AddUser" onSubmit="return confirmSubmit()">
<div class="formTitle">Create new contract</div>
<fieldset class="formFieldSet">
<input type='hidden' name='mandatory' value='contract_id;client_id;creation_date;training_type;package;nb_session_bought;rate_per_session;sold_by;start_date;expire_date;remaining_sessions;trainer_rate_modifier;comments'>
  <input type="hidden" value="addArticle" name="formPurpose" />
  
<div class="entry">
<label class="lbl_regular" style="">
Contract id*</label>
<input type="text" size="38" maxlength="15" name="contract_id" value="" class="entryField"/> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Client id*</label>
<input type="text" size="38" maxlength="15" name="client_id" value="" class="entryField"/> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Creation date*</label>
<input type="text" size="38" maxlength="50" name="creation_date" value="" class="entryField"/> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Training type*</label>
<input type="text" size="38" maxlength="50" name="training_type" value="" class="entryField"/> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Package*</label>
<textarea cols="35" rows="3" wrap="VIRTUAL" name="package" class="entryField"></textarea> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Nb session bought*</label>
<textarea cols="35" rows="3" wrap="VIRTUAL" name="package" class="entryField"></textarea> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Rate per session*</label>
<textarea cols="35" rows="3" wrap="VIRTUAL" name="package" class="entryField"></textarea> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Sold by*</label>
<input type="text" size="38" maxlength="20" name="sold_by" value="" class="entryField"/> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Start date*</label>
<input type="text" size="38" maxlength="50" name="start_date" value="" class="entryField"/> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Expire date*</label>
<input type="text" size="38" maxlength="50" name="expire_date" value="" class="entryField"/> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Remaining sessions*</label>
<input type="text" size="38" maxlength="50" name="expire_date" value="" class="entryField"/> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Trainer rate modifier*</label>
<input type="text" size="38" maxlength="50" name="expire_date" value="" class="entryField"/> 
</div>
<div class="entry">
<label class="lbl_regular" style="">
Comments*</label>
<textarea cols="35" rows="8" wrap="VIRTUAL" name="comments" class="entryField"></textarea> 
</div>
</fieldset><center><input type="submit" value="Submit" name="submit" /></center></form> 
