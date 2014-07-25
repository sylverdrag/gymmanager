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


//## test 2: getUserByID()
//$userData = $user->getUserByID("usr_20090309_140339");
//$falseID = $user->getUserByID("usr_20090309_14039");
//if ($falseID === false && is_array($userData))
//{
//	echo '$user->getUserByID is working properly.<br>';
//}
//else
//{
//	echo '$user->getUserByID is broken.<br>';
//}
//
//## test 2.1: getUserByEmail()
//$userData ="";
//$userData = $user->getUserByEmail("info@your-translations.com");
//if (!$userData)
//{
//    echo '$user->getUserByEmail doesn\'t work  <br />';
//}
//else
//{
//    echo '$user->getUserByEmail works fine: ';
//    print_r($userData);
//    echo "<br />";
//}
//
//## test 3: isValidName()
//$testName = "Mc' éric";
//if ($user->isValidName($testName))
//{
//	echo '$user->isValidName() is working properly.<br>';
//}
//else{
//	echo '$user->isValidName() is broken.<br>';
//}
//
//## test 4: login()
//if ($user->userExists("sylver"))
//{
//	$user->setPassword("2k3c3f38"); 
//	if ($user->login() && $_SESSION[login] == "yes")
//	{
//		echo '$user->login() is working properly.<br>';
//	}
//	else
//	{
//		echo '$user->login() is broken.<br>';
//	}
//}
//else
//{
//	echo "userExists() failled so Login() was not tested.";
//}
//
//## test 5: isValidPhone()
//if ($user->isValidPhone("(+66)831.544.598") && !$user->isValidPhone("'drop table"))
//{
//	echo '$user->isValidPhone($phone) is working properly<br>';
//}
//else 
//{
//	echo '$user->isValidPhone($phone) is broken<br>';
//}
//
//## test 6: addNewUser()
//unset($user);
//// create a fake $_POST to simulate Form data
//$fakePOST[firstName] 	= "Johny";
//$fakePOST[lastName] 	= "Bronco";
//$fakePOST[userName] 	= "JohnyB";
//$fakePOST[usrPass] 		= "mypass";
//$fakePOST[email]	 	= "Johny@test.com";
//$fakePOST[referer]		= "http://somewebsite.com";
//
//$useradd = new users($connect, $key);
////setting mandatory fields
//$useradd->setFirstName($fakePOST[firstName]);
//$useradd->setLastName($fakePOST[lastName]);
//$useradd->setUserName($fakePOST[userName]);
//$useradd->setPassword($fakePOST[usrPass]);
//$useradd->setEmail($fakePOST[email]);
//$useradd->setReferer($fakePOST[referer]);
//// add new user
//if ($useradd->addNewUser(true))
//{
//	echo '$useradd->addNewUser() is working properly<br>'; 
//}
//else
//{
//	echo '$useradd->addNewUser() is broken (or the user was already in the database).<br>';
//}
//
//## test 7: validateReg()
//$useradd->validateReg("02c18cf0e20e15f36e793df4b6719acb");
//
//## test 8: updateUser()
//
//$useradd->ID = "usr_20090316_180325";
////$useradd->setPassword("new password");
//$useradd->setAccess_admin("no");
//$useradd->setAccess_client("no");
//$useradd->setAccess_eBook("yes");
//$useradd->setAccess_member("yes");
//$useradd->setAccess_terminoBoxPro("no");
//$useradd->setAccess_transPicPro("no");
//$useradd->setAccess_translator("no");
//$useradd->setCellphone("24567812393");
//$useradd->setLastName("Schmuck");
//$useradd->setPhone("29309877342");
//$useradd->setAddressLine1("21 vesterbrogade");
//$useradd->setAddressLine2("");
//$useradd->setCity("copenhavn");
//$useradd->setCountry("Denmark");
//$useradd->setPostCode("13820");
//$updateResult = $useradd->updateUser();
//if ($updateResult)
//{
//	echo '$useradd->updateUser() seems to be working fine<br>' ."\n";
//}
//else
//{
//	echo '$useradd->updateUser() is broken.<br>' . "\n";
//}
//
//## test 9: Translator class
//
//$translator = new translator($connect, $key);
//$translator->setUserName("Joe");
//echo $translator->getUserName();
?>