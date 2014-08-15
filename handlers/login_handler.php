<?php 
session_start(); 

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

#1# "Email" field of the login form is a bot trap.
if ($_POST["email"] != "") 
{
    die;
}

function login($dbh, $user, $password)
{
    try {
        $stmt = $dbh->prepare("SELECT * FROM login WHERE user_name = :user AND password = :password");
        $stmt->bindParam(':user', $user );
        $stmt->bindParam(':password', $password );

        $stmt->execute();
        return $stmt->fetch();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die;
    }
}
$user = $_POST["user_name"];
$password = sha1($_POST["password"]);

$logged_in_user = login($dbh, $user, $password);

if ($logged_in_user !== FALSE)
{
    $_SESSION["logged_in_user"] = $logged_in_user;
    header("Location: ../index.php?pge=dashboard");
}
else 
{
    header("Location: ../index.php?errors=wrongLogin");
}

