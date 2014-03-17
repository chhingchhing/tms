jQuery(document).ready(function(){
    jQuery("th").removeAttr("style");

    var baseURL = jQuery('input[name="baseURL"]').val();
    var office_number = jQuery('input[name="office_number"]').val();
    var controller = jQuery('input[name="controller_name"]').val();

    /* check all */
    if (jQuery('input#select_all')) {
        jQuery('input#select_all').click(function() {
            if (this.checked === false) {
                jQuery('input.check_value:checked').attr('checked', false);
            } else {
                jQuery('input.check_value:not(:checked)').attr('checked', true);
            }
        });
    }

    /* Adding new supplier and edit supplier */
    jQuery("div#success").hide();
    jQuery("div#error").hide();
    jQuery("div#feedback_bar").hide();
    jQuery("div#feedback_bar_error").hide();

    jQuery("input[name='btnSubmitSupplier']").on("click", function(){
        var data = jQuery('form.supplier_frm').serialize();
        // var saveAction = baseURL + "suppliers/saved";
        var url = jQuery("form#supplier_frm").attr("action");
        var account_number = jQuery("input#account_number").val();
        var company_name = jQuery("input#company_name_input").val();
        var first_name = jQuery("input#first_name").val();
        var last_name = jQuery("input#last_name").val();
        var email = jQuery("input#email").val();
        var phone_number = jQuery('input#phone_number').val();
        var address_1 = jQuery("input#address_1").val();
        var address_2 = jQuery("input#address_2").val();
        var city = jQuery("input#city").val();
        var state = jQuery("input#state").val();
        var zip = jQuery("input#zip").val();
        var country = jQuery("input#country").val();
        var comments = jQuery("input#comments").val();
        var mailing_lists = jQuery("input#mailing_lists").val();
        if (company_name=="" || first_name=="" || last_name=="") {
            jQuery('div#getSmsError').text('Please fill all the fields!');
            jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
            return false;
        };
    var search = url.replace("save", "search");
        jQuery.ajax({
            type: "POST",
            url: url,
            dataType: " json",
            data: {"account_number":account_number, "company_name":company_name, "first_name":first_name, "last_name":last_name,
            "email":email, "phone_number":phone_number, "address_1":address_1, "address_2":address_2,
            "city":city, "state":state, "zip":zip, "country":country, "comments":comments, "mailing_lists": mailing_lists},
            success: function(response) {
                if(response.success)
                {
                    $("#suppliers").modal('hide');
                    $('.modal').on('hidden.bs.modal', function(){
                        $(this).find('form')[0].reset();
                    });
                    set_feedback(response.message,'success_message',false);
                    getDataSearch("", search);
                }
                else
                {
                    set_feedback(response.message,'error_message',false);    
                }
            }
        });
    });

    /* Add new and edit customer */
    jQuery("body").on("click", "input[name='btncustomersCustomer']", function(event){
        event.preventDefault();
        var customer_id = jQuery('input[name="customer_id"]').val();
        var saveAction = jQuery("#customer_frm").attr("action");
        var first_name = jQuery("input#first_name").val();
        var last_name = jQuery("input#last_name").val();
        if (first_name=="" || last_name=="") {
            jQuery('div#getSmsError').text('Please fill all the fields!');
            jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
            return false;
        };
        var urlCheckDuplicate = saveAction.replace("save", "check_duplicate");
        var data = jQuery('form#customer_frm').serialize();
        if (customer_id == '') {
            jQuery.ajax({
                type: "POST",
                url: urlCheckDuplicate,
                dataType: "json",
                data: data,
                success: function(msg) {
                    if (msg.duplicate) {
                        jQuery('div#getSmsError').text("Duplicate customer's name, Please check the firstname and lastname!");
                        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
                        return false;
                    } else {
                        save(data, saveAction, "customers");
                    }
                }
            });
        } else {
            save(data, saveAction, "customers");
        }
    });

    /* Clean Up Old Customers that deleted */
    jQuery("a.cleanup").on("click", function(event){
        event.preventDefault();
        var action = jQuery(this).attr("href");
        var confirming = "Are you sure you want to clean ALL deleted "+controller+"? (This will remove account numbers from deleted "+controller+" so they can be reused)";
        if (!confirm(confirming)) {
            return false;
        };
        jQuery.ajax({
            type: "POST",
            url: action,
            dataType: "json",
            success: function(response){
                if (response.success) {
                    set_feedback(response.message,'success_message',false);
                }
            }
        });
    });

/* Close message error and success */
    jQuery("body").on("click", "span.cross", function(){
        jQuery("div#success").fadeOut();
        jQuery("div#error").fadeOut();
    });

    jQuery("body").on("click", ".crossing", function(){
        jQuery("div#feedback_bar").fadeOut();
        jQuery("div#error").fadeOut();
        jQuery("span#getSmsError").fadeOut();
    });

    /* Delete record of manage */
    //Button for remove data as permanent
    jQuery("a#delete").click(function(event) {
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
                data: {"checkedID": rcdsChecked},
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

    /* Click edit link and retrieve data into form for edit */
    $('body').on('click','a.edit',function(){
         var url = jQuery(this).attr("href").replace("#", "");
            var uid = url.substr(url.lastIndexOf('/') + 1);
            $.ajax({
                url: url,
                dataType: "json",
                success: function(data){
                    var action = jQuery("form.form-horizontal").attr("action");
                    jQuery("form.form-horizontal").attr("action", action+"/"+uid);

                    jQuery("input[name='customer_id']").val(data.customer_id);
                    jQuery("input[name='company_name']").val(data.company_name);
                    jQuery("input[name='account_number']").val(data.account_number);
                    jQuery("input[name='first_name']").val(data.first_name);
                    jQuery("input[name='last_name']").val(data.last_name);
                    jQuery("input[name='email']").val(data.email);
                    jQuery("input[name='phone_number']").val(data.phone_number);
                    jQuery("input[name='address_1']").val(data.address_1);
                    jQuery("input[name='address_2']").val(data.address_2);
                    jQuery("input[name='city']").val(data.city);
                    jQuery("input[name='state']").val(data.state);
                    jQuery("input[name='zip']").val(data.zip);
                    jQuery("input[name='country']").val(data.country);
                    jQuery("textarea[name='comments']").val(data.comments);
                    jQuery("input[name='hotel_name']").val(data.hotel_name);
                    jQuery("input[name='room_number']").val(data.room_number);
                }
            });
    }); 

    /* Click edit link and retrieve data into form for edit massages */
    $('body').on('click','a.edit_sms',function(event){
        event.preventDefault();
        var url = jQuery(this).attr('href').replace("#", "");
        $.ajax({
            url: url,
            dataType: "html",
            success: function(data){
                $("#massages").html(data);
                $("#massages").modal("show");
            }
        });
    });
    
    
    /* Click edit link and retrieve data into form for edit bike */
    $('body').on('click','a.edit_bike',function(){    
        var url = jQuery(this).attr('href').replace("#", ""); 
        $.ajax({
            url: url,
            dataType: "json",
            success: function(data) {
                jQuery("input[name='item_bike_id']").val(data.item_bike_id);
                jQuery("input[name='bike_code']").val(data.bike_code);
                jQuery("input[name='unit_price']").val(data.unit_price);
                jQuery("input[name='actual_price']").val(data.actual_price);
                jQuery("select[name='bike_types']").val(data.bike_types);
            }
        });
    });
    
     /* Click edit link and retrieve data into form for edit tours */
    $('body').on('click','a.edit_tour',function(){ 
        var url = jQuery(this).attr('href').replace("#", ""); 
        $.ajax({
            url: url,
            dataType: "html",
            success: function(data) {
                $("#tours").html(data);
                $("#tours").modal("show");
            }
        });
    });

});
/* End of function on ready() */

/* Enable Credit Card Processing: on Config */
$("#enable_credit_card_processing").change(check_enable_credit_card_processing).ready(check_enable_credit_card_processing);
jQuery("#enable_credit_card_processing").change(function(){
    if (this.checked === true) {
        jQuery('input#enable_credit_card_processing:checked').attr('checked', true);
        $("#merchant_information").show();
    } else {
        jQuery('input#enable_credit_card_processing:not(:checked)').attr('checked', false);
        $("#merchant_information").hide();
    }
});

jQuery("body").on("keypress","input#search", function(event){
    if (event.keyCode === $.ui.keyCode.ENTER) {
        return false;
    }
    var term = jQuery(this).val();
    var url = jQuery("form#search_form").attr("action");
//    alert(url);
    var suggest = url.replace("search", "suggest");
    jQuery.ajax({
        type: "POST",
        url: suggest,
        dataType: "json",
        data: {"term" : term},
        success: function(data){
            $( "#search" ).autocomplete({
              source: data,
              select: function(e, ui){
                getDataSearch(ui.item.label, url);
              }
            });
        }
    });
});

function getDataSearch(term, url){
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
}

//validation and submit handling
function check_enable_credit_card_processing() {
    if($("#enable_credit_card_processing").attr('checked')) {
        $("#merchant_information").show();
    } else {
        $("#merchant_information").hide();
    }
    
}

/*
* Report
*/
jQuery("td.innertable").hide();

jQuery('body').on("click", "input[name='submitSaleEditForm']", function(event){
    event.preventDefault();
    var url = jQuery(this).closest("form").attr("action");
    var data = jQuery(this).closest("form").serialize();
    jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        data: data,
        success: function(response) {
            if(response.success)
            {
                set_feedback(response.message,'success_message',false);
            }
            else
            {
                set_feedback(response.message,'error_message',false);    
                
            }
        }
    });
});

function set_feedback(text, classname, keep_displayed)
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
    
}

jQuery("body").on("click", "input[name='submitDeleteEntireSale']", function(){
    var delete_entire_sale = "Are you sure you want to delete this sale, this action cannot be undone.";
    if (!confirm(delete_entire_sale)) {
        return false;
    }
});

jQuery("body").on("click", "input[name='submitUndeleteSale']", function(){
    var undelete_sale = "Are you sure you want to undelete this sale, this action cannot be undone";
    if (!confirm(undelete_sale)) {
        return false;
    }
});

// Print reciept
jQuery("body").on("click", "button#print", function() {
    var headElements = '<meta charset="utf-8" />,<meta http-equiv="X-UA-Compatible" content="IE=edge"/>';
    var keepAttr = ['class','id','style'];
    var options = { 
        mode : "iframe", 
        retainAttr : keepAttr,
        extraHead : headElements 
    };
    $( "div#print_receipt" ).printArea( options );
});

// Search on sale (Sale)
jQuery("body").on("keypress", "td.value input", function() {
    var fullUrl = window.location.pathname;
    var path = fullUrl.split("/");
    var w = jQuery("td.value input").attr("w");
    var url = baseURL + "reports/"+path[3]+"?act=autocomplete&w="+w+"&term="+this.value;
    if (w != "") {
        jQuery.ajax({
            type: "get",
            url: url,
            dataType: "json",
            success: function(response) {
                jQuery("td.value input").tokenInput(response);
            }
        });
    };
});

// Employee Add
jQuery("body").on("click", "input#submitEmployee", function(event) {
    var person_id = jQuery("input[name='person_id']").val();
    var first_name = jQuery("input[name='first_name']").val();
    var last_name = jQuery("input#last_name").val();
    var username = jQuery("input#username").val();
    var password = jQuery("input#password").val();
    var repeat_password = jQuery("input#repeat_password").val();
    var position = jQuery("select[name='position']").val();

    // if (first_name=="" || last_name=="" || username=="") {
    //     set_messages_error("Please fill all the required fields.");
    //     return false;
    // }
    if (person_id == "") {
        if (password.length < 8) {
            alert("hi");
            set_messages_error("Passwords must be at least 8 characters.");
            document.getElementById("password").style.borderColor = "#E34234";
            return false;
        }
        if (password != repeat_password) {
            set_messages_error("Passwords do not match");
            document.getElementById("password").style.borderColor = "#E34234";
            document.getElementById("repeat_password").style.borderColor = "#E34234";
            return false;
        }
    }

    var url = jQuery(this).parents("form#employee_form").attr("action");
    var data = jQuery("form#employee_form").serialize();
    var check_duplicate_url = url.replace("save", "check_duplicate");
    if (person_id == '') {
        jQuery.ajax({
            type: "POST",
            url: check_duplicate_url,
            dataType: "json",
            data: data,
            success: function(response){
                if (response.duplicate) {
                    jQuery('div#getSmsError').text("Duplicate employee's name, Please check the firstname and lastname!");
                    jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
                    return false;
                } else {
                    save(data, url, "employees");
                }
            }
        });
    } else {
        save(data, url, "employees");
    }
    event.preventDefault();
});

function save(data, url, modal_id) {
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
    }

// Check parents checkbox for auto checked for children checkbox
jQuery("body").on("change", "input.module_checkboxes", function() {
    if (this.checked) {
        $(this).closest("ul li").find("input").prop("checked", true);
    }else if (!this.checked) {
        $(this).closest("ul li").find("input").prop("checked", false);
    }
});

// Edit employee, retrieve data into partial view form of modal bootstrap
$('body').on('click','a.edit_emp',function(event){
    event.preventDefault();
    var url = jQuery(this).attr("href").replace("#", "");
    var uid = url.substr(url.lastIndexOf('/') + 1);
    $.ajax({
        url: url,
        dataType: "html",
        success: function(data){
            $("#employees").html(data);
            $("#employees").modal("show");
        }
    });
});

// For reset modal form of bootstrap
$('body').on('click','a.add_new',function(event){
    $(".modal").on("show", function() { 
     $(this).find('form')[0].reset();
     });
    event.preventDefault();
});

// Check username duplicate
$('body').on("change", "input[name='username']", function(event) {
    event.preventDefault();
    var data = jQuery("form#employee_form").serialize();
    var url = jQuery(this).parents("form#employee_form").attr("action");
    var employee_exists = url.replace("save", "employee_exists");
    $.ajax({
        type: "POST",
        dataType: "html",
        url: employee_exists,
        data: data,
        success: function(response) {
            if (response == "false") {
                jQuery('div#getSmsError').text('The username already exists.');
                jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
                return false;
            };
        }
    });
});

// Check length of password
$('body').on("change", "input[name='password']", function(event) {
    event.preventDefault();
    var password = $("input[name='password']").val();
    if (password.length < 8) {
        set_messages_error("Passwords must be at least 8 characters.");
        document.getElementById("password").style.borderColor = "#E34234";
        return false;
    }
});

// Check and compare string of password and confirm password
$('body').on("change", "input[name='repeat_password']", function(event) {
    event.preventDefault();
    var password = $("input[name='password']").val();
    var repeat_password = $("input[name='repeat_password']").val();
    if (password != repeat_password) {
        set_messages_error("Passwords do not match");
        document.getElementById("password").style.borderColor = "#E34234";
        document.getElementById("repeat_password").style.borderColor = "#E34234";
        return false;
    }
});

// set message error
function set_messages_error(message) {
    jQuery('div#getSmsError').text(message);
    jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
}

// $("body").on("click", "a.add_new", function(event) {
//     event.preventDefault();
//     var controller = $(this).attr("data-target");
//     alert(controller.replace("#",""));
//     var url = baseURL +controller.replace("#","")+ "/view";
//     $.ajax({
//         url: url,
//         dataType: "html",
//         success: function(data) {
//             $("#employees").html(data);
//             $("#employees").modal("show");
//         }
//     });
// });