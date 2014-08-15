/* Scrolls the item to the center of the window vertically */
jQuery.fn.scroll_to_center = function() { 
    var el_v_offset = $(this).offset().top;
    var el_h_offset = $(this).offset().left;
    var elHeight = $(this).height();
    var elWidth = $(this).width();
    var windowHeight = $(window).height();
    var windowWidth = $(window).width();
    var voffset;
    var hoffset;

    if (elHeight < windowHeight) { voffset = el_v_offset - ((windowHeight / 2) - (elHeight / 2));  }
    else { voffset = el_v_offset; }

    if (elWidth < windowWidth) { hoffset = el_h_offset - ((windowWidth / 2) - (elWidth / 2));  }
    else { hoffset = el_h_offset; }

    $('html, body').animate({
        scrollTop:voffset,
        scrollLeft:hoffset
    }, 700);
}

function get_contracts_for_client(client_id)
{    
    $.ajax
    ({
        async: true,
        url: 'handlers/gymHandler.php',
        type: 'POST',
        data: "client_id=" +client_id+ "&formPurpose=get_client_active_contracts",
        dataType: "text",       /* For some reason the call fails if I return json directly so I am getting the json as text and parsing it.  */
        success: function(result)
        {
            result = $('<div/>').html(result).text();
            result = $.parseJSON(result);
            if (result.status === "ok")
            {
                 for (var i = 0; i < result.data.length; i++)
                {       
                    $("#contract_id").append($('<option/>', 
                                            { 
                                                value: result.data[i]["contract_id"],
                                                text : result.data[i]["training_type"] + ", remaining: " + result.data[i]["remaining_sessions"],
                                                title: result.data[i]["training_type"]
                                            }));
                    $('#training_type').val(result.data[0]["training_type"]);
                }
            }
            else if (result.status === "no valid contract found")
            {
                alert('No valid contract for this client. Please create a contract first, then add the session.');
            }
            else
            {
                alert('An error occured. Please contact the webmaster');
                return result.status;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown)
        {
            // Debugging error message. Add error handling later
            console.log(JSON.stringify(XMLHttpRequest));
            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
           // alert ("error: "+textStatus +" status: " + textStatus + "response" + XMLHttpRequest.responseText);
        }
    });
    return false;
}



$( window ).load(function(){
    // Get package data and fill out the package details in the form when a package is selected.
    var package_div_content = $("#package_data").html();
    if (package_div_content !== undefined)
    {
        var package_data = $.parseJSON($('#package_data').html());
        var training_type = "";
        var nb_sessions = "";
        var price_per_session = "";
        var total = "";
        $("#package_id").change(function(){
            var package_id = $(this).val();
            for (var i = 0; i< package_data.length; i++)
            {
                id = $('<div/>').html(package_data[i]["package_id"]).text();
                if (package_id === id)
                {
                    training_type = $('<div/>').html(package_data[i]["type"]).text();
                    nb_sessions = $('<div/>').html(package_data[i]["nb_sessions"]).text();
                    price_per_session = $('<div/>').html(package_data[i]["price_per_session"]).text();
                    total = $('<div/>').html(package_data[i]["full_price"]).text();
                }
            }  
            $("#training_type").val(training_type);
            $("#nb_sessions").val(nb_sessions);
            $("#remaining_sessions").val(nb_sessions);
            $("#price_per_session").val(price_per_session);
            $("#total").val(total);
            $("#package_details").show();
        });
    }
    $( "#start_date" ).datepicker({
        dateFormat: "yy-mm-dd"
    });
    $( "#expire_date" ).datepicker({
        dateFormat: "yy-mm-dd"
    });
    $( "#from_date" ).datepicker({
        dateFormat: "yy-mm-dd"
    });
    $( "#to_date" ).datepicker({
        dateFormat: "yy-mm-dd"
    });


    // Get the contracts for a specific client
    $("#client_id").change(function(){
        var client_id = $(this).val();
        get_contracts_for_client(client_id);        
    });
    
    $('#contract_id').change(function(){
       var contract_type = $('#contract_id option:selected').attr("title");
       $('#training_type').val(contract_type);
    });
//    end of $(document).ready
});//


