<?php 
session_start(); 
//##-## Check that the query comes from a signed in user with admin rights
//if($_SESSION[access_admin]!= "yes"){
//	echo "Sorry, you are not authorized to perform this operation";
////	print_r($_SESSION);
//	die;  
//}
//##-## From here on, the user has been verified
////Check that $_POST[formPurpose] is set. If not, send the user back 
//if (!isset($_POST[formPurpose])){        
//  $_SESSION[postValues]= $_POST;
//  $_SESSION[formErrors][nb_error] = "1 fatal";
//  $referPage=$_SERVER["HTTP_REFERER"];
//  header("location: $referPage");
//  die;
//}

##-## Database connection to sylver_gymmngr ##-##  
include_once('/home/sylver/includes/sylverp.php');
$db_name="sylver_gymmngr";
try {
    $dbh = new PDO('mysql:host=' .$hostname .';dbname='. $db_name, $user, $password);
}
catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
##-## Connected! ##-##

$nb_error = 0; // Error counter

#1# Explode "mandatory" and check all fields in "mandatory" are not blank
$mandat = explode(";", $_POST[mandatory]);
foreach ($mandat as $key=>$value)
{
  if($_POST[$value] == "")
  {
    $nb_error++;
    $_SESSION[formErrors][$value] = "color:red;font-weight: bold;";
    $_SESSION[formErrors][errMsg] .= "$value can not be empty.<br />\r\n";
  }
}

#1b# Check if there is any error. If yes, load all $_POST values in $_SESSION and send the user back to the form
if ($nb_error!=0){
  $_SESSION[postValues]= $_POST;
  $_SESSION[formErrors][errMsg] = "Sorry, $nb_error errors found:<br />\r\n" . $_SESSION[formErrors][errMsg];
  $referPage=$_SERVER["HTTP_REFERER"];
  header("location: $referPage");  
  die;

}

##-## Start processing the user input based on the source form purpose ##-## 
$formPurpose=$_POST[formPurpose];
switch ($formPurpose){
    case "logSession":   ###### Log a training session from a client -  happens after every training session ########
        try {
        $stmt = $dbh->prepare("INSERT INTO sessions (session_id, date, client_id, contract_id, trainer_id, type, comments) " . 
                              "VALUES (:session_id, :date, :client_id, :contract_id, :trainer_id, :type, :comments)");
        $stmt->bindParam(':session_id', $session_id );
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':client_id', $client_id);
        $stmt->bindParam(':contract_id', $contract_id);
        $stmt->bindParam(':trainer_id', $trainer_id);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':comments', $comments);

        $session_id = $_POST["session_id"];
        $date = $_POST["date"];
        $client_id = $_POST["client_id"];
        $contract_id = $_POST["contract_id"];
        $trainer_id = $_POST["trainer_id"];
        $type = $_POST["type"];
        $comments = $_POST["comments"];
        $stmt->execute();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }

      break;
    
}

 ?>
