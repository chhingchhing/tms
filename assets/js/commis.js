/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var baseURL = jQuery('input[name="baseURL"]').val();
var controller = jQuery('input[name="controller_name"]').val();


// chen action commissioner
jQuery("a#delete_commis").click(function(event) {
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
   
//    /* Click edit link and retrieve data into form for edit */
  $('body').on('click','a#edit_commis',function(){    
     var url = jQuery(this).attr("href").replace("#", ""); //catch url when click on edit_guide
     var uid = url.substr(url.lastIndexOf('/') + 1);
    
    $.ajax({
        url: url,
        dataType: "json",        
        success: function(data){
        var action = jQuery("form.form-horizontal").attr("action");
        jQuery("form.form-horizontal").attr("action", action+"/"+uid);
                
            jQuery("input[name='commis_id']").val(data.commisioner_id);
            jQuery("input[name='first_name']").val(data.first_name);
            jQuery("input[name='last_name']").val(data.last_name);
            jQuery("input[name='phone_number']").val(data.tel);
           
        }
    }); 
  });

//add new commissioner in manage page
jQuery("body").on("click", "input#btnSubmitCommissioner", function(event) {
    event.preventDefault();
    var commis_id = jQuery("input[name='commis_id']").val();
    var checkExistURL = baseURL + "commissioners/check_duplicate";
    var addURL = jQuery("form#commissioners_form").attr("action")+"/"+commis_id;
    var data = jQuery("form#commissioners_form").serialize();

    var first_name = jQuery("input[name='first_name']").val();
    
    if (first_name == "") {
        jQuery('div#getSmsError').text("Please Input first name!");
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400).fadeOut(400);
        return false;
    }
    var last_name = jQuery("input[name='last_name']").val();
    
    if (last_name == "") {
        jQuery('div#getSmsError').text("Please Input last name!");
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400).fadeOut(400);
        return false;
    }
    if(!commis_id){
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
            save(data, addURL, "commissioners");
        }
    });
}else{
     save(data, addURL, "commissioners");
}
  
});

/* Save new commis */
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

// Check duplicate destination
function check_duplicate_destination(callback) {
    if (controller == "tours") {
        var term = $("input#newDes").val();
    } 
    if(controller == "tickets") {
        var term = $("input#destinationID").val();
    }
    var url = baseURL + "tickets/check_duplicate_destination";
    $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        data: {"term" : term},
        success: function(response) {
            if (callback) {
                callback(response);
            };
        }
    });
}

function addTicketManage(data, url, modals) {
    jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        data: data,
        success: function(msg) {
            if (msg.success) {
                set_feedback(msg.message,'success_message',false);
                jQuery("#"+modals).modal('hide');
                // Reset form
                $('.modal').on('hidden.bs.modal', function(){
                    $(this).find('form')[0].reset();
                });
                var search = url.replace("save", "search");
                getDataSearch("", search);
                return true;
            } else {
                jQuery('div#getSmsError').text(msg.message);
                jQuery("div#error").hide();
                return false;
            }
        }
    });
}


function save_package(data, url, modal_id) {
    var search = url.replace("save_package", "search_package");
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



function addPeople(url, url_select, datas, modals) {
    $.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
        data: datas,
        success: function(response) {
            selectPeople(url, url_select, response.person_id, modals);
        }
    });
}

function selectPeople(url, url_select, person_id, modals) {
    jQuery.ajax({
        type: "POST",
        url: url_select,
        dataType: "html",
        data: { "term" : person_id },
        success: function(response) {
            $("div#register_container").html(response);
            jQuery("#"+modals).modal('hide');
        }
    });
}
