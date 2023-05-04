<?php
/* 
 * Index page. Load configuration data, the relevant page and the template
 */
 
session_start();

// Get Config
include_once 'config.php';
// get dBug
//include_once 'classes/dBug.php';

//error_reporting(E_ALL);

// Check that a page is selected. If no page is selected, go to the home page.
if (isset($_GET['pge']))
{
    // If the user is not logged in, the only visible page is the login page.
    if (is_array($_SESSION['logged_in_user']))
    {
        $page = $_GET['pge'];
    }
    else
    {
        $page = "login"; 
    }
    
}
else
{
// If the user is logged in, go to the dashboard page by default
// else, go to the login page    
    if (is_array(@$_SESSION['logged_in_user']))
    {
        $page = "dashboard";
    }
    else 
    {
        $page = "login";
    }
}

if (!@include_once($page . ".php"))
{
    echo "Error 404. Sorry. ". $page . ".php was not found.";
}

// Get the template
include_once ($template);
?>