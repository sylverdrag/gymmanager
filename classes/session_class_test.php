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
require("./session_class.php");


$session = new session($dbh);

## test 1: Session ID exists
echo '<b># test 1 # $session->exists_session_id()</b>';
$id = "ses_20140723_111111"; // This ID is in the database
if ($session->exists_session_id($id))
{
    $bad_id = "ses_20140723_000000"; // This ID is not in the DB
    if (!$session->exists_session_id($bad_id))
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
echo '<b># test 2 # $session->exists_client_id() </b>';
$id = "clt_20140723_111111"; // This ID is in the database for testing purposes
if ($session->exists_client_id($id))
{
    $bad_id = "clt_20140723_000000"; // This ID is not in the DB
    if (!$session->exists_client_id($bad_id))
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
echo '<b># test 3 # $session->exists_contract_id()</b>';
$id = "ctt_20140723_111111"; // This ID is in the database for testing purposes
if ($session->exists_contract_id($id))
{
    $bad_id = "ctt_20140723_000000"; // This ID is not in the DB
    if (!$session->exists_contract_id($bad_id))
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
echo '<b># test 4 # $session->exists_trainer_id()</b>';
$id = "tnr_20140723_111111"; // This ID is in the database for testing purposes
if ($session->exists_trainer_id($id))
{
    $bad_id = "tnr_20140723_000000"; // This ID is not in the DB
    if (!$session->exists_trainer_id($bad_id))
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
echo '<b># test 5 # $session->get_nb_remaining_sessions_in_contract($id)</b>';
$id = "ctt_20140723_111111"; // This ID is in the database for testing purposes
$nb_remaining_sessions = $session->get_nb_remaining_sessions_in_contract($id);
if (is_numeric($nb_remaining_sessions))
{
    echo ' is working properly. ('.$nb_remaining_sessions. ' sessions remaining)<br>';
}
else
{
	echo ' is broken. ('. $id .' - make sure there are sessions remaining on that contract)<br>';
}

## test 6: Decrement the number of remaining sessions in a contract
echo '<b># test 6 # $session->decrement_remaining_sessions_in_contract($id)</b>';
$id = "ctt_20140723_111111"; // This ID is in the database for testing purposes
$nb_remaining_sessions_after = $session->decrement_remaining_sessions_in_contract($id);
if (is_numeric($nb_remaining_sessions_after))
{
    echo ' is working properly. ('.$nb_remaining_sessions_after. ' sessions remaining)<br>';
}
else
{
	echo ' is broken. ('. $id .' - make sure there are sessions remaining on that contract)<br>';
}

## test 7: Add a session to the database
echo '<b># test 7 # $session->add_session_to_db()</b> - test disabled to avoid crashing (that function does not check if the session_id is unique) Enable only if there is a problem logging the session)';
//$session->set_client_id("clt_20140723_111111");
//$session->set_trainer_id("tnr_20140723_111111");
//$session->set_contract_id("ctt_20140723_111111");
//$session->set_session_id("ses_20140723_111112");
//$session->set_session_date(date("Y-m-d H:m:s"));
//$session->set_session_type("testing add_session");
//$session->set_comments("This is a system test.");
//if ($session->add_session_to_db())
//{
//    echo ' is working properly.<br>';
//}
//else
//{
//	echo ' is broken. (or the record already exists in the database - check that first.) <br>';
//}


## test 8: log the makafraking session
echo '<b># test 8 # $session->logSession()</b>';
$session->set_client_id("clt_20140723_111111");
$session->set_trainer_id("tnr_20140723_111111");
$session->set_contract_id("ctt_20140723_111111");
$session->set_session_id("ses_20140723_111113");
$session->set_session_date(date("Y-m-d H:m:s"));
$session->set_session_type("test: log session");
$session->set_comments("This is a system test.");


if ($session->log_session())
{
    echo ' is working properly.<br>';
}
else
{
    echo ' is broken.<br>';
}

?>