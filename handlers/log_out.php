<?php 
session_start();
foreach ($_SESSION as $key => $value)
{
    unset ($_SESSION[$key]);
}
//$_SESSION['logged_in_user'] = "";

header("Location: index.php");
?>