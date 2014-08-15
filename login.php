<?php
/* 
This page displays the login form 
*/
##-F&R 01-##
$template = 'templates/tplt_main.php';

# header variables
$title          = "Login";
$keywords       = "";
$description    = "Login to the Gym Manager.";
$extra       = "";


# Main content to buffer
ob_start();

##-F&R 02-##

?>
<div id="page">
    <div id="login_form" style="width: 600px; margin: 20px auto;">
        <form action="handlers/login_handler.php" method="POST" enctype="multipart/form-data" class="dataInput" name="login">
            <input type="text" value="" name="email" style="display: none;" />
        <div class="formTitle">Login</div>
        <div class="entry">
        <label class="lbl_regular" style="">
        User name*</label>
        <input type="text" size="38" maxlength="50" id="user_name" name="user_name" value="" class="entryField" />
        </div>
        <div class="entry">
        <label class="lbl_regular" style="">
        Password*</label>
        <input type="text" size="38" maxlength="50" id="password" name="password" value="" class="entryField" />
        </div>
        <center><input type="submit" value="Login" name="login" /></center></form>
    </div>
</div>

<?php
##-F&R 03-##
$content    = ob_get_contents();
ob_end_clean();

##-F&R 04-##



##-F&R 05-##

?>
