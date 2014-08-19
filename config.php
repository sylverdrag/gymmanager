<?php

// JS config
$JSconfig = array();

// Paths
$liveServer = false;
if ($liveServer)
{
    define ('ROOT_PATH', "");
    define ('IMAGE_PATH', "/images");
    $JSconfig["online"] = "yes";
}
else
{
    define ('ROOT_PATH', "");
    define ('IMAGE_PATH', "/images");
    $JSconfig["online"] = "no";
}
 
// Set default time zone to Thailand time
date_default_timezone_set("Asia/Bangkok");

$mnu_header =  "<div class='mnu_header'>" .
               "<a href='index.php?pge=forms/log_session'>Log session</a>" .
               "</div>" . 
               "<div class='mnu_header'>" .
               "<a href='index.php?pge=manage'>Manage</a>" .
               "</div>" .
               "<div class='mnu_header'>" .
               "<a href='index.php?pge=reports/reporting_center'>Reports</a>" .
               "</div>" .
               "<div class='mnu_header'>" .
               "<a href='index.php?pge=handlers/log_out'>log out</a>" .
               "</div>";

$footer = "(c) 2014 Sylvain Galibert. All Rights reserved. ";
// Finalize $JSconfig
$JSconfig = json_encode($JSconfig);


?>