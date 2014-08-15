<?php
/* 
This page displays the login form 
*/
##-F&R 01-##
$template = 'templates/tplt_main.php';

# header variables
$title          = "Dashboard";
$keywords       = "";
$description    = "Welcome to the Gym Manager.";
$extra       = "";


# Main content to buffer
ob_start();

##-F&R 02-##

?>
<div id="page">
    Yeah, Dashboard
    <hr>
    <?= var_dump($_SESSION); ?>
</div>

<?php
##-F&R 03-##
$content    = ob_get_contents();
ob_end_clean();

##-F&R 04-##



##-F&R 05-##

?>
