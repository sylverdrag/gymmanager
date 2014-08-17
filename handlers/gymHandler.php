<?php 
session_start(); 
//##-## Check that the query comes from a signed in user with admin rights
//if($_SESSION["access_admin"]!= "yes"){
//	echo "Sorry, you are not authorized to perform this operation";
////	print_r($_SESSION);
//	die;  
//}
//##-## From here on, the user has been verified
////Check that $_POST["formPurpose"] is set. If not, send the user back 
//if (!isset($_POST["formPurpose"])){        
//  $_SESSION["postValues"]= $_POST;
//  $_SESSION["formErrors"]["nb_error"] = "1 fatal";
//  $referPage=$_SERVER[""HTTP_REFERER""];
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

require_once ("../classes/gym_manager_class.php");

$gymmngr = new gym_manager_class($dbh);

$nb_error = 0; // Error counter

#1# Explode "mandatory" and check all fields in "mandatory" are not blank
if ($_POST["mandatory"] != "") // making sure there are mandatory fields - not true of ajax calls
{
    $mandat = explode(";", $_POST["mandatory"]);

    foreach ($mandat as $key=>$value)
    {
      if($_POST[$value] == "")
      {
        $nb_error++;
        $_SESSION["formErrors"][$value] = "color:red;font-weight: bold;";
        $_SESSION["formErrors"]["errMsg"] .= "$value can not be empty.<br />\r\n";
      }
    }

    #1b# Check if there is any error. If yes, load all $_POST values in $_SESSION and send the user back to the form
    if ($nb_error!=0){
      $_SESSION["postValues"]= $_POST;
      $_SESSION["formErrors"]["errMsg"] = "Sorry, $nb_error errors found:<br />\r\n" . $_SESSION["formErrors"]["errMsg"];
      $referPage=$_SERVER["HTTP_REFERER"];
      header("location: $referPage");  
      die;
    }
}

##-## Start processing the user input based on the source form purpose ##-## 
$formPurpose=$_POST["formPurpose"];
switch ($formPurpose){
    case "create_client":   ###### Creates a new client ########
        $gymmngr->set_client_id($_POST["client_id"]);
        $gymmngr->client_first_name = $_POST["first_name"];
        $gymmngr->client_last_name = $_POST["last_name"];
        $gymmngr->client_address = $_POST["address"];
        $gymmngr->client_city = $_POST["city"];
        $gymmngr->client_zip = $_POST["zip"];
        $gymmngr->client_age = $_POST["age"];
        $gymmngr->client_sex = $_POST["sex"];
        $gymmngr->client_phone = $_POST["phone"];
        $gymmngr->client_email = $_POST["email"];
        $gymmngr->client_create_date = $_POST["created_date"];

        if ($gymmngr->create_client()){ echo 'ok'; } else { echo 'error'; }
        
        break;
    
    case "create_trainer":   ###### Creates a new trainer ######
        $gymmngr->set_trainer_id($_POST["trainer_id"]);
        $gymmngr->trainer_first_name = $_POST["first_name"];
        $gymmngr->trainer_last_name = $_POST["last_name"];
        $gymmngr->trainer_sex = $_POST["sex"];
        $gymmngr->trainer_age = $_POST["age"];
        $gymmngr->trainer_phone = $_POST["phone"];
        $gymmngr->trainer_address = $_POST["address"];
        $gymmngr->trainer_is_active = $_POST["active"];
        $gymmngr->trainer_rate_boxing = $_POST["boxing_rate"];
        $gymmngr->trainer_rate_pilates = $_POST["pilates_rate"];
        $gymmngr->trainer_rate_yoga = $_POST["yoga_rate"];
        $gymmngr->trainer_rate_bodyweight = $_POST["bodyweight_rate"];
        $gymmngr->trainer_rate_trx = $_POST["trx_rate"];
        $gymmngr->set_comments($_POST["comments"]);
        $gymmngr->trainer_create_date = $_POST["created_date"];


        if ($gymmngr->create_trainer()){ echo 'ok'; } else { echo 'error: Failled to create trainer'; }
        
        break;

    case "create_package":   ###### Creates a new training package ######
        $gymmngr->package_id = $_POST["package_id"];
        $gymmngr->package_name = $_POST["name"];
        $gymmngr->set_session_type($_POST["type"]);
        $gymmngr->nb_sessions = $_POST["nb_sessions"];
        $gymmngr->price_per_session = $_POST["price_per_session"];
        $gymmngr->package_active = $_POST["active"];
        $gymmngr->package_discount = $_POST["discount"];
        $gymmngr->package_comments = $_POST["comments"];

        if ($gymmngr->create_package()){ echo 'ok'; } else { echo 'error: Failled to create package'; }
        
        break;
       
        
    case "create_contract":   ###### Creates a new contract ######
        $gymmngr->set_contract_id($_POST["contract_id"]);
        $gymmngr->set_client_id($_POST["client_id"]);
        $gymmngr->contract_creation_date = $_POST["creation_date"];
        $gymmngr->branch = $_POST["branch"];
        $gymmngr->set_session_type($_POST["training_type"]);
        $gymmngr->package_id = $_POST["package_id"];
        $gymmngr->nb_sessions = $_POST["nb_sessions"];
        $gymmngr->price_per_session = $_POST["price_per_session"];
        $gymmngr->start_date = $_POST["start_date"];
        $gymmngr->expire_date = $_POST["expire_date"];
        $gymmngr->remaining_sessions = $_POST["remaining_sessions"];
        $gymmngr->trainer_rate_modifier = $_POST["trainer_rate_modifier"];
        $gymmngr->set_comments($_POST["comments"]);


        if ($gymmngr->create_contract()){ echo 'ok'; } else { echo 'error: Failled to create contract'; }
        
        break;
        
    case "log_session":   ###### After every training session, the session is logged and the contract remaining sessions is updated ######
        $gymmngr->set_client_id($_POST["client_id"]);
        $gymmngr->set_trainer_id($_POST["trainer_id"]);
        $gymmngr->set_contract_id($_POST["contract_id"]);
        $gymmngr->set_session_id($_POST["session_id"]);
        $gymmngr->set_session_date($_POST["date"]);
        $gymmngr->set_session_type($_POST["training_type"]);
        $gymmngr->set_comments($_POST["comments"]);

        if ($gymmngr->log_session()){ echo 'ok'; } else { echo 'error'; }
        
        break;
        
    case "get_client_active_contracts":
        $contracts = $gymmngr->get_active_contracts_for_client($_POST["client_id"]);
        $contracts_json = json_encode($contracts);
        echo $contracts_json;
        break;
    
}

