/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery("div#success").hide();
jQuery("div#error").hide();
jQuery("div#feedback_bar").hide();
jQuery("div#feedback_bar_error").hide();

// jQuery("input[name='submit_sms']").on("click", function(event) {
jQuery("body").on("click", "input[name='submit_sms']", function(event) {
    event.preventDefault();
    var url = jQuery("form#massage_form").attr("action");
    var checkExistURL = url.replace("save", "check_duplicate_data");
    var data = jQuery("form#massage_form").serialize();
    var baseURL = jQuery('input[name="baseURL"]').val();
    var massage_name = jQuery("input[name='massage_name']").val();
    var massage_desc = jQuery("input[name='massage_desc']").val();
    var supplier_idd = jQuery("select[name='supplier_id']").val();
    var price_one = jQuery("input[name='price_one']").val();
    var massage_typesID = jQuery("select[name='massage_typesID']").val();
    var item_massage_id = jQuery("input[name='item_massage_id']").val();
    if (massage_name == "") {
        jQuery('div#getSmsError').text("Please Input Massage Name!");
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
        return false;
    }
    else if (massage_desc == "") {
        jQuery('div#getSmsError').text("Please Input Massage Description!");
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
        return false;
    }
    else if (price_one == "") {
        jQuery('div#getSmsError').text("Please Input Massage Price One!");
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
        return false;
    }
    /*else if (supplier_idd == 0) {
        jQuery('div#getSmsError').text("Please Seletct Supplier Type!");
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
        return false;
    }
    else if (massage_typesID == 0) {
        jQuery('div#getSmsError').text("Please Input Massage type!");
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
        return false;
    }*/
    if (!item_massage_id) {
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
                save(data, url, "massages");
            }
        });
    } else {
        save(data, url, "massages");
    }
});

/*function save(data, url, modal_id) {
    var search = url.replace("saved", "search");
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

// add payment of sale massage 
$('body').on('click', 'div#add_payment_button', function(event) {
    event.preventDefault();
    var url = jQuery("form#add_payment_form").attr("action");
    $.ajax({
        type: "POST",
        url: url,
        data: jQuery('form#add_payment_form').serialize(),
        success: function(data) {
            $("div#register_container").html(data);
        }
    });
});

// set time in of sale massage
jQuery("body").on("click", "input[name='btn_time_in']", function(event) {
    event.preventDefault();
    var url = jQuery("form#set_time_in_form").attr("action");

    jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "html",
        data: jQuery("form#set_time_in_form").serialize(),
        success: function(respons) {
            $("div#register_container").html(respons);
        }
    });
});

// set time out of sale massage
jQuery("body").on("click", "input[name='btn_time_out']", function(event) {
    event.preventDefault();
    var url = jQuery("form#set_time_out_form").attr("action");

    jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "html",
        data: jQuery("form#set_time_out_form").serialize(),
        success: function(respons) {
            $("div#register_container").html(respons);
        }
    });
});

// Add new massages on Sale
jQuery("body").on("click", "input[name='btn_submit_massages']", function(event) {
    event.preventDefault();
    var check_duplicate = baseURL + "massages/check_duplicate_data";
    var saveAction = baseURL + "massages/saved/-1";
    var addAction = baseURL + "massages/add";

    // get value from add new massage form
    var massage_name = jQuery("input[name='massage_name']").val();
    var massage_desc = jQuery("input[name='massage_desc']").val();
    var supplier_id = jQuery("select[name='supplier_id']").val();
    var price_one = jQuery("input[name='price_one']").val();
    var massage_typesID = jQuery("select[name='massage_typesID']").val();

    if (massage_name == "" || massage_typesID == 0 || price_one == "") {
        jQuery('div#getSmsError').text('Please fill all the fields of item!');
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
        return false;
    }
    ;
    jQuery.ajax({
        type: "POST",
        url: check_duplicate,
        dataType: "json",
        data: jQuery('form#massage_form').serialize(),
        success: function(msg) {
            if (msg.duplicate) {
                var duplicating = "A similar massage code already exists. Do you want to continue? maybe no need here";
                if (!confirm(duplicating)) {
                    return false;
                }
                ;
                saveMassage(saveAction, addAction);
            } else {

                saveMassage(saveAction, addAction);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
});


/* Save new massage */
function saveMassage(saveAction, addAction) {
    $.ajax({
        type: "POST",
        url: saveAction,
        dataType: "json",
        data: jQuery("form#massage_form").serialize(),
        success: function(respons) {
            addToCart(respons.item_massage_id, addAction);
            jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
            jQuery('div#getSmsError').text('Success adding new item! Please close your form!');
            jQuery("#massages").modal('hide');
        }
    });
}

// Add massage to cart
function addToCart(item_massage_id, addAction) {
    $.ajax({
        type: "POST",
        url: addAction,
        dataType: "html",
        data: {"item": item_massage_id},
        success: function(respons) {
            jQuery("#massages").modal('hide');
            $("div#register_container").html(respons);
        }
    });
}

// New 07/08/2014
// Set massager in register sale of each item of massage
$('body').on('keypress','input#each_massager',function(event){
    var current_obj = $(this);
    if (event.keyCode === $.ui.keyCode.ENTER) {
        return false;
    }
    var term = current_obj.val();
    var url = current_obj.parents("form").attr("action");
    if (current_obj.attr('id')=='each_massager') {
        var suggest = baseURL + "massages/massager_search";
    } else {
        var suggest = baseURL + "commissioners/commissioner_search";
    }
    
    $.ajax({
        type: "POST",
        url: suggest,
        dataType: "json",
        data: {"term" : term},
        success: function(data){
            current_obj.autocomplete({
                source: data,
                select: function(e, ui) {
                    current_obj.next().val(ui.item.value);
                }
            });
        }
    });
 });
