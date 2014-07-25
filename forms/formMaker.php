<?php 
include_once('form_class.php');

if (isset($_SESSION[pageVars]))
{
  //load the page variables in $_GET[]
  $varsNcontents = explode("&", $_SESSION[pageVars]);
  foreach($varsNcontents as $key => $value)
  {
    $var_content = explode("=", $value);
    $_GET[$var_content[0]] = $var_content[1];
  }
  unset($_SESSION[pageVars]); //remove page variables to avoid mix ups
}

if ($_GET[action] == "edit" && is_array($_SESSION[userR]))
{
  $existingData = $_SESSION[userR];  
  unset($_SESSION[userR]);
  $formName = "editUser";
  $options[formTitle] = "Edit User";
  $formPurpose = "<input type=\"hidden\" value=\"editUser\" name=\"formPurpose\" />";
  $ignoredFields = array("addedBy", "creationDate");
  $options[hidden][ID] = $existingData[ID];
}
else
{
  $existingData = $_SESSION[postValues];
  $formName = "Add new trainer";
  $options[formTitle] = "Add User";
  $formPurpose = "<input type=\"hidden\" value=\"addArticle\" name=\"formPurpose\" />";
  $ignoredFields = array("art_ID", "user_ID");
}

//print_r($existingData);

$db_name = "sylver_gymmngr";
$tbl = "trainers";


$options[error] = $_SESSION[formErrors];
$options[errMsg] = $_SESSION[formErrors][errMsg];

$formAtts = "action=\"handlers/adminFormHandler.php\" method=\"POST\" enctype=\"multipart/form-data\" class=\"dataInput\" name=\"$formName\" onSubmit=\"return confirmSubmit()\"";


$specialFields[] = array("where"=>"before","openField"=>"", "lbl"=>"", "field"=>"$formPurpose", "closeField"=>"");
$error = '$options[error][$fieldName]';				  



@include_once('/home/sylver/includes/sylverp.php');
$dbLink=@mysqli_connect($hostname, $user, $password);

$form = new form($dbLink, $cryptKey, $formAtts);
$form->form_db_table($db_name, $tbl, $ignoredFields, $specialFields, $existingData, $options); 
?> 




<?php 
// to avoid these things from being carried over to other forms
  unset($_SESSION[postValues]); 
  unset($_SESSION[formErrors]); 
 ?> 