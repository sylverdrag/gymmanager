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
require("../classes/gym_reports_class.php");

$gym_reports = new gym_reports_class($dbh);


##-## Start processing the user input based on the source form purpose ##-## 
$action=$_POST["action"];
switch ($action){
    case "display_client_summary":  
        $client_id = $_POST["client_id"];
        $client_name = $_POST["client_name"];
        $limit = "";
        $ignore_cols[] = "Name";
        $ignore_cols[] = "Contract ID";
        $active_contracts = $gym_reports->get_active_contracts_for_client($client_id, $limit);
        $all_contracts = $gym_reports->get_all_contracts_for_client($client_id, $limit);
        $all_sessions = $gym_reports->get_all_sessions_for_client($client_id, $limit);
        $client_details = $gym_reports->get_client_details($client_id);
        $report  =  "<div>Name: <b>" .
                    $client_details["first_name"] . " " . 
                    $client_details["last_name"] . "</b><br />" .
                    "Address:<br /><b>" .
                    "<div id='address'>" .
                    $client_details["address"] ."<br />" .
                    $client_details["zip"] ." ".$client_details["city"] . "</div>" .
                    "age: <b>" . $client_details['age'] . "</b> / " .
                    "sex: <b>" . $client_details['sex'] . "</b><br />" .
                    "phone: <b>" . $client_details['phone'] . "</b><br />" .
                    "email: <b>" . $client_details['email'] . "</b><br />" .
                    "Client since: <b>" . $client_details['created_date'] .
                    "</b></div>"
                 ;
        $report .= $gym_reports->results_to_table($active_contracts, "Contracts", "Active contracts for ". $client_name, $ignore_cols);
        $report .= $gym_reports->results_to_table($all_contracts, "Contracts", "All contracts for ". $client_name, $ignore_cols);
        $report .= $gym_reports->results_to_table($all_sessions, "Sessions", "All sessions for ". $client_name, $ignore_cols);
        echo $report;
        
        break;

    case "display_trainer_summary":  
        $trainer_id = $_POST["trainer_id"];
        $trainer_name = $_POST["trainer_name"];
        $from   = $_POST["from"];
        $to     = $_POST["to"];
        $limit = "";
        
        $ignore_cols[] = "Trainer";
        $trainer_activity = $gym_reports->trainer_activity_in_period($trainer_id, $from, $to, $limit);
        //var_dump($trainer_activity);
        
        $nb_sessions_done = count($trainer_activity);
        $session_breakdown = array();
        foreach ($trainer_activity as $key => $value)
        {
            switch ($value["Training type"])
            {
                case "TRX":
                    $session_breakdown["TRX"] +=1; 
                    break;
                case "Yoga":
                    $session_breakdown["Yoga"] +=1;
                    break;
                case "Pilates":
                    $session_breakdown["Pilates"] +=1;
                    break;
                case "Boxing":
                    $session_breakdown["Boxing"] +=1;
                    break;
                case "Bodyweight":
                    $session_breakdown["Bodyweight"] +=1;
                    break;
            }
        }
        foreach ($session_breakdown as $key => $value)
        {
            $session_breakdown_str .= $key  . ": " . $value . "; ";
        }
        
        $trainer_details = $gym_reports->get_trainer_details($trainer_id);
        $report  =  "<div>Name: <b>" .
                    $trainer_details["first_name"] . " " . 
                    $trainer_details["last_name"] . "</b><br />" .
                    "Address:<br /><b>" .
                    "<div id='address'>" .
                    $trainer_details["address"] ."<br />" . "</b></div>" .
                    "age: <b>" . $trainer_details['age'] . "</b> / " .
                    "sex: <b>" . $trainer_details['sex'] . "</b><br />" .
                    "phone: <b>" . $trainer_details['phone'] . "</b><br />" .
                    "</div>"
                 ;
        $report .= "<h3># sessions for the period: <b>" . $nb_sessions_done ."</b></h3> (<i>"  . $session_breakdown_str . "</i>)</br>";
        $report .= "<h3>Total fees for the period: <b>".$gym_reports->calculate_trainer_fees($trainer_id, $from, $to) . "</b></h3>";
        $report .= $gym_reports->results_to_table($trainer_activity, "Trainer_Activity", "Trainer activity from " . $from . " to ". $to . " for " . $trainer_name, $ignore_cols);
        $report .= "";
        echo $report;
        
        break;

}