<?php

session_start(); 
//##-## Check that the query comes from a signed in user with admin rights


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

# Load class
require_once ("../classes/gym_manager_class.php");
require_once ("../classes/gym_edits_class.php");
$gym_edits = new gym_edits_class($dbh);


##-## Start processing the user input based on the source form purpose ##-## 
$action=$_POST["action"];
switch ($action){
    case "update":
        if ($_POST["db_table_name"] == "sylver_gymmngr.clients")
        {
            if ($gym_edits->update_client($_POST)){ echo 'ok'; } else { echo 'error'; }
        }
        else if ($_POST["db_table_name"] == "sylver_gymmngr.trainers")
        {
            if ($gym_edits->update_trainer($_POST)){ echo 'ok'; } else { echo 'error'; }
        }
        else if ($_POST["db_table_name"] == "sylver_gymmngr.training_packages")
        {
            if ($gym_edits->update_package($_POST)){ echo 'ok'; } else { echo 'error'; }
        }
        break;


}