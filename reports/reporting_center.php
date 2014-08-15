<?php
/* 
This page displays the login form 
*/
##-F&R 01-##
$template = 'templates/tplt_main.php';

# header variables
$title          = "Reports";
$keywords       = "";
$description    = "Check out how you are doing and what's going on with your business.";
$extra          = "";


# Main content to buffer
ob_start();

##-F&R 02-##

?>
<div id="page">
    <h1 class="formTitle">Reporting center</h1>
    <h2>Key reports...</h2>
    <ul id="lks_reports">
        <li><a href='index.php?pge=reports/sales_overview'>Sales overview</a></li>
        <li><a href='index.php?pge=reports/client_summary'>Client overview</a></li>
        <li><a href='index.php?pge=reports/client_activity'>Client activity</a></li>
        <li><a href='index.php?pge=reports/trainer_activity'>Trainer activity</a></li>
    </ul>  
    <h2>Extra reports...</h2>
    <ul id="lks_reports">
        <li><a href='index.php?pge=reports/awol_clients'>AWOL clients</a></li>
    </ul>
</div>

<?php
##-F&R 03-##
$content    = ob_get_contents();
ob_end_clean();

##-F&R 04-##



##-F&R 05-##

?>
