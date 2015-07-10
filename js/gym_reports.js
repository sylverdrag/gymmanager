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
    
    /* 
     * Updating clients, contracts and session data
     * */
    $("#bt_update_all").click(function(){
        var data = {};
        // Get the client's data
        var client_id = $("#client").attr("data-client_id");
        var client_data = {};
        $(".clients_detail").each(function() {
            var id = $(this).attr("data-colname");
            var value = $(this).html();
            client_data[id] = value;
        });
        data["client"] = client_data;
        
        // Get the contracts data
        var contracts_data = {};
        $(".contract").each(function(){
            var contract_id= $(this).attr("data-contract_id");
            var contract_data = {};
            $(this).find(".contracts_detail").each(function() {
                var id = $(this).attr("data-colname");
                var value = $(this).html();
                contract_data[id] = value;
            }); 
            contracts_data[contract_id] = contract_data;
        });
        data["contracts"] = contracts_data;
        
        // Get the sessions' data
        var sessions_data = {};
        $(".session").each(function(){
            var session_id= $(this).find("#date").attr("data-sess_id");
            var session_data = {};
            $(this).find(".sessions_detail").each(function() {
                var id = $(this).attr("data-colname");
                var value = $(this).html();
                session_data[id] = value;
            }); 
            sessions_data[session_id] = session_data;
        });
        data["sessions"] = sessions_data;
        
        
        var JSON_data = JSON.stringify(data);
        var form_purpose = "update_client_contracts_sessions";
        
        $.ajax
        ({
            async: true,
            url: 'handlers/gymHandler.php',
            type: 'POST',
            data: "JSON_data=" + JSON_data + "&formPurpose=" + form_purpose,
            dataType: "text",
            success: function(result)
            {
                if (result === "ok")
                {
                    alert(form_purpose + ": OK");
                }
                else
                {
                    alert(form_purpose + ': ' + result);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {
                // Debugging error message. Add error handling later
                console.log(JSON.stringify(XMLHttpRequest));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
    
    return false;

           
    });
    
    
//    end of $(document).ready
});//


