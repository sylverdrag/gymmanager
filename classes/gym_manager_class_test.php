<h1>Session class tests:</h1>
<?php
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

# Load class
require("./gym_manager_class.php");


$gymmngr = new gym_manager_class($dbh);

## test 1: Session ID exists
echo '<b># test 1 # $gymmngr->exists_session_id()</b>';
$id = "ses_20140723_111111"; // This ID is in the database
if ($gymmngr->exists_session_id($id))
{
    $bad_id = "ses_20140723_000000"; // This ID is not in the DB
    if (!$gymmngr->exists_session_id($bad_id))
    {
	echo ' is working properly.<br>';
    }
    else
    {
       echo ' is broken. ('. $bad_id .')<br>';
    }
}
else
{
	echo ' is broken. ('. $id .')<br>';
}


## test 2: Client ID exists
echo '<b># test 2 # $gymmngr->exists_client_id() </b>';
$id = "clt_20140723_111111"; // This ID is in the database for testing purposes
if ($gymmngr->exists_client_id($id))
{
    $bad_id = "clt_20140723_000000"; // This ID is not in the DB
    if (!$gymmngr->exists_client_id($bad_id))
    {
	echo ' is working properly.<br>';
    }
    else
    {
       echo ' is broken. ('. $bad_id .')<br>';
    }
}
else
{
	echo ' is broken. ('. $id .')<br>';
}

## test 3: Contract ID exists
echo '<b># test 3 # $gymmngr->exists_contract_id()</b>';
$id = "ctt_20140723_111111"; // This ID is in the database for testing purposes
if ($gymmngr->exists_contract_id($id))
{
    $bad_id = "ctt_20140723_000000"; // This ID is not in the DB
    if (!$gymmngr->exists_contract_id($bad_id))
    {
	echo ' is working properly.<br>';
    }
    else
    {
        echo ' is broken. ('. $bad_id .')<br>';
    }
}
else
{
	echo ' is broken. ('. $id .')<br>';
}

## test 4: trainer ID exists
echo '<b># test 4 # $gymmngr->exists_trainer_id()</b>';
$id = "tnr_20140723_111111"; // This ID is in the database for testing purposes
if ($gymmngr->exists_trainer_id($id))
{
    $bad_id = "tnr_20140723_000000"; // This ID is not in the DB
    if (!$gymmngr->exists_trainer_id($bad_id))
    {
	echo ' is working properly.<br>';
    }
    else
    {
        echo ' is broken. ('. $bad_id .')<br>';
    }
}
else
{
	echo ' is broken. ('. $id .')<br>';
}

## test 5: Get the number of remaining sessions in a contract
echo '<b># test 5 # $gymmngr->get_nb_remaining_sessions_in_contract($id)</b>';
$id = "ctt_20140723_111111"; // This ID is in the database for testing purposes
$nb_remaining_sessions = $gymmngr->get_nb_remaining_sessions_in_contract($id);
if (is_numeric($nb_remaining_sessions))
{
    echo ' is working properly. ('.$nb_remaining_sessions. ' sessions remaining)<br>';
}
else
{
	echo ' is broken. ('. $id .' - make sure there are sessions remaining on that contract)<br>';
}

## test 6: Decrement the number of remaining sessions in a contract
echo '<b># test 6 # $gymmngr->decrement_remaining_sessions_in_contract($id)</b>';
$id = "ctt_20140723_111111"; // This ID is in the database for testing purposes
$nb_remaining_sessions_after = $gymmngr->decrement_remaining_sessions_in_contract($id);
if (is_numeric($nb_remaining_sessions_after))
{
    echo ' is working properly. ('.$nb_remaining_sessions_after. ' sessions remaining)<br>';
}
else
{
	echo ' is broken. ('. $id .' - make sure there are sessions remaining on that contract)<br>';
}

## test 7: Add a session to the database
echo '<b># test 7 # $gymmngr->add_session_to_db()</b> - test disabled to avoid crashing (that function does not check if the session_id is unique) Enable only if there is a problem logging the session)<br />';
//$gymmngr->set_client_id("clt_20140723_111111");
//$gymmngr->set_trainer_id("tnr_20140723_111111");
//$gymmngr->set_contract_id("ctt_20140723_111111");
//$gymmngr->set_session_id("ses_20140723_111112");
//$gymmngr->set_session_date(date("Y-m-d H:m:s"));
//$gymmngr->set_session_type("testing add_session");
//$gymmngr->set_comments("This is a system test.");
//if ($gymmngr->add_session_to_db())
//{
//    echo ' is working properly.<br>';
//}
//else
//{
//	echo ' is broken. (or the record already exists in the database - check that first.) <br>';
//}


## test 8: log the makafraking session
echo '<b># test 8 # $gymmngr->logSession()</b>';
$gymmngr->set_client_id("clt_20140723_111111");
$gymmngr->set_trainer_id("tnr_20140723_111111");
$gymmngr->set_contract_id("ctt_20140723_111111");
$gymmngr->set_session_id("ses_20140723_111113");
$gymmngr->set_session_date(date("Y-m-d H:m:s"));
$gymmngr->set_session_type("test: log session");
$gymmngr->set_comments("This is a system test.");


if ($gymmngr->log_session())
{
    echo ' is working properly.<br>';
}
else
{
    echo ' is broken.<br>';
}

## test 9: get the list of the clients
echo '<b># test 9 # $gymmngr->get_client_list()</b>';
$clients =  $gymmngr->get_client_list();
if (is_array($clients[0]))
{
    echo ' is working properly.<br>';
}
else
{
    echo ' is broken.<br>';
}
// var_dump($clients);

## test 10: get the list of the client's contracts
echo '<b># test 10 # $gymmngr->get_active_contracts_for_client("clt_20140723_111111")</b>';
$contracts =  $gymmngr->get_active_contracts_for_client("clt_20140723_111111");
if (is_array($contracts[0]))
{
    echo ' is working properly.<br>';
}
else
{
    echo ' is broken.<br>';
}
// var_dump($contracts);

## test 11: get the list of active trainers
echo '<b># test 11 # $gymmngr->get_trainer_list()</b>';
$trainers =  $gymmngr->get_trainer_list();
if (is_array($trainers[0]))
{
    echo ' is working properly.<br>';
}
else
{
    echo ' is broken.<br>';
}
// var_dump($trainers);

## test 12: Create a training package
echo '<b># test 12 # $gymmngr->create_package()</b>';
$gymmngr->package_id = "pkg_20140727_210718";
$gymmngr->package_name = "Test package";
$gymmngr->set_session_type("TRX");
$gymmngr->nb_sessions = 12;
$gymmngr->price_per_session = 550;
$gymmngr->package_active = "yes";
$gymmngr->package_discount = 10;
$gymmngr->package_comments = "A test package";


if ($gymmngr->create_package())
{
    echo ' is working properly.<br>';
}
else
{
    echo ' is broken.<br>';
}



?>