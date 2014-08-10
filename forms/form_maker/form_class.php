<?php 
/*
*
* This class contain all the functions needed to create forms
*
*/

class form
{
  	public  $formTag    = "";
    private $connect    = "";
    private $cryptKey   = "";

    function __construct($dbLink, $cryptKey, $formAtt)
	{
		$this->connect      = $dbLink;
		$this->cryptKey     = $cryptKey;
        //$this->setUser_ID($_SESSION[userID]);

		$this->formTag = "<form $formAtt>"; 
	}

    /**
     * Gets all the fields and field types from a database table 
     * @param <type> $db_name
     * @param <type> $tbl
     * @param <type> $ignoredFields
     * @return <mixed> $formItems or false
     */
    public function getDatabaseFields($db_name, $tbl, $ignoredFields)
    {
        $db = mysqli_select_db($this->connect, $db_name);
	  	$sql = "DESCRIBE $tbl";
	  	$result = mysqli_query($this->connect, $sql);
        if ($result !== false)
        {
            $i = 0;
            while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
            {
                if (!in_array($row[Field], $ignoredFields))
                {
                    $formItems[$i][lbl] = $row[Field];
                    $formItems[$i][type] = $row[Type];
                    $formItems[$i][nul] = $row["Null"];
                }
                $i++;
            }
            return $formItems;
        }
        else
        {
            return false;
        }
    }

    /**
     * This function get the details of a table and
	 * create a form to insert values in that table
	 * $ignoredFields is an array of fields which should not be in the form
	 * $specialFields contain complete fields ready to output, (useful if you need fields not in the table)
	 * $existingData are data that should be displayed in the fields
	 * $options[formTitle] displays a title above the form in a separe <div>
	 * $options[errors][fieldName] display the field's label in bold red letters
	 * $options[hidden][fieldName] set field as hidden and the value is $options[hidden][fieldName][value]
     * @param <string> $db_name
     * @param <string> $tbl
     * @param <array> $ignoredFields
     * @param <array> $specialFields
     * @param <array> $existingData
     * @param <array> $options
     */
	function form_db_table($db_name, $tbl, $ignoredFields, $specialFields, $existingData, $options)
	{
        # Get all the database fields that must be filled out.
        $formItems = $this->getDatabaseFields($db_name, $tbl, $ignoredFields);
	  			
		# Generate the form fields and load them in variables
		foreach ($formItems as $key=>$value)
		{
		   # $fieldName is the actual field name in the database.
		   # $fieldLbl is the name displayed in the form before the field
		   $fieldName = $value[lbl];
		   $pattern = array('/([a-z])([A-Z])/','/[_-]/');
		   $replacement = array('$1 $2', ' ');
		   $fieldLbl = ucfirst(strtolower(preg_replace($pattern, $replacement, $fieldName)));

		   # if $fieldName is in $options[hidden], an hidden input is created
		   if (is_array($options[hidden]))
		   {
              if (array_key_exists($fieldName, $options[hidden]))
              {
                $val = $options[hidden][$fieldName];
                $formEntries .= "<input type='hidden' name='$fieldName' value='$val' />";
                continue;
              }
		   }
		   if($value[nul] == "YES")
		   {
		        $mandatory = "";
		   }
		   else
		   {
		        $mandatory = "*";
		        $mandatoryFields .= $value[lbl] . ";";
		   }
		   // from type, decide which form item to use: varchar = <input> ...
		   if (stripos($value[type],"varchar") !== false)
		   {
		        $varcharLimit = substr($value[type], 8, -1);
		        if ($varcharLimit < 71)
		        {
                    $inputItem = "<input type=\"text\" size=\"38\" maxlength=\"$varcharLimit\"".
                    " name=\"$fieldName\" value=\"$existingData[$fieldName]\" class=\"entryField\"/>";
                }
                else
                {
                    $inputItem = "<textarea cols=\"35\" rows=\"3\" wrap=\"VIRTUAL\"" .
                    " name=\"$fieldName\" class=\"entryField\">$existingData[$fieldName]</textarea>";
                }
           }
		   else if (stripos($value[type],"int") !== false)
		   {
		        $inputItem = "<input type=\"text\" size=\"38\" maxlength=\"$varcharLimit\"".
                    " name=\"$fieldName\" value=\"$existingData[$fieldName]\" class=\"entryField\"/>";
           }
		   else if (stripos($value[type],"text") !== false)
		   {
               $inputItem = "<textarea cols=\"35\" rows=\"8\" wrap=\"VIRTUAL\"" .
               " name=\"$fieldName\" class=\"entryField\">$existingData[$fieldName]</textarea>";
		   }
		   else if (stripos($value[type],"date") !== false)
		   {
			   $inputItem = "<input type=\"text\" size=\"38\" maxlength=\"50\"".
			   " name=\"$fieldName\" value=\"$existingData[$fieldName]\" class=\"entryField\"/>";
		   }
		   else if (stripos($value[type],"enum") !== false)
		   {
		/*  
			echo '<pre>';
			print_r($value);
			echo '</pre>';
		*/
               $inputItem = "<select size=\"1\" name=\"$fieldName\">\r\n";
               if (isset($existingData[$fieldName]))
               {
                    $inputItem .= "<option value=\"$existingData[$fieldName]\">$existingData[$fieldName]</option>";
               }
               $enumVal = explode(",",substr($value[type], 6, -1));
               foreach($enumVal as $key => $value)
               {
                    $val= trim(str_replace("'", "", $value));
                    $inputItem .= "<option value=\"$val\">$val</option>";
			   }
               $inputItem .= "</select>";
            }
		  ## !!! COMPLETE THE LIST OF TYPES !!!
		  
		  $error = $options[error][$fieldName];
		  
		  $formEntries .= "<div class=\"entry\">\r\n";
		  $formEntries .= "<label class=\"lbl_regular\" style=\"$error\">\r\n";
		  $formEntries .= "$fieldLbl$mandatory</label>\r\n$inputItem \r\n";
		  $formEntries .= "</div>\r\n";
  
		}
		
		# Sends the list of mandatory fields
		if ($mandatoryFields != "")
		{
		    $mandatoryFields = substr($mandatoryFields, 0, -1);
		  	//- Explode to determine which fields can't be blank -\\
		  	$mandatoryFields = "<input type='hidden' name='mandatory' value='$mandatoryFields'>\r\n";
		}
		
		# Extract special fields - fields and labels ready for output
		if (is_array($specialFields))
		{
		foreach ($specialFields as $key=>$value)		
		{
		  if($value[where]="before")
		  {
		  	$specFieldsBefore .= "$value[openField] $value[lbl] $value[field]\r\n $value[closeField] \r\n";
		  }
		  else
		  {
		   	$specFieldsAfter .= "$value[openField] $value[lbl] $value[field]\r\n $value[closeField] \r\n"; 
		  }
		}
		}
		# Error message
		if (isset($options[errMsg]))
		{
		  echo "<div class=\"errorMsg\">$options[errMsg]</div>";
		}
		# Output the top of the form
		echo $this->formTag;
		if (isset($options[formTitle]))
		{
		echo "\r\n<div class=\"formTitle\">$options[formTitle]</div>\r\n";
		}
		echo "<fieldset class=\"formFieldSet\">\r\n";

		#output the the actual fields
		echo $mandatoryFields;
		echo $specFieldsBefore;
		echo $formEntries;
		echo $specFieldsAfter;
		
		# Close fieldset, add a validate button and close the form
		echo "</fieldset>";
		echo "<center><input type=\"submit\" value=\"Submit\" name=\"submit\" /></center>\n";
		echo "</form>";
		
	}
	
	function queryForm()
	{
	  $selectAtt = "name= \"srcLang\"";
	  $selected = "<option value=\"EN-US\" selected>English, USA</option><option>---</option>";
	  $form = $this->formTag . "\r\n";
	  $form .= "<table align=\"CENTER\" width=\"90%\" cellpadding=\"1\" border=\"1\" name=\"askQuestion\">
<tr>
         <td class=\"formLabel\">Language: </td>
         <td>" . $this->langCombo($selectAtt, $selected) . "</td>
</tr>
<tr>
         <td></td>
         <td></td>
</tr>
<tr>
         <td></td>
         <td></td>
</tr>
<tr>
         <td></td>
         <td></td>
</tr>
<tr>
         <td></td>
         <td></td>
</tr>
<tr>
         <td></td>
         <td></td>
</tr>
</table>";
	  // . "Language: " . $this->langCombo($selectAtt, $selected);
	  $form .= "</form>";
	  return $form;
	}
	/**
     * Takes the attributes for the drop down list and the selected item
     * and returns the code for a language combo box with the
     * specified selected item.
     * @param <type> $selectAtt
     * @param <type> $selected
     * @return <string> $combo
     */
	function langCombo($selectAtt, $selected)
	{
	  // $selectAtt contains full attributes which are added to the 
	  // "select" tag, like name=""
		$combo = "
	<select size=\"1\" $selectAtt>
		$selected
		<option value=\"AF-01\">Afrikaans</option>
		<option value=\"SQ-01\">Albanian</option>
		<option value=\"AR-01\">Arabic</option>
		<option value=\"EU-01\">Basque</option>
		<option value=\"BG-01\">Bulgarian</option>
		<option value=\"BE-01\">Byelorussian</option>
		<option value=\"CA-01\">Catalan</option>
		<option value=\"ZH-CN\">Chinese, PRC</option>
		<option value=\"ZH-SG\">Chinese, Singapore</option>
		<option value=\"ZH-TW\">Chinese, Taiwan</option>
		<option value=\"HR-01\">Croatian</option>
		<option value=\"CS-01\">Czech</option>
		<option value=\"DA-01\">Danish</option>
		<option value=\"NL-BE\">Dutch, Belgium</option>
		<option value=\"NL-NL\">Dutch, Netherlands</option>
		<option value=\"EN-CA\">English, Canada</option>
		<option value=\"EN-GB\">English, UK</option>
		<option value=\"EN-US\">English, USA</option>
		<option value=\"ET-01\">Estonian</option>
		<option value=\"FA-01\">Farsi</option>
		<option value=\"FI-01\">Finnish</option>
		<option value=\"FR-CA\">French, Canada</option>
		<option value=\"FR-FR\">French, France</option>
		<option value=\"DE-AT\">German, Austria</option>
		<option value=\"DE-DE\">German, Germany</option>
		<option value=\"DE-CH\">German, Switzerland</option>
		<option value=\"EL-01\">Greek</option>
		<option value=\"IW-01\">Hebrew</option>
		<option value=\"HU-01\">Hungarian</option>
		<option value=\"IS-01\">Icelandic</option>
		<option value=\"IN-01\">Indonesian</option>
		<option value=\"IT-IT\">Italian, Italy</option>
		<option value=\"IT-CH\">Italian, Switzerland</option>
		<option value=\"JA-01\">Japanese</option>
		<option value=\"KO-01\">Korean</option>
		<option value=\"LV-01\">Latvian</option>
		<option value=\"LT-01\">Lithuanian</option>
		<option value=\"MK-01\">Macedonian</option>
		<option value=\"MT-01\">Maltese</option>
		<option value=\"NO-NY\">Norwegian</option>
		<option value=\"PL-01\">Polish</option>
		<option value=\"PT-BR\">Portuguese, Brazil</option>
		<option value=\"PT-PT\">Portuguese, Portugal</option>
		<option value=\"RO-01\">Romanian</option>
		<option value=\"RU-01\">Russian</option>
		<option value=\"SH-01\">Serbo-Croatian</option>
		<option value=\"SK-01\">Slovak</option>
		<option value=\"SL-01\">Slovenian</option>
		<option value=\"SO-01\">Sorbian</option>
		<option value=\"ES-AR\">Spanish, Argentina</option>
		<option value=\"ES-CL\">Spanish, Chile</option>
		<option value=\"ES-ES\">Spanish, Spain</option>
		<option value=\"SV-SE\">Swedish</option>
		<option value=\"TR-01\">Turkish</option>
		<option value=\"UK-01\">Ukrainian</option>
		<option value=\"VI-01\">Vietnamese</option>
	</select>";
	return $combo;
	}
	
}

/* Previous testing code
$db_name = "sylver_TUboards";
$tbl = "people";
$ignoredFields = array("ID", "addedBy", "creationDate");
$existingData = array("userName"=>"Sylver");
$options[formTitle] = "Automatically generated form";
$options[hidden][userName] = "test";
$formAtts = "action=\"handlers/translatorFormHandler.php\" method=\"POST\" enctype=\"multipart/form-data\" class=\"dataInput\" name=\"$tbl\" onSubmit=\"return confirmSubmit()\"";

$form = new form($formAtts);
$form->form_db_table($db_name, $tbl, $ignoredFields, $specialFields, $existingData, $options);
/*
$selectAtt = "name= \"test\"";
$selected = "<option value=\"UK-01\">Ukrainian</option><option>---</option>";
echo $form->formTag;
*/

 ?> 