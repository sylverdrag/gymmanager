<!DOCTYPE html>
<html>
<head>
    <title>Reporting class tests:</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" value="notranslate" />
    
    <link rel="stylesheet" type="text/css" href="../css/basic.css?<?php echo date("c"); ?>" media="screen" />
    <link rel="stylesheet" type="text/css" href="../css/reporting.css" media="screen" />
</head>
<body>
<h1>Reporting class tests:</h1>
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
require("./gym_reports_class.php");


$gym_reports = new gym_reports_class($dbh);

## test 1: 
echo '<b># test 1 # $gym_reports->calculate_GI()</b>';
$from = "2014-07-01";
$to = "2014-08-31";
$gi = $gym_reports->calculate_GI($from, $to);
if ($gi !== false)
{
    $gi = "THB " . number_format($gi);
    echo ' is working properly. GI: '. $gi .'<br>';
}
else
{
    echo ' is broken.<br>';
}

## test 2: 
echo '<b># test 2 # $gym_reports->get_sales($from, $to)</b>';
$from = "2014-07-01";
$to = "2014-08-31";
$limit = "LIMIT 10";
$ignore_cols = array();
$sales = $gym_reports->get_sales($from, $to, $limit);
if ($sales !== false)
{
    echo ' is working properly. Data: <br>';
   // var_dump($sales);
    echo $gym_reports->results_to_table($sales, "Sales", "Sales report from " . $from . " to ". $to, $ignore_cols);
}
else
{
    echo ' is broken.<br>';
}

## test 2b: 
echo '<b># test 2 # $gym_reports->get_sales_by_branch($from, $to)</b>';
$from = "2014-07-01";
$to = "2014-08-31";
$branch = "all";
$limit = "LIMIT 10";
$ignore_cols = array();
$sales = $gym_reports->get_sales_by_branch($from, $to, $branch, $limit);
if ($sales !== false)
{
    echo ' is working properly. Data: <br>';
   // var_dump($sales);
    echo $gym_reports->results_to_table($sales, "Sales", "Sales report from " . $from . " to ". $to, $ignore_cols);
}
else
{
    echo ' is broken.<br>';
}


## test 3: 
echo '<b># test 3 # $gym_reports->calculate_VSD()</b>';
$from = "2014-07-01";
$to = "2014-08-31";
$vsd = $gym_reports->calculate_VSD($from, $to);
if ($vsd !== false)
{
    $vsd = "THB " . number_format($vsd);
    echo ' is working properly. VSD: '. $vsd .'<br>';
}
else
{
    echo ' is broken.<br>';
}

## test 4: 
echo '<b># test 4 # $gym_reports->get_active_contracts_for_client($client_id, $limit);</b>';
$client_id = "clt_20140809_150843";
$limit = "LIMIT 3";
$active_contracts = $gym_reports->get_active_contracts_for_client($client_id, $limit);
$ignore_cols[] = "Name";
$ignore_cols[] = "Contract ID";
if ($active_contracts !== false)
{
    echo ' is working properly. Data: <br>';
    //var_dump($active_contracts);
    $name = $active_contracts[0]["Name"];
    echo $gym_reports->results_to_table($active_contracts, "Contracts", "Active contracts for ". $name, $ignore_cols);
}
else
{
    echo ' is broken.<br>';
}

## test 5: 
echo '<b># test 5 # $gym_reports->get_all_contracts_for_client($client_id, $limit);</b>';
$client_id = "clt_20140809_150843";
$limit = "LIMIT 5";
$all_contracts = $gym_reports->get_all_contracts_for_client($client_id, $limit);
if ($all_contracts !== false)
{
    echo ' is working properly. Data: <br>';
    //var_dump($active_contracts);
    $name = $all_contracts[0]["Name"];

    echo $gym_reports->results_to_table($all_contracts, "Contracts", "All contracts for ". $name, $ignore_cols);
}
else
{
    echo ' is broken.<br>';
}

## test 5b: 
echo '<b># test 5b # $gym_reports->get_all_contracts_for_client($client_id, $limit);</b>';
$client_id = "clt_20140809_150843";
$limit = "LIMIT 5";
$all_sessions = $gym_reports->get_all_sessions_for_client($client_id, $limit);
if ($all_sessions !== false)
{
    echo ' is working properly. Data: <br>';
    //var_dump($active_contracts);
    $name = $all_contracts[0]["Name"];

    echo $gym_reports->results_to_table($all_sessions, "Sessions", "All sessions for ". $name, $ignore_cols);
}
else
{
    echo ' is broken.<br>';
}

## test 5c: 
echo '<b># test 5c # $gym_reports->get_all_contracts_for_client($client_id, $limit);</b>';
$client_id = "clt_20140809_150843";
$limit = "LIMIT 5";
$client_details = $gym_reports->get_client_details($client_id);
if ($client_details !== false)
{
    echo ' is working properly. Data: <br>';
    var_dump($client_details);
}
else
{
    echo ' is broken.<br>';
}


## test 6: 
echo '<b># test 6 # $gym_reports->get_all_sales_by_client($limit);</b>';
$limit = "LIMIT 5";
$sales_by_client = $gym_reports->get_all_sales_by_client($limit);
$ignore_cols = array();
if ($sales_by_client !== false)
{
    echo ' is working properly. Data: <br>';
    //var_dump($active_contracts);
    echo $gym_reports->results_to_table($sales_by_client, "Sales", "Sales by client", $ignore_cols);
}
else
{
    echo ' is broken.<br>';
}

## test 7: 
echo '<b># test 7 # $gym_reports->clients_sessions_in_period($from, $to, $limit);</b>';
$from = "2014-07-01";
$to = "2014-08-31";
$limit = "LIMIT 5";
$ignore_cols = array();
$client_sessions = $gym_reports->clients_sessions_in_period($from, $to, $limit);
if ($client_sessions !== false)
{
    echo ' is working properly. Data: <br>';
   // var_dump($sales);
    echo $gym_reports->results_to_table($client_sessions, "Sessions", "Client sessions from " . $from . " to ". $to, $ignore_cols);
}
else
{
    echo ' is broken.<br>';
}

## test 8: 
echo '<b># test 8 # $gym_reports->get_nb_sessions_in_period($from, $to);</b>';
$from = "2014-07-01";
$to = "2014-08-31";
$nb_sessions = $gym_reports->get_nb_sessions_in_period($from, $to);
if ($nb_sessions !== false)
{
    echo ' is working properly. '. $nb_sessions .' sessions.<br>';
}
else
{
    echo ' is broken.<br>';
}

## test 9: 
echo '<b># test 9 # $gym_reports->trainer_activity_in_period($trainer_id, $from, $to, $limit);</b>';
$from = "2014-07-01";
$to = "2014-08-31";
$limit = "LIMIT 5";
$trainer_id = "tnr_20140809_150832";
$ignore_cols[] = "Trainer";
$trainer_activity = $gym_reports->trainer_activity_in_period($trainer_id, $from, $to, $limit);
if ($trainer_activity !== false)
{
    echo ' is working properly. Data: <br>';
    $trainer_name = $trainer_activity[0]["Trainer"];
    echo $gym_reports->results_to_table($trainer_activity, "Trainer_Activity", "Trainer activity from " . $from . " to ". $to . " for " . $trainer_name, $ignore_cols);
}
else
{
    echo ' is broken.<br>';
}

## test 10: 
echo '<b># test 10 # $gym_reports->calculate_trainer_fees($trainer_id, $from_date, $to_date);</b>';
$from = "2014-07-01";
$to = "2014-08-31";
$trainer_id = "tnr_20140809_150832";
$trainer_fee = $gym_reports->calculate_trainer_fees($trainer_id, $from, $to);
if ($trainer_fee !== false)
{
    echo ' is working properly. THB '. $trainer_fee .' ($trainer_id = "tnr_20140809_150832")<br>';
}
else
{
    echo ' is broken.<br>';
}

## test 11: 
echo '<b># test 11 # $gym_reports->all_trainer_activity_in_period($from, $to, $limit);</b>';
$from = "2014-07-01";
$to = "2014-08-31";
$limit = "LIMIT 10";
$ignore_cols = array();
$all_trainer_activity = $gym_reports->all_trainer_activity_in_period($from, $to, $limit);
if ($all_trainer_activity !== false)
{
    echo ' is working properly. Data: <br>';
    echo $gym_reports->results_to_table($all_trainer_activity, "All_Trainer_Activity", "All trainer activity from " . $from . " to ". $to, $ignore_cols);
}
else
{
    echo ' is broken.<br>';
}

## test 12: 
echo '<b># test 12 # $gym_reports->calculate_total_trainer_fees($from, $to);</b>';
$from = "2014-07-01";
$to = "2014-08-31";
$total_trainer_fees = $gym_reports->calculate_total_trainer_fees($from, $to);
if ($total_trainer_fees !== false)
{
    echo ' is working properly. THB '. $total_trainer_fees .' <br>';
}
else
{
    echo ' is broken.<br>';
}

?>
</body>
</html>