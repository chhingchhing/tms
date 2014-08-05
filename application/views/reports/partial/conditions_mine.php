<script src="<?php echo base_url()."js/jquery.googleapi.js"; ?>"></script>
<script src="<?php echo base_url()."assets/js/jquery-1.10.2.js"; ?>"></script>

<style type="text/css">
	.ui-autocomplete-loading {
        background: white url('images/spinner_small.gif') 0% 5% no-repeat;
    }
	.item_table { padding-left: 40px; font: 12px Arial;}
	span.required { color: #FF0000; }

	/* Add / Remove Images */
	a.AddCondition {
		background-image: url(data:image/gif;base64,R0lGODlhEAAQAPcAAAAAAP///5i+lTyGNUeNQEiOQVKVTJi+lN7p3d3o3ESMO0WMO0iPP1SVTLjRtUKNNkOPOEmOP3DBY16bVWCdV4HMdXm9boXNeYbJfI7Sg4nIf47MhJTTisXowFCZQXrGa2OgV37Hb4rPfYbJeozLgZXUipfVi5bUi5PNiJ3YkqDZlaDZlqXbm6/fprfgr73ktqTGnqPFnc7pydzx2GWrVXG+X2qsW3zDa2eiWpbTia/fpbPdqbXfrL7ittfu0tTrz8XawODy3OPs4eLr4FeeRWSgVGaiV3etaHWsZ5nRi5jMirTdqrLbqLbdrLjdr8bawebu5HC4WW61WGirU2+1WXnBZGuqWGyqWn7BaX25an25a3+5bYrCearUncvmw8jcwsncw2+1WHK5W3O6XHS3XHe8YH2+Z3i0ZIm+eI/CfZfMhZLFgIm5eJjLhpjMh7XbqLXRrLfTrnS3W3y6ZH28Zn6yaoK1boK0bo2+e4i3dpfHhaTOlKbQlqbPlqvUnLTaprPZpbTZpbfaqrvcr+bu44a2cZS/gZW/gurx54y7dpO/fpK+fsLatsHZtdXlzerw5/n5+f///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAAJEALAAAAAAQABAAAAjPACEJHEiwYMFHjA4VusMmDiGDkBwt2uPlhwwnW46AKego0aAgL1is0NGBi5EnAxEpEjQjBYoNJ0pkcKEFhxCBjfT4UMFBQ4AAF26E4EEEhkBDTVqQwGDhp40yY5KsoSDQTg8TI35qDRAFSxcGAuvsEFFh688oZvwoEJhHSY4PEmicjSJGDZ4GAuFMWVKlRpmzUsj8gXBAIBQkWZhAjUIljBxAVwogGPilyJk3bujMaRPIygAHBYGA8JCGTx80DwiANjgkxoQICwwISACxdsGAADs=);	
		display: block;
		float: left;
		text-indent: -9999px;
		width: 16px;
		height: 16px;
	}
	a.DelCondition {
		background-image: url(data:image/gif;base64,R0lGODlhEAAQAPcAAAAAAP///+FvcON4efDi3ePAtfDh3LpSNL5WOb9cP71bP8FmS9mjk9mklMRQNL9TOcBZPsNkS8ZqU/zHusFNM8BVPfaCaMhqVfaEbPiMdu6KdfCMd/GOeveTfviUf/qah/qjkfi2qN6mm/3b1PLj4MxSPPNzXfN5Y8tlVMxoV/iGcPCFcPmSfvqTf/eRfvCRf/qdi/qrnfq6rt6nnfzUzfnTzOnFv/Li3+5mUs1gUc5iU/J3Y/upnPWsofivpPWvpfS0qvm5r/nLxOrFv9BPPtNwZPSOge6MgferofarouvFwevGwv3c2OlZTeZYTOlbT+ZZTupcUNtWS/BkVuxfVNddUepmXNZgVO5qXuNrYeNuY+JwZtVtY+l7cO+GfvGdlvjDvuZWTOZaUuljW+BlXOR4cfCDe+l/eOJ7ddx3cu6EfeqDfe2TjvGclvWmofOno+etqeivrPTk4+ZWUOddWdtoZNtraNxuaeh6dd54c+6Sj/SinvWjn/Ktqt1rauJ4eOJ+fOF+fPSgnu2ysu2zs/LLy/bm5vn5+f///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAAIgALAAAAAAQABAAAAjLAA8JHEiwYEFDhAL5sZMnjhyDhwr9YQOmhhAgW7gsKVhIQB8mMkDA4DGhi44hAw0NeDPiwwsOHlhkCKElBQmBg9bQgNFBA4afGE74IDJDIKAfMTZQWcp0h5EzFwTeCdJiRYCrWJtg+VJBYB0kKixgzWqlDQKBaY64MNGkrdsnZtBEEAhHSpIpONw2cSKGjwMGAuUUydKDSpQmUMLM2XMlAYGBSnKQceNlDB01gqocKFDQBooSePToKUNBAWeDN0RIgPBgQQMDEGMXDAgAOw==);		display: block;
		float: left;
		text-indent: -9999px;
		width: 16px;
		height: 16px;
	}
	span.actionCondition {
		float: left;
		font-weight: bold;
		margin-right: 5px;
	}
	
	table.conditions {
		width: 900px;
		border: 1px solid #DDDDDD;
	}
	
	table.conditions tr.duplicate td {
		padding: 10px 0px;
	}

	table.conditions tr.duplicate td.field {
		padding-left: 5px;
	}

	table.conditions tr.duplicate td.field select {
		width: 200px;
	}
	
	table.conditions tr.duplicate td.value textarea {
		height: 20px;
		resize: none;
		overflow-y: hidden;
		padding-left: 16px;
		-webkit-transition:height .1s ease-in-out;
		-moz-transition:height .1s ease-in-out;
		-o-transition:height .1s ease-in-out;
		-ms-transition:height .1s ease-in-out;
		transition:height .1s ease-in-out;	
	}
	
</style>

<script type="text/javascript">
// (function($) 
// {
//   	$.fn.tokenize = function(options)
// 	{
// 		alert("tokenize");
// 		var settings = $.extend({}, {prePopulate: false}, options);
//     	return this.each(function() 
// 		{
//       		$(this).tokenInput('<?php echo site_url("reports/sales_generator"); ?>?act=autocomplete',
// 			{
// 				theme: "facebook",
// 				queryParam: "term",
// 				extraParam: "w",
// 				hintText: <?php echo json_encode(lang("reports_sales_generator_autocomplete_hintText"));?>,
// 				noResultsText: <?php echo json_encode(lang("reports_sales_generator_autocomplete_noResultsText"));?>,
// 				searchingText: <?php echo json_encode(lang("reports_sales_generator_autocomplete_searchingText"));?>,
// 				preventDuplicates: true,
// 				prePopulate: settings.prePopulate
// 			});
//     	});
//  	}
// })(jQuery);
/*jQuery("body").on("keypress", "td.value input", function() {
    var fullUrl = window.location.pathname;
    var path = fullUrl.split("/");
    var w = jQuery("td.value input.w").attr("w");
    var url = baseURL + "reports/"+path[3]+"?act=autocomplete&w="+w+"&term="+this.value;
    // var settings = $.extend({}, {prePopulate: false}, '');
    if (w != "") {
        jQuery.ajax({
            type: "get",
            url: url,
            dataType: "json",
            success: function(response) {
            	// jQuery("td.value input").tokenize();
                jQuery("td.value input").tokenInput(response, 
                    {
                    theme: "facebook",
                    queryParam: "term",
                    extraParam: "w",
                    hintText: <?php echo json_encode(lang("reports_sales_generator_autocomplete_hintText"));?>,
                    noResultsText: <?php echo json_encode(lang("reports_sales_generator_autocomplete_noResultsText"));?>,
                    searchingText: <?php echo json_encode(lang("reports_sales_generator_autocomplete_searchingText"));?>,
                    preventDuplicates: true,
                    // prePopulate: settings.prePopulate
                });
            }
        });
    };
});*/

jQuery(function($) 
{
  	$.fn.tokenize = function(options)
	{
		var settings = $.extend({}, {prePopulate: false}, options);
    	return this.each(function() 
		{
      		$(this).tokenInput('<?php echo site_url("reports/sales_generator_".$this->uri->segment(4)); ?>?act=autocomplete',
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
    	});
 	}
});

jQuery("body").on("change", "select#matchType", function() {
	if ($(this).val() == 'matchType_All')
	{
		$("#matched_items_only").attr('disabled', false);
		$(".actions span.actionCondition").html(<?php echo json_encode(lang("reports_sales_generator_matchType_All_TEXT"));?>);
	}
	else 
	{
		$("#matched_items_only").attr('checked', false);
		$("#matched_items_only").attr('disabled', true);
		$(".actions span.actionCondition").html(<?php echo json_encode(lang("reports_sales_generator_matchType_Or_TEXT"));?>);
	}
});

jQuery("body").on("click", "a.AddCondition", function(event) {
	event.preventDefault();
	var sInput = $("<input />").attr({"type": "text", "name": "value[]", "w":"", "value":"", "class":"w"});
	$('.conditions tr.duplicate:last').clone().insertAfter($('.conditions tr.duplicate:last'));
	$("input", $('.conditions tr.duplicate:last')).parent().html("").append(sInput).children("input").tokenize();
	$("option", $('.conditions tr.duplicate:last select')).removeAttr("disabled").removeAttr("selected").first().attr("selected", "selected");
	
	$('.conditions tr.duplicate:last').trigger('change');
	e.preventDefault();
});

jQuery("body").on("click", "a.DelCondition", function(e) {
	if ($(this).parent().parent().parent().children().length > 1)
		$(this).parent().parent().remove();
	
	e.preventDefault();
});

// $(".selectField").on("change", function() {
jQuery("body").on("change", ".selectField", function() {
	var sInput = $("<input />").attr({"type": "text", "name": "value[]", "w":"", "value":"", "class":"w"});
	var field = $(this);
	// alert(field.toSource())
	// Remove Value Field
	field.parent().parent().children("td.value").html("");
	if ($(this).val() == 0) 
	{
		field.parent().parent().children("td.condition").children(".selectCondition").attr("disabled", "disabled");	
		field.parent().parent().children("td.value").append(sInput.attr("disabled", "disabled"));		
	} 
	else 
	{
		field.parent().parent().children("td.condition").children(".selectCondition").removeAttr("disabled");	
		if ($(this).val() == 2 || $(this).val() == 7 || $(this).val() == 10) 
		{
			field.parent().parent().children("td.value").append(sInput);		
		} 
		else 
		{
			if ($(this).val() == 6) 
			{
				field.parent().parent().children("td.value").append($("<input />").attr({"type": "hidden", "name": "value[]", "value":"", "class":"w"}));		
			} 
			else 
			{
				// field.parent().parent().children("td.value").append(sInput.attr("w", $("option:selected", field).attr('rel'))).children("input").tokenize();		
				field.parent().parent().children("td.value").append(sInput.attr("w", $("option:selected", field).attr('rel'))).children("input");		
			}
		}
		disableConditions(field, true);
	}
});

jQuery(function() {
	<?php
		if (isset($prepopulate) and count($prepopulate) > 0) {
			echo "var prepopulate = $.parseJSON('".json_encode($prepopulate)."');";
		}
	?>
	var sInput = $("<input />").attr({"type": "text", "name": "value[]", "w":"", "value":"", "class":"w"});
	$(".selectField").each(function(i) {
		if ($(this).val() == 0) {
			$(this).parent().parent().children("td.condition").children(".selectCondition").attr("disabled", "disabled");
			$(this).parent().parent().children("td.value").html("").append(sInput.attr("disabled", "disabled"));	
		} else {
			if ($(this).val() != 2 && $(this).val() != 6 && $(this).val() != 7 && $(this).val() != 10) {
				// $(this).parent().parent().children("td.value").children("input").attr("w", $("option:selected", $(this)).attr('rel')).tokenize({prePopulate: prepopulate.field[i][$(this).val()] });	
				$(this).parent().parent().children("td.value").children("input").attr("w", $("option:selected", $(this)).attr('rel')).tokenize({prePopulate: prepopulate.field[i][$(this).val()] });	
			}
			if ($(this).val() == 6) {
				$(this).parent().parent().children("td.value").html("").append($("<input />").attr({"type": "hidden", "name": "value[]", "value":"", "class":"w"}));	
			}
			disableConditions($(this), false);
		}
	});
	
	jQuery("body").on("change", "#start_month, #start_day, #start_year, #end_month, #end_day, #end_year", function() 
	{
		$("#complex_radio").attr('checked', 'checked');
	});

	jQuery("body").on("change", "#report_date_range_simple", function() 
	{
		$("#simple_radio").attr('checked', 'checked');
	});
});

function disableConditions(elm, q) {
	var allowed1 = ['1', '2'];
	var allowed2 = ['7', '8', '9'];
	var allowed3 = ['10', '11'];
	var allowed4 = ['1', '2', '7', '8', '9'];
	var allowed5 = ['1'];
	var disabled = elm.parent().parent().children("td.condition").children(".selectCondition");
	
	if (q == true)
		$("option", disabled).removeAttr("selected");
	
	$("option", disabled).attr("disabled", "disabled");
	$("option", disabled).each(function() {
		if (elm.val() == 11 && $.inArray($(this).attr("value"), allowed5) != -1) {
			$(this).removeAttr("disabled");
		}else if (elm.val() == 10 && $.inArray($(this).attr("value"), allowed4) != -1) {
			$(this).removeAttr("disabled");
		} else if (elm.val() == 6 && $.inArray($(this).attr("value"), allowed3) != -1) {
			$(this).removeAttr("disabled");
		} else if (elm.val() == 7 && $.inArray($(this).attr("value"), allowed2) != -1) {
			$(this).removeAttr("disabled");
		} else if (elm.val() != 6 && elm.val() != 7 && elm.val() != 10 && elm.val() != 11 && $.inArray($(this).attr("value"), allowed1) != -1) {
			$(this).removeAttr("disabled");
		}
	});
	
	if (q == true)
		$("option:not(:disabled)", disabled).first().attr("selected", "selected");
}

</script>