/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*jQuery("div#success").hide();
jQuery("div#error").hide();
jQuery("div#feedback_bar").hide();
jQuery("div#feedback_bar_error").hide();*/

/*$(document).ready(function() {  
    $('.printMe').click(function() {  
    window.print();  
    return false;  
    });
});*/


// Print receipt
jQuery("body").on("click","#print_button", function(){
    window.print();
    return false;
});


var baseURL = jQuery('input[name="baseURL"]').val();
var controller = jQuery('input[name="controller_name"]').val();


jQuery("body").on("click", "input[name='submitTour']", function(event) {
    event.preventDefault();
    var tour_id = jQuery("input[name='tour_id']").val();
    var checkExistURL = baseURL + "tours/check_duplicate";
    var addURL = jQuery("form#tour_form").attr("action")+"/"+tour_id;
    var selectURL = baseURL + "tours/add";
    var tour_name = jQuery("input[name='tour_name']").val();
    if (tour_name == "") {
        jQuery('div#getSmsError').text("Please Input tour name!");
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400).fadeOut(400);
        return false;
    }
    // if (!tour_id) {
        jQuery.ajax({
            type: "POST",
            url: checkExistURL,
            dataType: "json",
            data: jQuery("form#tour_form").serialize(),
            success: function(json) {
                if (json.duplicate) {
                    jQuery('div#getSmsError').text("This record is duplicated!");
                    jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400).fadeOut(400);
                    return false;
                }
                saveTour(addURL, selectURL);
            }
        });
    // } else {
    //     saveTour(addURL, selectURL);
    // }
    
  
});

/* Save new tour */
function saveTour(addURL, selectURL) {
    $.ajax({
        type: "POST",
        url: addURL,
        dataType: "json",
        data: $("form#tour_form").serialize(),
        success: function(respons) {
            jQuery('div#getSmsSuccess').text(respons.message);
            jQuery("div#success").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400).fadeOut(400);
            addToCart(respons.tour_id, selectURL);
        }
    });
}

// Add item to cart
function addToCart(tour_id, selectURL) {
    $.ajax({
        type: "POST",
        url: selectURL,
        dataType: "html",
        data: { "item": tour_id},
        success: function(respons) {
            jQuery("#tours").modal('hide');
            $("div#register_container").html(respons);
            // window.location.reload(true);
        }
    });
}

/* Guide Action */

// Add new guide
jQuery('body').on('click', 'input#submit_guide', function(event){
    event.preventDefault();
    var url = jQuery("form#guides_form").attr('action');
    $.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
        data: jQuery("form#guides_form").serialize(),
        success: function(respons) {
            selectGuide(respons.guide_id);
        }
    });
});

function selectGuide(guide_id) {
    var url = baseURL + controller + "/select_guide";
    if(controller == "guides") {
        jQuery.ajax({
            type: "POST",
            url: url,
            dataType: "html",
            data: { "guide" : guide_id },
            success: function(respons) {
                jQuery("#guides").modal('hide');
                window.location.reload(true); 
            }
        });
    } else {
        jQuery.ajax({
            type: "POST",
            url: url,
            dataType: "html",
            data: { "guide" : guide_id },
            success: function(respons) {
                $("div#register_container").html(respons);
                jQuery("#guides").modal('hide');
            }
        });
    }
}
    
    /* Click edit link and retrieve data into form for edit */
  $('body').on('click','a#edit_guide',function(){ 
     var url = jQuery(this).attr("href").replace("#", ""); //catch url when click on edit_guide
     var uid = url.substr(url.lastIndexOf('/') + 1);
    $.ajax({
        url: url,
        dataType: "json",        
        success: function(data){
        var action = jQuery("form.form-horizontal").attr("action");
        jQuery("form.form-horizontal").attr("action", action+"/"+uid);
                
            jQuery("input[name='guide_id']").val(data.guide_id);
            jQuery("input[name='first_name']").val(data.guide_fname);
            jQuery("input[name='last_name']").val(data.guide_lname);
            jQuery("select[name='gender']").val(data.gender);
            jQuery("input[name='phone_number']").val(data.tel);
            jQuery("input[name='email']").val(data.email);
            jQuery("input[name='guide_type']").val(data.guide_type);
           
        }
    }); 
  });

    
//end chen action guide

// Search autocompleted on sale guide
$('body').on('keypress','input#guide',function(event){
    if (event.keyCode === $.ui.keyCode.ENTER) {
        return false;
    }
    var url = jQuery("form#select_guide_form").attr("action");
    var term = jQuery(this).val();
    var suggest = baseURL + "guides/guide_search";
    $.ajax({
        type: "POST",
        url: suggest,
        dataType: "json",
        data: {"term" : term},
        success: function(data){
            $("input#guide").autocomplete({
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

//add new tour on module of tour management

jQuery("body").on("click", "input[name='btn_submit_tours']", function(event) {
    event.preventDefault();
    var tour_id = jQuery("input[name='tour_id']").val();
    var checkExistURL = baseURL + "tours/check_duplicate";
    var addURL = jQuery("form#tour_form").attr("action")+"/"+tour_id;
    var selectURL = baseURL + "tours/add";
    var tour_name = jQuery("input[name='tour_name']").val();
    
    if (tour_name == "") {
        jQuery('div#getSmsError').text("Please Input tour name!");
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400).fadeOut(400);
        return false;
    }
    if (!tour_id) {
        jQuery.ajax({
            type: "POST",
            url: checkExistURL,
            dataType: "json",
            data: jQuery("form#tour_form").serialize(),
            success: function(json) {
                if (json.duplicate) {
                    jQuery('div#getSmsError').text("This record is duplicated!");
                    jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400).fadeOut(400);
                    return false;
                }
                save(addURL, selectURL);
            }
        });
    } else {
        save(addURL, selectURL);
    }
});

/* Save new tour on Module tour management */
function save(addURL, selectURL) {
    $.ajax({
        type: "POST",
        url: addURL,
        dataType: "json",
        data: $("form#tour_form").serialize(),
        success: function(response) {
            if(response.success)
            {
                set_feedback(response.message,'success_message',false);
                jQuery("#tours").modal("hide");
                // Reset form
                $('.modal').on('hidden.bs.modal', function(){
                    $(this).find('form')[0].reset();
                });
                var search = addURL.replace("save", "search");
                getDataSearch("", search);
            }
            else
            {
                set_feedback(response.message,'error_message',false);
            }
        }
    });
}

//chen add new guide

jQuery("body").on("click", "input#btnSubmitGuide", function(event) {
    event.preventDefault();
    var guide_id = jQuery("input[name='guide_id']").val();
    var checkExistURL = baseURL + "guides/check_duplicate";
    var addURL = jQuery("form#guides_form").attr("action")+"/"+guide_id;

//    var selectURL = baseURL + "transportations/add";
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
    if(!guide_id){
        jQuery.ajax({
            type: "POST",
            url: checkExistURL,
            dataType: "json",
            data: jQuery("form#guides_form").serialize(),
            success: function(json) {
                if (json.duplicate) {
                    jQuery('div#getSmsError').text("This record is duplicated!");
                    jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400).fadeOut(400);
                    return false;
                }
                saveGuide(addURL);
            }
        });
    } else {
        saveGuide(addURL);
    }  
});

/* Save new guide */
function saveGuide(addURL) {
    $.ajax({
        type: "POST",
        url: addURL,
        dataType: "json",
        data: $("form#guides_form").serialize(),
        success: function(response) {
            if(response.success)
            {
                set_feedback(response.message,'success_message',false);
                jQuery("#guides").modal("hide");
                // Reset form
                $('.modal').on('hidden.bs.modal', function(){
                    $(this).find('form')[0].reset();
                });
                var search = addURL.replace("save", "search");
                getDataSearch("", search);
            }
            else
            {
                set_feedback(response.message,'error_message',false);
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