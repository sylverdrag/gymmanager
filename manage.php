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
    <h1 class="formTitle">Manager</h1>
    <h2>Create...</h2>
    <ul id="lks_create">
        <li><a href='index.php?pge=forms/add_client'>New client</a></li>
        <li><a href='index.php?pge=forms/add_contract'>New contract</a></li>
        <li><a href='index.php?pge=forms/add_training_package'>New package</a></li>
        <li><a href='index.php?pge=forms/add_trainer'>New trainer</a></li>
    </ul>
    <h2>Edit existing...</h2>
    <ul id="lks_edit">
        <li><a href='index.php?pge=forms/edit_clients'>Edit clients</a></li>
        <li>contract</li>
        <li>package</li>
        <li>trainer</li>
    </ul>
    
</div>

<?php
##-F&R 03-##
$content    = ob_get_contents();
ob_end_clean();

##-F&R 04-##



##-F&R 05-##

?>
