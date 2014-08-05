/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var baseURL = jQuery('input[name="baseURL"]').val();
var controller = jQuery('input[name="controller_name"]').val();


// chen action transport
//   jQuery("a#delete_guide").click(function(event){
   jQuery("body").on("click", "a#delete_transport", function(event) {
        if(jQuery('input[type="checkbox"]').is(':checked')){
            var url = jQuery(this).attr('href');
            event.preventDefault();
            var rcdsChecked = jQuery('input:checkbox:checked.check_value').map(function () {
                return jQuery(this).val();
            }).get();
            alert("Do you want to delete as this transportation?");

            jQuery.ajax({
                type: "POST",
                url: url,
                data: { "ids" : rcdsChecked },
                success: function(event){
                    if (event == "true") {
                        jQuery('input:checkbox:checked').each(function(){
                            jQuery('input:checkbox:checked').parents("tr").remove();
                            jQuery('input:checkbox:checked').removeAttr('checked');
                        });
                        jQuery('span#getSmsSuccess').text("You have successfully deleted.");
                        jQuery("div#feedback_bar").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
                    } else if(event == "false") {
                        jQuery('span#getSmsError').text("Could not deleted selected suppliers, one or more of the selected suppliers has sales.");
                        jQuery("div#feedback_bar_error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400);
                    }
                    window.location.reload(true); 
                }
            }); 
            return false;
        } else {
            alert('Please select checkbox to delete!');
            return false;
        }
    });
   
    /* Click edit link and retrieve data into form for edit */
  $('body').on('click','a#edit_transport',function(){
     var url = jQuery(this).attr("href").replace("#", ""); //catch url when click on edit_guide
     var uid = url.substr(url.lastIndexOf('/') + 1);
    
    $.ajax({
        url: url,
        dataType: "json",        
        success: function(data){
        var action = jQuery("form.form-horizontal").attr("action");
        jQuery("form.form-horizontal").attr("action", action+"/"+uid);
                
            jQuery("input[name='transport_id']").val(data.transport_id);
            jQuery("input[name='company_name']").val(data.company_name);
            jQuery("input[name='taxi_fname']").val(data.taxi_fname);
            jQuery("input[name='taxi_lname']").val(data.taxi_lname);
            jQuery("input[name='phone']").val(data.phone);
            jQuery("select[name='vehicle_type']").val(data.vehicle);
            jQuery("textarea[name='mark']").val(data.mark);
           
        }
    }); 
  });
  
//end chen action transportations

//add new transportation

jQuery("body").on("click", "input[name='btnSubmitTransport']", function(event) {
    event.preventDefault();
    var transport_id = jQuery("input[name='transport_id']").val();
    var checkExistURL = baseURL + "transportations/check_duplicate";
    var addURL = jQuery("form#transportations_form").attr("action")+"/"+transport_id;
    var company_name = jQuery("input[name='company_name']").val();
     var fname = jQuery("input[name='taxi_fname']").val();
      var lname = jQuery("input[name='taxi_lname']").val();
    
    if (company_name == "") {
        jQuery('div#getSmsError').text("Please Input company name!");
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400).fadeOut(400);
        return false;
    }
    if (fname == "") {
        jQuery('div#getSmsError').text("Please Input first name!");
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400).fadeOut(400);
        return false;
    }
    if (lname == "") {
        jQuery('div#getSmsError').text("Please Input last name!");
        jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400).fadeOut(400);
        return false;
    }
    if(!transport_id){
    jQuery.ajax({
        type: "POST",
        url: checkExistURL,
        dataType: "json",
        data: jQuery("form#transportations_form").serialize(),
        success: function(json) {
            if (json.duplicate) {
                jQuery('div#getSmsError').text("This record is duplicated!");
                jQuery("div#error").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400).fadeOut(400);
                return false;
            }
            saveTransportation(addURL);
        }
    });
}else{
    saveTransportation(addURL);
}
  
});

/* Save new transportation */
function saveTransportation(addURL) {
    $.ajax({
        type: "POST",
        url: addURL,
        dataType: "json",
        data: $("form#transportations_form").serialize(),
        success: function(respons) {
            jQuery('div#getSmsSuccess').text(respons.message);
            jQuery("div#success").fadeOut(800).fadeIn(800).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400).fadeOut(400);
             jQuery("#transportations").modal('hide');
             window.location.reload(true); 
            //addToCart(respons.tour_id, selectURL);
        }
    });
}
