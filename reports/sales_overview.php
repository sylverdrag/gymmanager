<?php

/*
 * Getting page data
 */
##-F&R 01-##
$template = 'templates/tplt_main.php';

# header variables
$title          = "Sales overview";
$keywords       = "";
$description    = "Shows all sales for a specific period.";
$extra          = "";


# Main content to buffer
ob_start();

##-F&R 02-##

?>
<div class="main">
    <form method="POST" enctype="multipart/form-data" class="dataInput" name="sales_overview" >
        <div class="formTitle">Sales overview</div>
        <fieldset class="formFieldSet">
            <input type="hidden" value="sales_overview" name="report_type" id="report_type" />
            <div class="entry">
                <label class="lbl_regular" style="">
                    From</label>
                <input type="text" size="38" maxlength="50" id="from_date" name="start_date" value="" class="entryField"/> 
            </div>
            <div class="entry">
                <label class="lbl_regular" style="">
                    To</label>
                <input type="text" size="38" maxlength="50" id="to_date" name="expire_date" value="" class="entryField"/> 
            </div>
            <div class="entry">
                <label class="lbl_regular" style="">
                    Branch</label>
                <select size="1" name="branch" class="entryField">
                    <option value="all">All</option>
                    <option value="Punna">Punna</option>
                    <option value="Meechoke">Meechoke</option>
                </select> 
            </div> 
        </fieldset>
        <div id="bt_display_sales" class="bt_green_rounded" style="width: 200px; text-align: center;margin: 10px auto;">Display report</div>
    </form> 
    <div id="report_area">
        
    </div>
</div>
<script src="js/gym_reports.js" type="text/javascript" charset="utf-8"></script>

<?php
##-F&R 03-##
$content    = ob_get_contents();
ob_end_clean();

##-F&R 04-##



##-F&R 05-##

?>