/*
 * Handle the reporting pages
 */
// Functions
function get_report(report_type, from, to, branch)
{
    alert (report_type + "; " + from + "; " + to + "; " + branch);
    
}

// Document loaded
$( window ).load(function(){
    // Get the sales overview
    $("#bt_display_sales").click(function(){
        var report_type = $("#report_type").val();
        var from = $("#from_date").val();
        var to = $("#to_date").val();
        var branch = $("#branch").val();
        get_report(report_type, from, to, branch);        
    });
    
//    end of $(document).ready
});//


