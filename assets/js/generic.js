jQuery(document).ready(function(){
    jQuery("th").removeAttr("style");

    var baseURL = jQuery('input[name="baseURL"]').val();
    var office_number = jQuery('input[name="office_number"]').val();
    var controller = jQuery('input[name="controller_name"]').val();

    /* Adding new supplier and edit supplier */
    jQuery("div#success").hide();
    jQuery("div#error").hide();
    jQuery("div#feedback_bar").hide();
    jQuery("div#feedback_bar_error").hide();

    // jQuery("input[name='btnSubmitSupplier']").on("click", function(){
    $("body").on("click", "input[name='btnSubmitSupplier']", function(event) {
        event.preventDefault();
        var data = jQuery('form.supplier_frm').serialize();
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
        var supplier_type_id = jQuery("select[name='supplier_type']").val();
        var new_supplier = jQuery("input[name='new_supplier_type']").val();
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
            data: {"new_supplier_type":new_supplier,"supplier_type":supplier_type_id,"account_number":account_number, "company_name":company_name, "first_name":first_name, "last_name":last_name,
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

    /* Add new and edit customer for module customer management */ 
    jQuery("body").on("click", "input#btnSubmitClient", function(event){
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
    $('body').on('click','a.edit',function(event){
        event.preventDefault();
        var modals = jQuery(this).attr("modals");
        var url = jQuery(this).attr("href").replace("#", "");
        var uid = url.substr(url.lastIndexOf('/') + 1);
        $.ajax({
            url: url,
            dataType: "html",
            success: function(data){
                $("#"+modals).html(data);
                $("#"+modals).modal("show");
            }
        });
    });

    /* Click edit link and retrieve data into form for edit */
    $('body').on('click','a.edit_supplier',function(event){
        event.preventDefault();
        var url = jQuery(this).attr("href").replace("#", "");
        var uid = url.substr(url.lastIndexOf('/') + 1);
        $.ajax({
            url: url,
            dataType: "html",
            success: function(data){
                $("#suppliers").html(data);
                $("#suppliers").modal("show");
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

jQuery("body").on("change","input#search", function(event){
    event.preventDefault();

    var term = jQuery(this).val();
    var url = jQuery("form#search_form").attr("action");
    var suggest = url.replace("search", "suggest");
    if (term == "") {
        getDataSearch('', url);
    };
});

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
jQuery("body").on("click", "button#print, button#bnt_print", function() {
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
/*jQuery("body").on("keypress", "td.value input", function() {
    var fullUrl = window.location.pathname;
    var path = fullUrl.split("/");
    var w = jQuery("td.value input.w").attr("w");
    var url = baseURL + "reports/"+path[3]+"?act=autocomplete&w="+w+"&term="+this.value;
    if (w != "") {
        jQuery.ajax({
            type: "get",
            url: url,
            dataType: "json",
            success: function(response) {
                jQuery("td.value input").tokenInput(response, 
                    {
                    theme: "facebook",
                    queryParam: "term",
                    extraParam: "w",
                    hintText: <?php echo json_encode(lang("reports_sales_generator_autocomplete_hintText"));?>,
                    noResultsText: <?php echo json_encode(lang("reports_sales_generator_autocomplete_noResultsText"));?>,
                    searchingText: <?php echo json_encode(lang("reports_sales_generator_autocomplete_searchingText"));?>,
                    preventDuplicates: true,
                    prePopulate: settings.prePopulate
                });
            }
        });
    };
});*/

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


// Initailize the form of search sale of tokenInput js
/*$('body').on("change", "select.selectField", function() {
    if ($('td.value input').attr('w')) {
        $('td.value input').tokenInput();
    };
});*/

// Config
/*$("body").on("click", "input[name='submitf']", function(event) {
    var url = $("form#config_form").attr("action");
    event.preventDefault();
    $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        data: $("form#config_form").serialize(),
        success: function(response) {
            console.log(response);
            if (response.success) {
                set_feedback(response.message,'success_message',false);
            } else {
                set_feedback(response.message,'error_message',false);
            }
        }
    });
});*/

/* For create new currency rate */
    $('body').on('click','a#_new',function(event){
        var modals = jQuery(this).attr("modals");
        var url = jQuery(this).attr("href").replace("#", "");
        $.ajax({
            url: url,
            dataType: "html",
            success: function(data){
                $("#"+modals).html(data);
                $("#"+modals).modal("show");
            }
        });
        event.preventDefault();
    });

/* Create currency on Currency management */
 $("body").on("click", "input[name='submit_currency']", function(event){
        event.preventDefault();
        var currency_id = jQuery('input[name="currency_id"]').val();
        var url = jQuery("#currency_form").attr("action");
        var currency_value = jQuery("input#currency_value").val();
        var currency_type_name = jQuery("input#currency_type_name").val();
        if (currency_value=="" || currency_type_name=="") {
            jQuery('div#getSmsError').text('Please fill all the fields!');
            jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
            return false;
        };
        var urlCheckDuplicate = url.replace("save", "check_duplicate");
        var data = jQuery('form#currency_form').serialize();
        if (currency_id == '') {
            jQuery.ajax({
                type: "POST",
                url: urlCheckDuplicate,
                dataType: "json",
                data: data,
                success: function(msg) {
                    if (msg.duplicate) {
                        jQuery('div#getSmsError').text("Duplicate currency data, Please check the currency name!");
                        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
                        return false;
                    } else {
                        save(data, url, "currency");
                    }
                }
            });
        } else {
            save(data, url, "currency");
        }
    });
    /* End of create currency on currency management */

/* Starting The Generally of Package Tour */
/* Click create new link and display the form pop up modal */
    $('body').on('click','a.new',function(event){
        event.preventDefault();
        var modals = jQuery(this).attr("modals");
        var url = jQuery(this).attr("href").replace("#", "");
        var uid = url.substr(url.lastIndexOf('/') + 1);
        $.ajax({
            url: url,
            dataType: "html",
            success: function(data){
                $("#"+modals).html(data);
                $("#"+modals).modal("show");
            }
        });
    });




    jQuery("body").on("keypress","input#item", function(event){
        if (event.keyCode === $.ui.keyCode.ENTER) {
            return false;
        }
        var term = jQuery(this).val();
        var url = baseURL + controller + "/item_search";
        jQuery.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            data: {"term" : term},
            success: function(data){
                $( "#item" ).autocomplete({
                  source: data,
                  select: function(e, ui){
                    // getDataSearch(ui.item.label, url);
                    $( "#item" ).val("");
                    if ($("#item_kit_item_"+ui.item.value).length ==1)
                    {
                        $("#item_kit_item_"+ui.item.value).val(parseFloat($("#item_kit_item_"+ui.item.value).val()) + 1);
                    }
                    else
                    {
                        $("#item_kit_items").append("<tr><td><a href='#' onclick='return deleteItemKitRow(this);'>X</a></td><td>"+ui.item.label+"</td><td><input class='quantity' onchange='calculateSuggestedPrices();' id='item_kit_item_"+ui.item.value+"' type='text' size='3' name=item_kit_item["+ui.item.value+"] value='1'/></td></tr>");
                    }
                    
                    calculateSuggestedPrices();
                    
                    return false;
                  }
                });
            }
        });
    });


$("body").on("keypress", "input#item_mine", function(event) {
    if (event.keyCode === $.ui.keyCode.ENTER) {
        return false;
    }
    // event.preventDefault();
    var url = baseURL + controller + "/item_search";
    var term = $(this).val();
    jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        data: {"term" : term},
        success: function(data){
            $(this).autocomplete({
              source: data,
              select: function(e, ui){
                // getDataSearch(ui.item.label, url);
              }
            });
        }
    });


    $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        data: {"term":term},
        success: function(data) {
            console.log(data);
            $("#item").autocomplete({
              source: data,
              /*delay: 10,
              autoFocus: false,
              minLength: 0,*/
              select: function(event, ui){
                /*url = url.replace("item_search", "get_info")+"/"+ui.item.value;
                alert(url);
                addItemToPackage(url);*/
                $( "#item" ).val("");
                if ($("#item_kit_item_"+ui.item.value).length ==1)
                {
                    $("#item_kit_item_"+ui.item.value).val(parseFloat($("#item_kit_item_"+ui.item.value).val()) + 1);
                }
                else
                {
                    $("#item_kit_items").append("<tr><td><a href='#' onclick='return deleteItemKitRow(this);'>X</a></td><td>"+ui.item.label+"</td><td><input class='form-control input-sm quantity' onchange='calculateSuggestedPrices();' id='item_kit_item_"+ui.item.value+"' type='text' size='3' name=item_kit_item["+ui.item.value+"] value='1'/></td></tr>");
                }
                
                calculateSuggestedPrices();
                
                return false;
              }
            });
        }
    });


});

function calculateSuggestedPrices()
{
    var items = [];
    $("#item_kit_items").find('input').each(function(index, element)
    {
        var quantity = parseFloat($(element).val());
        var item_id = $(element).attr('id').substring($(element).attr('id').lastIndexOf('_') + 1);
        
        items.push({
            item_id: item_id,
            quantity: quantity
        });
    });
    calculateSuggestedPrices.totalCostOfItems = 0;
    calculateSuggestedPrices.totalPriceOfItems = 0;
    getPrices(items, 0);
}

function getPrices(items, index)
{
    if (index > items.length -1)
    {
        $("#unit_price").val(calculateSuggestedPrices.totalPriceOfItems);
        $("#cost_price").val(calculateSuggestedPrices.totalCostOfItems);
    }
    else
    {
        var site_url_get_info = baseURL + controller + "/get_info";
        // $.get('<?php echo site_url("items/get_info");?>'+'/'+items[index]['item_id'], {}, function(item_info)
        $.get(site_url_get_info +'/'+items[index]['item_id'], {}, function(item_info)
        {
            calculateSuggestedPrices.totalPriceOfItems+=items[index]['quantity'] * parseFloat(item_info.sale_price);
            calculateSuggestedPrices.totalCostOfItems+=items[index]['quantity'] * parseFloat(item_info.actual_price);
            getPrices(items, index+1);
        }, 'json');
    }
}

function deleteItemKitRow(link)
{
    $(link).parent().parent().remove();
    calculateSuggestedPrices();
    return false;
}

jQuery("body").on("click", 'input#btn_tours_package', function(event) {
    event.preventDefault();
    var url = $("form#item_kit_form").attr("action");
    var package_id = $("input[name='item_id']").val();
    var urlCheckDuplicate = url.replace("save_package", "check_duplicate_package");
    var data = jQuery('form#item_kit_form').serialize();
    if (package_id == '') {
        jQuery.ajax({
            type: "POST",
            url: urlCheckDuplicate,
            dataType: "json",
            data: { "term":$("input[name='name']").val() },
            success: function(msg) {
                if (msg.duplicate) {
                    jQuery('div#getSmsError').text("Duplicate data");
                    jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
                    return false;
                } else {
                    save_package(data, url, "tours_package");
                }
            }
        });
    } else {
        save_package(data, url, "tours_package");
    }
});

jQuery("body").on("keypress","input#search_package", function(event){
    if (event.keyCode === $.ui.keyCode.ENTER) {
        return false;
    }
    var term = jQuery(this).val();
    var url = jQuery("form#search_form").attr("action");
    var suggest = url.replace("search_package", "suggest_search_package");
    jQuery.ajax({
        type: "POST",
        url: suggest,
        dataType: "json",
        data: {"term" : term},
        success: function(data){
            $( "#search_package" ).autocomplete({
              source: data,
              select: function(e, ui){
                getDataSearch(ui.item.label, url);
              }
            });
        }
    });
});

/*function addItemToPackage (url) {
    // body...
}

$( "#item" ).autocomplete({
    source: '<?php echo site_url("tours/item_search"); ?>',
    delay: 10,
    autoFocus: false,
    minLength: 0,
    select: function( event, ui ) 
    {   
        $( "#item" ).val("");
        if ($("#item_kit_item_"+ui.item.value).length ==1)
        {
            $("#item_kit_item_"+ui.item.value).val(parseFloat($("#item_kit_item_"+ui.item.value).val()) + 1);
        }
        else
        {
            $("#item_kit_items").append("<tr><td><a href='#' onclick='return deleteItemKitRow(this);'>X</a></td><td>"+ui.item.label+"</td><td><input class='quantity' onchange='calculateSuggestedPrices();' id='item_kit_item_"+ui.item.value+"' type='text' size='3' name=item_kit_item["+ui.item.value+"] value='1'/></td></tr>");
        }
        
        calculateSuggestedPrices();
        
        return false;
    }
});*/

// Check all checkbox child
$(function () {
    $('th input[type="checkbox"]').click(function(){
        if ( $(this).is(':checked') )
            $('td input[type="checkbox"]').prop('checked', true);
        else
            $('td input[type="checkbox"]').prop('checked', false);
    })
});
