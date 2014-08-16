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
$limit = "LIMIT 3";
$sales = $gym_reports->get_sales($from, $to, $limit);
if ($sales !== false)
{
    echo ' is working properly. Data: <br>';
   // var_dump($sales);
    echo $gym_reports->results_to_table($sales, "Sales", "Sales report from " . $from . " to ". $to);
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
    echo ' is working properly. GI: '. $vsd .'<br>';
}
else
{
    echo ' is broken.<br>';
}



?>
</body>
</html>