 /*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
* Base URL of application
*/

var baseURL = jQuery('input[name="baseURL"]').val();
var office_number = jQuery('input[name="office_number"]').val();
var controller = jQuery('input[name="controller_name"]').val();

// Reload file javascript for bootstrap form helper of timepicker
// if (controller == "tickets") {
    setInterval(function() {
        var url1 = baseURL + 'js/jquery.datePicker-2.1.2.js';
        var url2 = baseURL + 'js/datepicker.js';
        var url3 = baseURL + 'js/date.js';
        
        $.get(url1, function(data) {
          // do something with the data
        });
        $.get(url2, function(data) {
          // do something with the data
        });
        $.get(url3, function(data) {
          //do something with the data
        });
    }, 5000);
// }
setInterval(function() {
    var url = baseURL + 'assets/timepicker/js/bootstrap-formhelpers-timepicker.js';
    var urlDate = baseURL + 'assets/timepicker/js/bootstrap-formhelpers-datepicker.js';
    $.get(url, function(data) {
          //do something with the data
        });
    $.get(urlDate, function(data) {
          //do something with the data
        });
    $('.date-pick').datePicker();
}, 1000);


jQuery("body").on("click", "input[name='btn_time_departure']", function(event){
    event.preventDefault();
    var url = jQuery("form#set_time_departure_form").attr("action");
    jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "html",
        data: jQuery("form#set_time_departure_form").serialize(),
        success: function(response) {
            $("div#register_container").html(response);
        }
    });
});

//    Add new ticket on ticket management
jQuery("div#success").hide();
jQuery("div#error").hide();
jQuery("div#feedback_bar").hide();
jQuery("div#feedback_bar_error").hide();

jQuery("body").on("click", "input[name='submit_ticket']", function(event) {
    event.preventDefault();
    var check_duplicate =  baseURL + "tickets/check_duplicate_data";
    var saveAction = jQuery("form#ticket_form").attr("action");
    var ticket_name = jQuery("input[name='ticket_name']").val();
    var destination_id = jQuery("select[name='destination_id']").val();
    var ticket_type = jQuery("select[name='ticket_typeID']").val();
    var ticket_type_new = jQuery("input[name='input_ticket_type']").val();
    var actual_price = jQuery("input#actual_price").val();
    var sale_price = jQuery("input[name='sale_price']").val();
    var ticket_id = jQuery("input[name='ticket_id']").val();
   
//    validation codition from insert ticket 
    if (ticket_name == "") {
        jQuery('div#getSmsError').text('Please fill Ticket Name!');
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
        return false;
    }
    // else if (destination_id == 0) {
    //     jQuery('div#getSmsError').text('Please select Destination!');
    //     jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
    //     return false;
    // }
    else if (ticket_type == 0 && ticket_type_new == '') {
        jQuery('div#getSmsError').text('Please select Ticket Type!');
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
        return false;
    }
    /*else if (actual_price == "") {
        jQuery('div#getSmsError').text('Please Fill Actual Price!');
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
        return false;
    }*/
    else if (sale_price == "") {
        jQuery('div#getSmsError').text('Please Fill Sale Price!');
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
        return false;
    }

    var data = jQuery('form#ticket_form').serialize();

    if (ticket_id == "") {
        check_duplicate_destination(function(result) {
            if (result.duplicate) {
                    jQuery('div#getSmsError').text('This destination has duplicate!');
                    jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
                    return false;
            } else {
                jQuery.ajax({
                    type: "POST",
                    url: check_duplicate,
                    dataType: "json",
                    data: jQuery('form#ticket_form').serialize(),
                    success: function(msg) {
                        if (msg.duplicate) {
                            jQuery('div#getSmsError').text('This record has duplicate, please check your enter input!');
                            jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
                            return false;
                        } else {
                            addTicketManage(data, saveAction, 'tickets')
                            // saveTicket(saveAction, addAction);
                        }
                    }
                });
            }
        });
    } else {
        addTicketManage(data, saveAction, 'tickets')
        // saveTicket(saveAction, addAction);
    }
    
});


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

 jQuery("body").on("click", "a.list-group-item", function(){
    jQuery("input[name='ticket_id']").val("");
    jQuery("input[name='ticket_name']").val("");
    jQuery("textarea[name='description']").val("");
    jQuery("select[name='destinationID']").val(0);
    jQuery("select[name='supplier']").val("");
    jQuery("select[name='ticket_typeID']").val(0);
    jQuery("input[name='actual_price']").val("");
    jQuery("input[name='sale_price']").val("");
 });

/* Click edit link and retrieve data into form for edit */
  /*$('body').on('click','a#edit_ticket',function(){
    var url = jQuery(this).attr("href").replace("#", "");
    $.ajax({
        url: url,
        dataType: "json", 
        success: function(data){     
            jQuery("input[name='ticket_id']").val(data.ticket_id);
            jQuery("input[name='ticket_name']").val(data.ticket_name);
            jQuery("textarea[name='description']").val(data.descriptions);
            jQuery("select[name='destination_id']").val(data.destinationID);
            jQuery("select[name='supplier']").val(data.supplierID);
            jQuery("select[name='ticket_typeID']").val(data.ticket_typeID);
            jQuery("input[name='actual_price']").val(data.actual_price);
            jQuery("input[name='sale_price']").val(data.sale_price);
        }
    }); 
  });*/

// When keypress, start to search data for autocomplete
    $('body').on('keypress','input.search_item',function(event){
        if (event.keyCode === $.ui.keyCode.ENTER) {
            return false;
        }
        var term = jQuery(this).val();
        var url = jQuery("form.form_add_item").attr("action");
        var suggest = url.replace("add", "item_search_suggestion");
        $.ajax({
            type: "POST",
            url: suggest,
            dataType: "json",
            data: {"term" : term},
            success: function(data){
                $("input.search_item").autocomplete({
                  source: data,
                  select: function(e, ui) {
                    addItemToCart(ui.item.value, url);
                  }
                });
            }
        });
    });

function addItemToCart(term, url){
    jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "html",
        data: {"item" : term},
        success: function(data){
            $("div#register_container").html(data);
        }
    });
}

// Add payment
/*$('body').on('click','div#add_payment_button',function(event){
    event.preventDefault();
    var url = jQuery("form#add_payment_form").attr("action");
    $.ajax({
        type: "POST",
        url: url,
        data: jQuery('form#add_payment_form').serialize(),
        success: function(data){

            var respons = $("div#register_container").html(data);

            $('input#change_sale_date').datePicker();
        }
    });
});*/
// Delete payment
$("body").on('click',"a.delete_payment",function(event){
    event.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        type: "POST",
        url: url,
        success: function(data){
            $("div#register_container").html(data);
        }
    });
});

// Search autocompleted on sale
$('body').on('keypress','input#customer',function(event){
    if (event.keyCode === $.ui.keyCode.ENTER) {
        return false;
    }
    var term = jQuery("input#customer").val();
    var url = jQuery("form#select_customer_form").attr("action");
    var suggest = url.replace("select_customer", "customer_search");
        $.ajax({
            type: "POST",
            url: suggest,
            dataType: "json",
            data: {"term" : term},
            success: function(data){
                $("input#customer").autocomplete({
                    source: data,
                    select: function(e, ui) {
                        getData(ui.item.value, url);
                    }
                });
            }
        });
 });

// Add after search autocompleted
function getData(term, url) {
    jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "html",
        data: {"term" : term},
        success: function(data){
            $("div#register_container").html(data);
        }
    });
}

 // Add customer with dialog modal
 // Add new customer on sale
 jQuery("body").on("click", "input#btnSubmitCustomer", function(event){
        event.preventDefault();
        var check_duplicate =  baseURL + "customers/check_duplicate";
        var saveAction = jQuery("form#customer_frm").attr("action");
        var selectAction = baseURL + controller+"/select_customer";
        var first_name = jQuery("input#first_name").val();
        var last_name = jQuery("input#last_name").val();
        if (first_name=="" || last_name=="") {
            jQuery('div#getSmsError').text('Please fill all the fields!');
            jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
            return false;
        };
        var customer_id = jQuery("input[name='customer_id']").val();
        if (customer_id == "") {
            jQuery.ajax({
                type: "POST",
                url: check_duplicate,
                dataType: "json",
                data: jQuery('form#customer_frm').serialize(),
                success: function(msg) {
                    if (msg.duplicate) {
                        var duplicating = "A similar customer name already exists. Do you want to continue?";
                        if (!confirm(duplicating)) {
                            return false;
                        };
                        saveCustomer(saveAction, selectAction);
                    } else {
                        saveCustomer(saveAction, selectAction);
                    }
                }
            });
        } else {
            saveCustomer(saveAction, selectAction);
        }
    });

// Save customer
function saveCustomer(saveAction, selectAction) {
    $.ajax({
        type: "POST",
        url: saveAction,
        dataType: "json",
        data: $("form#customer_frm").serialize(),
        success: function(response) {
            if(response.success)
            {
                set_feedback(response.message,'success_message',false);
                selectCustomer(response.person_id, selectAction);
            }
            else
            {
                set_feedback(response.message,'error_message',false);      
            }
        }
    });
}

// Select customer after saved
function selectCustomer(customer_id, selectAction) {
    $.ajax({
        type: "POST",
        url: selectAction,
        dataType: "html",
        data: { term: customer_id},
        success: function(response) {
            $("div#register_container").html(response);
            jQuery("#customers").modal('hide');
        }
    });
}

// Delete customer after add on sale
$("body").on("click", "a#delete_customer, a#delete_guide", function(event){
    event.preventDefault();
    var urlDeleteCustomer = $(this).attr("href");
    $.ajax({
        type: "POST",
        url: urlDeleteCustomer,
        dataType: "html",
        success: function(response){
            $("div#register_container").html(response);
            return false;
        }
    });
    return false;
});

// Suspend on sale
jQuery("body").on("click", "div#suspend_sale_button", function(){
    var confirming = "Are you sure you want to suspend this sale?";
    if (!confirm(confirming)) {
        return false;
    };
    jQuery.ajax({
        type: "POST",
        url: baseURL + controller + "/suspend",
        dataType: "html",
        success: function(response) {
            $("div#register_container").html(response);
        }
    });
});

// Cancel sale
jQuery("body").on("click", "div#cancel_sale_button", function(event){
    event.preventDefault();
    var url = jQuery("form#cancel_sale_form").attr("action");
    var confirming = "Are you sure you want to clear this sale? All items will cleared.";
    if (!confirm(confirming)) {
        return false;
    };
    jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "html",
        success: function(response){
            $("div#register_container").html(response);
        }
    });
});

jQuery("body").on("click", "a#show_suspended_sales_button", function(){
    suspended(function(result) {
        $("table#appendTable").html(result);
    });
});

function suspended(callback) {
    var data = '';
    jQuery.ajax({
        type: "POST",
        url: baseURL + controller +"/suspended",
        dataType: "json",
        success: function(json){
            $.each(json, function(idx, obj) { 
            var customer_fname = typeof obj.first_name != "undefined" ? obj.first_name : "";
            var customer_lname = typeof obj.last_name != "undefined" ? obj.last_name : "";
            var d = obj.sale_time.split("-");   
            var date = d[2].split(" ",1)+'/'+d[1]+'/'+d[0]; //   11/10/2010       

            var html = "<tr>";
                html += "<td>"+obj.order_id+"</td>";
                html += "<td>"+date+"</td>";
                html += "<td>"+customer_fname+" "+customer_lname.toUpperCase()+"</td>";
                html += "<td>"+obj.comment+"</td>";

                html += "<td width=20>";
                html += "<a href='"+baseURL+controller+"/unsuspend/"+office_number+"/"+obj.order_id+"' class='btn btn-primary btn-sm'>Unsuspend</a>";
                html += "</td>";

                html += "<td width=20>";
                html += "<form action='"+controller+"/receipt/"+office_number+"/"+obj.order_id+"' method='post' id='form_receipt_suspended_sale'>";
                html += "<input type='hidden' value='"+obj.order_id+"' name='suspended_sale_id' />";
                html += "<input type='submit' value='Receipt' name='receipt' id='submit_receipt' class='btn btn-primary btn-sm' />";
                html += "</form>";
                html += "</td>";

                html += "<td width=20>";
                html += "<form action='"+controller+"/delete_suspended_sale' method='post' id='form_delete_suspended_sale'>";
                html += "<input type='hidden' value='"+obj.order_id+"' name='suspended_sale_id' />";
                html += "<input type='submit' value='Delete' name='delete_suspended' id='submit_delete' class='btn btn-primary btn-sm' />";
                html += "</form>";
                html += "</td>";
                html += "</tr>";
                data += html;
            });
            if (callback) {
             callback(data);
            }      
        }
    });
}

jQuery("body").on('click', "input[name='delete_suspended']", function(event){
    event.preventDefault();
    var action = jQuery(this).parents("form#form_delete_suspended_sale").attr("action");
    var data = jQuery(this).parents("form#form_delete_suspended_sale").serialize();
//    var sale_id = jQuery("input[name='suspended_sale_id']").val();
    var sale_id = jQuery(this).closest("input[name='suspended_sale_id']").val();
    jQuery.ajax({
        type: "POST",
        url: baseURL + action,
//        data: {"suspended_sale_id" : sale_id},
        data: data,
        success: function(data){
            jQuery("#suspended").modal('hide');
        }
    });
    return false;
});

// Unsuspended
// jQuery("body").on("click", "input#submit_unsuspend", function(){
//     var action = jQuery("form#form_unsuspended_sale").attr("action");
//     var sale_id = jQuery(this).prev("input").val();
//     jQuery.ajax({
//         type: "POST",
//         url: baseURL + action,
//         data: {"suspended_sale_id": sale_id},
//         success: function(datas) {
//             $("div#register_container").html(datas);
//             jQuery("#suspended").modal('hide');        }
//     });
//     return false;
// });

/* Hide div of change date of sale */
// /* Change sale date enable */
if( jQuery('input#change_sale_date_enable').is(':checked')) {
    jQuery("#change_sale_input").show();
} else {
    jQuery("#change_sale_input").hide();
}

jQuery("body").on("click", "#change_sale_date_enable", function(){
    var new_change;
    var change_sale_date_enable = jQuery("input[name='change_sale_date_enable']");
    if (change_sale_date_enable.val() == 1) {
        change_sale_date_enable.val(0);
    } else {
        change_sale_date_enable.val(1);
    }
    new_change = change_sale_date_enable.val();
    jQuery.ajax({
        type: "POST",
        dataType: "html",
        url: baseURL + controller +"/set_change_sale_date_enable",
        data: {"change_sale_date_enable" : new_change},
        success: function(response){
        }
    });

    /* Change sale date enable */
    if( jQuery('input#change_sale_date_enable').is(':checked')) {
        jQuery("#change_sale_input").show();
    } else {
        jQuery("#change_sale_input").hide();
    }

});
/* End of change sale date enable */

/* Set change date on sale */
jQuery("body").on("change", "input#change_sale_date", function(){
    var url = baseURL + controller + "/set_change_sale_date";
    var change_sale_date = jQuery("input#change_sale_date").val();
    $.ajax({
        type: "POST",
        url: url,
        data: { "change_sale_date": change_sale_date},
        success: function(){

        }
    });
});


/* Set comment on receipt */
jQuery("body").on("click", "input#show_comment_on_receipt", function(){
    var new_change;
    var show_comment_on_receipt = jQuery("input[name='show_comment_on_receipt']");
    if (show_comment_on_receipt.val() == 1) {
        show_comment_on_receipt.val(0);
    } else {
        show_comment_on_receipt.val(1);
    }
    new_change = show_comment_on_receipt.val();
    jQuery.ajax({
        type: "POST",
        dataType: "html",
        url: baseURL + controller +"/set_comment_on_receipt",
        data: {"show_comment_on_receipt" : new_change},
        success: function(response){
        }
    }); 
});
/* End of set commnet on receipt */

// Set comment for store of sale table and on receipt
jQuery("body").on("change", "textarea[name='comment']", function(){
    var comment_sale = jQuery(this).val();
    var url = baseURL + controller +"/set_comment";
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'html',
        data: { "comment":comment_sale },
        success: function(){
            
        }
    });
});

// Date 
$('.date-pick').datePicker();


// Complete Sale
jQuery("body").on("click", "input[name='finish_sale_button']", function(){
    var finish_sale = "Are you sure you want to submit this sale? This cannot be undone.";
    if (!confirm(finish_sale)) {
        return false;
    }

});

// Edit item
jQuery("body").on("change", "form.line_item_form", function(){
    var url = jQuery(this).attr("action");
    var data = jQuery(this).serialize();
    submitFormEditItem(data, url);
    /*jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "html",
        data: jQuery(this).serialize(),
        success: function(response){
            jQuery("div#register_container").html(response);            
        }
    });*/
});

// Delete Item
jQuery("body").on("click", "a.delete_item", function(){
    var url = jQuery(this).attr("href");
    jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "html",
        success: function(response){
            jQuery("div#register_container").html(response);
        }
    });
        return false;
});

// Add new ticket on Sale 
jQuery("body").on("click", "input[name='btn_submit_tickets']", function(event){
    event.preventDefault();
    var check_duplicate =  baseURL + "tickets/check_duplicate_data";
    var saveAction =  baseURL + "tickets/saved/-1";
    var addAction = baseURL + "tickets/add";
    var ticket_name = jQuery("input[name='ticket_name']").val();
    var destinationID = jQuery("select[name='destinationID']").val();
    var ticket_typeID = jQuery("select[name='ticket_typeID']").val();
    var input_ticket_type = jQuery("select[name='input_ticket_type']").val();
    var ticket_id = $("input[name='ticket_id']").val();

    if (ticket_name == "" || destinationID == "" || (ticket_typeID == 0 && input_ticket_type == "") ) {
        jQuery('div#getSmsError').text('Please fill all the fields!');
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
        return false;
    };


/*if (customer_id == "") {
            jQuery.ajax({
                type: "POST",
                url: check_duplicate,
                dataType: "json",
                data: jQuery('form#customer_frm').serialize(),
                success: function(msg) {
                    if (msg.duplicate) {
                        var duplicating = "A similar customer name already exists. Do you want to continue?";
                        if (!confirm(duplicating)) {
                            return false;
                        };
                        saveCustomer(saveAction, selectAction);
                    } else {
                        saveCustomer(saveAction, selectAction);
                    }
                }
            });
        } else {
            saveCustomer(saveAction, selectAction);
        }*/

    if (ticket_id == "") {
        check_duplicate_destination(function(result) {
            if (result.duplicate) {
                    jQuery('div#getSmsError').text('This destination has duplicate!');
                    jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
                    return false;
            } else {
                jQuery.ajax({
                    type: "POST",
                    url: check_duplicate,
                    dataType: "json",
                    data: jQuery('form#ticket_form').serialize(),
                    success: function(msg) {
                        if (msg.duplicate) {
                            var duplicating = "A similar ticket code already exists. Do you want to continue?";
                            if (!confirm(duplicating)) {
                                return false;
                            };
                            saveTicket(saveAction, addAction);
                        } else {
                            saveTicket(saveAction, addAction);
                        }
                    }
                });
            }
        });
    } else {
        saveTicket(saveAction, addAction);
    }


    /*check_duplicate_destination(function(result) {
        if (result.duplicate) {
                jQuery('div#getSmsError').text('This destination has duplicate!');
                jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
                return false;
        } else {
            jQuery.ajax({
                type: "POST",
                url: check_duplicate,
                dataType: "json",
                data: jQuery('form#ticket_form').serialize(),
                success: function(msg) {
                    if (msg.duplicate) {
                        var duplicating = "A similar ticket code already exists. Do you want to continue?";
                        if (!confirm(duplicating)) {
                            return false;
                        };
                        saveTicket(saveAction, addAction);
                    } else {
                        saveTicket(saveAction, addAction);
                    }
                }
            });
        }
    });*/ 
});


/* Save new ticket */
function saveTicket(saveAction, addAction) {
    $.ajax({
        type: "POST",
        url: saveAction,
        dataType: "json",
        data: $("form#ticket_form").serialize(),
        success: function(response) {
            addToCart(response.ticket_id, addAction);
        }
    });
}

// Add ticket to cart
function addToCart(ticket_id, addAction) {
    $.ajax({
        type: "POST",
        url: addAction,
        dataType: "html",
        data: { "item": ticket_id},
        success: function(response) {
            $("div#register_container").html(response);
            jQuery("#tickets").modal('hide');
        }
    });
}

/* Commissioner Action */

// Add new commissioner
jQuery('body').on('click', 'input#submit_commissioners', function(event){
    event.preventDefault();
    var url = jQuery("form#commissioners_form").attr('action');
    var checkExistURL = baseURL + "commissioners/check_duplicate";
    var datas = jQuery("form#commissioners_form").serialize();
//    var commis_id = url.substr(url.lastIndexOf('/') + 1);   // Get last url segment
     var commis_id = jQuery("input[name='commis_id']").val();
     var url_select = baseURL+controller+"/select_commissioner";

    // if (isNaN(commis_id)) {
    if (!commis_id) {
        jQuery.ajax({
            type: "POST",
            url: checkExistURL,
            dataType: "json",
            data: datas,
            success: function(json) {
                if (json.duplicate) {
                    jQuery('div#getSmsError').text("This record is duplicated!");
                    jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400).fadeOut(400);
                    return false;
                }
                addPeople(url, url_select, datas, 'commissioners');
            }
        });    
    } else {
        addPeople(url, url_select, datas, 'commissioners');
    }
    
});

/*function addCommmissioner(url, datas) {
    $.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
        data: datas,
        success: function(response) {
            selectCommissioner(response.commissioner_id);
        }
    });
}

function selectCommissioner(commissioner_id) {
    var url = baseURL + controller + "/select_commissioner";
    jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "html",
        data: { "term" : commissioner_id },
        success: function(response) {
            $("div#register_container").html(response);
            jQuery("#commissioners").modal('hide');
        }
    });
}*/

jQuery("body").on("click", "a#delete_commissioner", function(event){
    event.preventDefault();
    var url = jQuery(this).attr("href");
    jQuery.ajax({
        url: url,
        dataType: "html",
        success: function(response) {
            $("div#register_container").html(response);
        }
    });
    return false;
});

// Search autocompleted on sale
$('body').on('keypress','input#commissioner, input#massager',function(event){
    var current_obj = $(this);
    if (event.keyCode === $.ui.keyCode.ENTER) {
        return false;
    }
    var term = current_obj.val();
    var url = current_obj.parents("form").attr("action");
    if (current_obj.attr('id')=='massager') {
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
                    getData(ui.item.value, url);
                }
            });
        }
    });
 });
/*$('body').on('keypress','input#commissioner',function(event){
    if (event.keyCode === $.ui.keyCode.ENTER) {
        return false;
    }
    var term = jQuery(this).val();
    var url = jQuery("form#select_commissioner_form").attr("action");
    var suggest = baseURL + "commissioners/commissioner_search";
    $.ajax({
        type: "POST",
        url: suggest,
        dataType: "json",
        data: {"term" : term},
        success: function(data){
            $("input#commissioner").autocomplete({
                source: data,
                select: function(e, ui) {
                    getData(ui.item.value, url);
                }
            });
        }
    });
 });*/

$('body').on('change', 'input#commissioner_price', function(event) {  
    var term = jQuery(this).val();
    var url = jQuery("form#price_commissioner_form").attr("action");
    jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "html",
        data: {"commissioner_price" : term},
        success: function(data){
            $("div#register_container").html(data);
        }
    });
});

$('body').on('click','a#sale_edit_commissioners',function(event){
    event.preventDefault();
    var url = jQuery(this).attr("href").replace("#", "");
    var uid = url.substr(url.lastIndexOf('/') + 1);
    $.ajax({
        url: url,
        dataType: "json",
        success: function(data){
            var action = jQuery("form#commissioners_form").attr("action");
            jQuery("form#commissioners_form").attr("action", action+"/"+data.commisioner_id);

            jQuery("input[name='first_name']").val(data.first_name);
            jQuery("input[name='last_name']").val(data.last_name);
            jQuery("input[name='phone_number']").val(data.tel);
        }
    });
}); 

$('body').on('change', 'input#deposit_price', function(event) {  
    var term = jQuery(this).val();
    var url = jQuery("form#price_deposit_form").attr("action");
    jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "html",
        data: {"deposit_price" : term},
        success: function(data){
            $("div#register_container").html(data);
        }
    });
});
/* End of Commissioner Action */

$('body').on('change.bfhdatepicker', '#date_departure', function(event) {
    event.preventDefault();
    var url = $(this).closest("form").attr("action");
    var data = $(this).closest("form").serialize();
    editItem(url, data);
});

$('body').on('change.bfhtimepicker', '#times', function(event) {
    event.preventDefault();
    var url = $(this).closest("form").attr("action");
    var data = $(this).closest("form").serialize();
    editItem(url, data);
});

function editItem(url, data) {
    jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "html",
        data: data,
        success: function(response){
            jQuery("div#register_container").html(response); 
        }
    });
}

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

// Autocomplete for supplier hotel name and submit when select event
$('body').on('keypress','input.txt_hotel',function(event){
        if (event.keyCode === $.ui.keyCode.ENTER) {
            return false;
        }
        var term = jQuery(this).val(); 
        var data = jQuery(this).parents("form.line_item_form").serialize();
        var urlEdit = jQuery(this).parents("form.line_item_form").attr("action");
        var url = baseURL + "suppliers/filter_hotels";
        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            data: {"term" : term},
            success: function(response){
                $("input.txt_hotel").autocomplete({
                  source: response,
                  select: function(e, ui) {
                    $("input.txt_hotel").val(ui.item.value);
                    addHotelName(jQuery(this).parents("form.line_item_form").serialize(), urlEdit);
                  }
                });
            }
        });
    });

$('body').on('change','input.txt_hotel',function(event){
    event.preventDefault();
    if (event.keyCode === $.ui.keyCode.ENTER) {
        return false;
    }
    var term = jQuery(this).val(); 
    var urlEdit = jQuery(this).parents("form.line_item_form").attr("action");
    var url = baseURL + "suppliers/save/-1/";
    var check_hotel = url.replace("save", "check_hotel");
    $.ajax({
        type: "POST",
        url: check_hotel,
        dataType: "json",
        data: {"company_name" : term},
        success: function(response) {
            if (response.duplicate) {
                var duplicating = "A similar customer name already exists.";
                set_feedback(duplicating,'error_message',false); 
            } else {
                addHotelName(jQuery(this).parents("form.line_item_form").serialize(), urlEdit);
            }
        }
    });

    
});

function addHotelName(data, url) {
    $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        data: data,
        success: function(response){

        }
    });
}

function submitFormEditItem(data, url) {
    jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "html",
        data: data,
        success: function(response){
            jQuery("div#register_container").html(response);            
        }
    });
}

$("body").on("change", "input#destinationID, input#newDes", function(event) {
    var url = baseURL + "tickets/check_duplicate_destination";
    var term = $(this).val();
    check_duplicate_destination(function(result) {
        if (result.duplicate) {
                jQuery('div#getSmsError').text('This destination has duplicate!');
                jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
                return false;
            };
    });
    event.preventDefault();
});
