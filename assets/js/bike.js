/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var baseURL = jQuery('input[name="baseURL"]').val();
var controller = jQuery('input[name="controller_name"]').val();
var checkExistURLItem = baseURL + "bikes/check_duplicate_item";

//chen add new bike and update
jQuery("body").on("click", "input#saveSubmitBike", function(event) {
    event.preventDefault();
    var bike_id = jQuery("input[name='item_bike_id']").val();
    var checkExistURL = baseURL + "bikes/check_duplicate";
    // var addURL = jQuery("form#bikes_form").attr("action") + "/" + bike_id;
    var addURL = jQuery("form#bikes_form").attr("action");
    var code_bike = jQuery("input[name='bike_code']").val();
    var unit_price = jQuery("input[name='unit_price']").val();
    var actual_price = jQuery("input[name='actual_price']").val();
    var bike_types = jQuery("select[name='bike_types']").val();
    var data = jQuery("form#bikes_form").serialize();

    if (code_bike == "") {
        jQuery('div#getSmsError').text("Please Input code bike!");
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400).fadeOut(400);
        return false;
    }
    else if (unit_price == "") {
        jQuery('div#getSmsError').text("Please Input Unit price!");
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
        return false;
    }
    else if (actual_price == "") {
        jQuery('div#getSmsError').text("Please Input actual price!");
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
        return false;
    }
    else if (bike_types == 0) {
        jQuery('div#getSmsError').text("Please Seletct bike types!");
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
        return false;
    }
    if (!bike_id) {
        jQuery.ajax({
            type: "POST",
            url: checkExistURL,
            dataType: "json",
            data: data,
            success: function(json) {
                if (json.duplicate) {
                    jQuery('div#getSmsError').text("This record is duplicated!");
                    jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400).fadeOut(400);
                    return false;
                }
                save(data, addURL, "bikes");
            }
        });
    } else {
        save(data, addURL, "bikes");
    }
});

/* Save new bike */
/*function save(data, url, modal_id) {
    var search = url.replace("save", "search");
    $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        data: data,
        success: function(response) {
            if (response.success) {
                $("#"+modal_id).modal('hide');
                $('.modal').on('hidden.bs.modal', function(){
                    $(this).find('form')[0].reset();
                });
                set_feedback(response.message,'success_message',false);
                getDataSearch("", search);
            } else {
                set_feedback(response.message,'error_message',false);
                return false;
            }
        }
    });
}*/
//finish add and update
/*function getDataSearch(term, url){
    jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        data: {"search" : term},
        success: function(data){
            if (data) {
                var txt = data.manage_table;
                if(txt != ""){
                    $("table#data_table tbody").html(txt);
                    $("div#pagination").html(data.pagination);
                }
            };
        }
    });
}*/

//chen add new bike and update

jQuery("body").on("click", "input#submitBike", function(event) {
    event.preventDefault();
    var bike_id = jQuery("input[name='item_bike_id']").val();
    var checkExistURL = baseURL + "bikes/check_duplicate";
    var saveAction = baseURL + "bikes/save/-1";
    var addAction = baseURL + "bikes/add";
    var code_bike = jQuery("input[name='bike_code']").val();
    var unit_price = jQuery("input[name='unit_price']").val();
    var actual_price = jQuery("input[name='actual_price']").val();
    var bike_types = jQuery("select[name='bike_types']").val();
    var suppliers = jQuery("input[name='supplier']").val();
    var desc = jQuery("input[name='description']").val();

    if (code_bike == "") {
        jQuery('div#getSmsError').text("Please Input code bike!");
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400).fadeOut(400);
        return false;
    }
    else if (unit_price == "") {
        jQuery('div#getSmsError').text("Please Input Unit price!");
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
        return false;
    }
    else if (actual_price == "") {
        jQuery('div#getSmsError').text("Please Input actual price!");
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
        return false;
    }
    else if (bike_types == 0) {
        jQuery('div#getSmsError').text("Please Seletct bike types!");
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
        return false;
    }
    if (!bike_id) {
        
        jQuery.ajax({
            type: "POST",
            url: checkExistURL,
            dataType: "json",
            data: jQuery("form#bikes_form").serialize(),
            success: function(json) {
                if (json.duplicate) {
                    jQuery('div#getSmsError').text("This record is duplicated!");
                    jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400).fadeOut(400);
                    return false;
                }
                saveBike(saveAction, addAction);
            }
        });
    } else {
        saveBike(saveAction, addAction);
    }
});

/* Save new bike */
//function saveBike(addURL) {
function saveBike(saveAction, addAction) {
    $.ajax({
        type: "POST",
//        url: addURL,
        url: saveAction,
        dataType: "json",
        data: $("form#bikes_form").serialize(),
        success: function(respons) {
                jQuery("#bikes").modal('hide');
                addToCart(respons.bike_id, addAction);
          
        }
    });

}
//finish add and update

// Add bike to cart
function addToCart(bike_id, addAction) {
    $.ajax({
        type: "POST",
        url: addAction,
        dataType: "html",
        data: {"item": bike_id},
        success: function(respons) {
            
            $("div#register_container").html(respons);
            jQuery("#bikes").modal('hide');
        }
    });
}

//delete bike
jQuery("a#delete_bike").click(function(event) {
    event.preventDefault();
    if (jQuery('input[type="checkbox"]').is(':checked')) {
        var url = jQuery(this).attr('href');
        var rcdsChecked = jQuery('input:checkbox:checked.check_value').map(function() {
            return jQuery(this).val();
        }).get();
        var confirming = "Do you want to delete as permanent?";
        if (!confirm(confirming)) {
            return false;
        };
        jQuery.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            data: {"ids": rcdsChecked},
            success: function(response) {
                if (response.success) {
                    jQuery('input:checkbox:checked').each(function() {    
                        jQuery('input:checkbox:checked').parents("tr").remove();
                        jQuery('input:checkbox:checked').removeAttr('checked');
                    });
                    set_feedback(response.message,'success_message',false);
                } else {
                    set_feedback(response.message,'error_message',false);
                }
            }
        });
    } else {
        set_feedback('Please select check box to delete!','error_message',false);
        return false;
    }
});

// Set message feedback
/*function set_feedback(text, classname, keep_displayed)
{
    if(text!='')
    {
        $('#feedback_bar').removeClass();
        $('#feedback_bar').addClass(classname);
        $('#feedback_bar').html(text + '<div id="feedback_bar_close"><img src="images/close.png" /></div>');
        $('#feedback_bar').slideDown(250);
        var text_length = text.length;
        var text_lengthx = text_length*125;

        if(!keep_displayed)
        {
            $('#feedback_bar').show();
            
            setTimeout(function()
            {
                $('#feedback_bar').slideUp(250, function()
                {
                    $('#feedback_bar').removeClass();
                });
            },text_lengthx);
        }
    }
    else
    {
        $('#feedback_bar').hide();
    }

    $('#feedback_bar_close').click(function()
    {
        $('#feedback_bar').slideUp(250, function()
        {
            $('#feedback_bar').removeClass();
        });
    });
    
}*/
