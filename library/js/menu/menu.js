/*
** This is the location of the file menu.processor.php
*/
var menu_processor = "../../library/menu.processor.php";

/* STRING WITH SPECIAL CHARACTERS
** used to include in the regexp for some fields 
** such as Title for adding and editing menu items
*/
var special_chars = "\xE0\xE1\xE2\xE3\xE7\xE8\xE9\xEA\xEC\xED\xF2\xF3\xF4\xF5\xF9\xFA\xEA";


$(document).ready(function(){
	
	/* CHANGE CURRENT MENU */
	$("#menu_selector").change(function() {
		$("#change_menu").submit();
	});
	
	
	/* START NESTED SORTABLE */
	$('ol.sortable').nestedSortable({
		disableNesting: 'no-nest',
		forcePlaceholderSize: true,
		handle: 'div.handle',
		helper:	'clone',
		items: 'li',
		maxLevels: 99,
		opacity: .6,
		placeholder: 'placeholder',
		revert: 250,
		tabSize: 25,
		tolerance: 'pointer',
		toleranceElement: '> div',
		stop: function(event, ui) {
			enableSaveButton();
			//alert(movedItem);
		}
	});
	
	
	/* let's set the initial menu structure serialized, to compare to future changes and activating the save menu button if differences occur */
	$("#current_menu_serialized").val($('ol.sortable').nestedSortable('serialize'));
	
	
	/* Tooltips close cross button */
	$('.cross').bind('click', function() {
		$(this).parent("div").fadeOut('fast');
	});
	
	
	/* SAVE MENU BUTTON */
	$("#save_menu").click(function(e){
		e.preventDefault();
		saveMenutoDB();
	})
	
	
	/* PREVIEW MENU BUTTON */
	$("#preview_menu").click(function(e){
		e.preventDefault();
		
		$('input#current_menu_nr_items').val(get_numberItemsInMenu());
		
		menuarray = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});
		convertJsArrayToPhpArray(menuarray, "menu_item", "preview_menu_form");
		
		$("form.preview_menu_form").submit();
	})
	
});



/* FUNCTION TO CONVERT AN ARRAY INTO MULTIPLE INPUTS FOR PHP FORM SUBMITING */
function convertJsArrayToPhpArray(array, name, form) {
	if (typeof(form) == 'string') {
		form2 = document.getElementById(form);
	}
	var hidden = null;
	
	$('#'+form+' input[name^="'+name+'"]').remove();
	
	for(index in array) {
		hidden = document.createElement('input');
		hidden.setAttribute('type', 'hidden');
		hidden.setAttribute('name', name + '[' + index +']["item_title"]');
		hidden.setAttribute('value', array[index]["item_title"]);
		form2.appendChild(hidden);
		
		hidden = document.createElement('input');
		hidden.setAttribute('type', 'hidden');
		hidden.setAttribute('name', name + '[' + index +']["depth"]');
		hidden.setAttribute('value', array[index]["depth"]);
		form2.appendChild(hidden);
		
		hidden = document.createElement('input');
		hidden.setAttribute('type', 'hidden');
		hidden.setAttribute('name', name + '[' + index +']["left"]');
		hidden.setAttribute('value', array[index]["left"]);
		form2.appendChild(hidden);
		
		hidden = document.createElement('input');
		hidden.setAttribute('type', 'hidden');
		hidden.setAttribute('name', name + '[' + index +']["right"]');
		hidden.setAttribute('value', array[index]["right"]);
		form2.appendChild(hidden);
		
	}
}



/* OPEN NOTIFICATION FUNCTION */
function enableNotification(wich, msg, time) {
	if($('#'+wich).css('display')=='none') {
		$('#notify_green').hide();
		$('#notify_red').hide();
		$('#notify_yellow').hide();
		$('#notify_blue').hide();
		$('#'+wich+" .content").html(msg);
		$('#'+wich).fadeIn('slow');
		setTimeout(function() { $('#'+wich).fadeOut(); }, time);
	}
}



/* FUNCTION TO GET MENU OL TREE TO THE "ADD NEW ITEM FORM" PARENT INPUT */
function get_menuItems_NestedSetInput() {
	result 	= '<select name="parent" id="parent" >';
	result 	+= '<option value="0"> - Add to root - </option>';
	$('.sortable li').each(function() {
		id = $(this).attr("id");
		id = id.substring(5);
		result 	+= '<option value="'+id+'">'+Array($(this).parents('ol').length).join("&nbsp;&nbsp;&nbsp;&nbsp;")+$(this).find("span.title").html()+'</option>';
	});
	result 	+= "</select>";
	$("#parent_container").html(result);
}


/* GET TOTAL NUMBER OF ITEMS ALREADY IN DOM */
function get_numberItemsInMenu() {
	n = 0
	$('.sortable li').each(function() {
		n++;
	});
	return n;
}



/* SIMPLE DELAY FUNCTION */
var delay = (function(){
	var timer = 0;
	return function (callback, ms) {
		clearTimeout (timer);
		timer = setTimeout(callback, ms);
	};
})();




/* CREATE MENU ITEM FORM RESET FUNCTION */
function reset_CreateMenuItemForm(whatsOn) {
	if(whatsOn == "url") { 
		$("#url_input_holder").show(); 
		$("#type").val("url");
	} else { 
		$("#url_input_holder").hide(); 
	}
	
	if(whatsOn == "component") { 
		$("#component_input_holder").show();
		$("#component_id_input_holder").show();
		$("#type").val("component");
	} else { 
		$("#component_input_holder").hide();
		$("#component_id_input_holder").hide();
	}

	if(whatsOn == "content") { 
		$("#content_input_holder").show(); 
		$("#content").val("url");
	} else { 
		$("#content_input_holder").hide(); 
	}
}




/* ADD NEW MENU ITEM TO DOM FUNCTION 
This is important because it adds HTML dinamically to the dom.
Even knowing about the evilness of adding HTML via javascript
we have to. So all HTML is processed here at this funciton, so
any arrangements can be made here.

function addNewMenuItemNow(n, title, content, type, class_name, code, pdValue, frame, published, sAccess, lAccess, pre, after) {
	html_output = pre+'<li id="list_'+n+'"><div class="li_content"><div class="handle"></div><span class="title">'+title+'&nbsp;</span><input type="hidden" class="db_title" name="db_title" value="'+title+'" /><div class="tasks"><a class="edit edit_item_btn" href="#'+n+'"></a><a class="remove rem_item_btn" href="#'+n+'"></a></div><span class="content">'+content+'&nbsp;</span><input type="hidden" class="db_content" name="db_content" value="'+content+'" /><span class="type">'+type+'&nbsp;</span><input type="hidden" class="db_type" name="db_type" value="'+type+'" /><span class="class">'+class_name+'&nbsp;</span><input type="hidden" class="db_class_name" name="db_class_name" value="'+class_name+'" /><span class="code">&nbsp;</span><input type="hidden" class="db_code" name="db_code" value="'+code+'" /><span class="pdValue">&nbsp;</span><input type="hidden" class="db_pdValue" name="db_pdValue" value="'+pdValue+'" /><span class="frame">&nbsp;</span><input type="hidden" class="db_frame" name="db_frame" value="'+frame+'" /><span class="published">&nbsp;</span><input type="hidden" class="db_published" name="db_published" value="'+published+'" /><span class="sAccess">&nbsp;</span><input type="hidden" class="db_sAccess" name="db_sAccess" value="'+sAccess+'" /><span class="lAccess">&nbsp;</span><input type="hidden" class="db_lAccess" name="db_lAccess" value="'+lAccess+'" /><br clear="all" /></div></li>'+after;
	return html_output;
}
*/

function addNewMenuItemNow(n, title, content, type, class_name, code, pdValue, frame, published, sAccess, lAccess, pre, after) {
	html_output = pre+'<li id="list_'+n+'"><div class="li_content"><div class="handle"></div><span class="title">'+title+'&nbsp;</span><input type="hidden" class="db_title" name="db_title" value="'+title+'" /><div class="tasks"><a class="edit edit_item_btn" href="#'+n+'"></a><a class="remove rem_item_btn" href="#'+n+'"></a></div><span class="content">'+content+'&nbsp;</span><input type="hidden" class="db_content" name="db_content" value="'+content+'" /><span class="type">'+type+'&nbsp;</span><input type="hidden" class="db_type" name="db_type" value="'+type+'" /><input type="hidden" class="db_class_name" name="db_class_name" value="'+class_name+'" /><input type="hidden" class="db_code" name="db_code" value="'+code+'" /><input type="hidden" class="db_pdValue" name="db_pdValue" value="'+pdValue+'" /><input type="hidden" class="db_frame" name="db_frame" value="'+frame+'" /><input type="hidden" class="db_published" name="db_published" value="'+published+'" /><input type="hidden" class="db_sAccess" name="db_sAccess" value="'+sAccess+'" /><input type="hidden" class="db_lAccess" name="db_lAccess" value="'+lAccess+'" /><br clear="all" /></div></li>'+after;
	return html_output;
}
/* 
function addNewMenuItemNow(n, title, content, type, pre, after) {	
	html_output = pre+'<li id="list_'+n+'"><div class="li_content"><div class="handle"></div><span class="title">'+title+'&nbsp;</span><input type="hidden" class="db_title" name="db_title" value="'+title+'" /><div class="tasks"><a class="edit edit_item_btn" href="#'+n+'"></a><a class="remove rem_item_btn" href="#'+n+'"></a></div><span class="type">'+type+'&nbsp;</span><input type="hidden" class="db_type" name="db_type" value="'+type+'" /><span class="content">'+content+'&nbsp;</span><input type="hidden" class="db_content" name="db_content" value="'+content+'" /><br clear="all" /></div></li>'+after;
	return html_output;
}
*/


/* EDIT MENU ITEM IN DOM FUNCTION 
This is also important because it changes HTML dinamically in the dom.
So if your HTML changes, you have to change here accordingly
*/
function editMenuItemNow(n, title, content, type, class_name, code, pdValue, frame, published, sAccess, lAccess) {
	$('#list_'+n).find('span.title:first').html(title+"&nbsp;");
	$('#list_'+n).find('input.db_title:first').val(title);
	
	$('#list_'+n).find('span.class:first').html(class_name+"&nbsp;");
	$('#list_'+n).find('input.db_class_name:first').val(class_name);
	
	$('#list_'+n).find('span.type:first').html(type+"&nbsp;");
	$('#list_'+n).find('input.db_type:first').val(type);
	
	$('#list_'+n).find('span.content:first').html(content+"&nbsp;");
	$('#list_'+n).find('input.db_content:first').val(content);
	
	$('#list_'+n).find('span.code:first').html(code+"&nbsp;");
	$('#list_'+n).find('input.db_code:first').val(code);
	
	$('#list_'+n).find('span.pdValue:first').html(pdValue+"&nbsp;");
	$('#list_'+n).find('input.db_pdValue:first').val(pdValue);		
	
	$('#list_'+n).find('span.frame:first').html(frame+"&nbsp;");
	$('#list_'+n).find('input.db_frame:first').val(frame);	
	
	$('#list_'+n).find('span.published:first').html(published+"&nbsp;");
	$('#list_'+n).find('input.db_published:first').val(published);	
	
	$('#list_'+n).find('span.sAccess:first').html(sAccess+"&nbsp;");
	$('#list_'+n).find('input.db_sAccess:first').val(sAccess);	
	
	$('#list_'+n).find('span.lAccess:first').html(lAccess+"&nbsp;");
	$('#list_'+n).find('input.db_lAccess:first').val(lAccess);	
	
}
/*
function editMenuItemNow(n, title, content, type, class_name) {
	$('#list_'+n).find('span.title:first').html(title+"&nbsp;");
	$('#list_'+n).find('input.db_title:first').val(title);
	
	$('#list_'+n).find('span.class:first').html(class_name+"&nbsp;");
	$('#list_'+n).find('input.db_class_name:first').val(class_name);
	
	$('#list_'+n).find('span.type:first').html(type+"&nbsp;");
	$('#list_'+n).find('input.db_type:first').val(type);
	
	$('#list_'+n).find('span.content:first').html(content+"&nbsp;");
	$('#list_'+n).find('input.db_content:first').val(content);
	

}
*/


/* REMOVE MENU ITEM IN DOM FUNCTION - Also reorders all LI ids accordingly */
function removeMenuItem(id) {
	$("ol.sortable li").each(function(n,item){
		n = n+1;
		if (n == id) {
			$('#list_'+id+'').remove();
		} else if (n > id) {
			newID = n - 1;
			$('#list_'+n).attr("id",'#list_'+newID);
		}
	});
	$("ul").each(
		function() {
			var elem = $(this);
			if (elem.children().length == 0) {
				elem.remove();
			}
		}
	);
	$("ol").each(
		function() {
			var elem = $(this);
			if (elem.children().length == 0) {
				elem.remove();
			}
		}
	);
}



/* SHOW SAVE BUTTON FUNCTION */
window.enableSaveButton = function enableSaveButton() {
	if (($("#current_menu_serialized").val() != $('ol.sortable').nestedSortable('serialize')) || ($("#current_menu_name").val() != $("div.cname").html()) || ($("#current_menu_safe_name").val() != $("div.csname").html())) {
		$('#save_menu').fadeIn('normal');
		$('#save_menu').effect("shake", { times:3, distance:3, direction: "down" }, 75);
	} else {
		$('#save_menu').fadeOut('fast');
	}
}




/* SAVE MENU TO DATABASE */
function saveMenutoDB() {
	if (($("#current_menu_serialized").val() != $('ol.sortable').nestedSortable('serialize')) || ($("#current_menu_name").val() != $("div.cname").html()) || ($("#current_menu_safe_name").val() != $("div.csname").html())) {
		var serialized 	= $('ol.sortable').nestedSortable('toArray');
		var curr_menu	= $("#current_menu_safe_name").val();
		
		var newname		= $("div.cname").html();
		var newsname	= $("div.csname").html();
		
		$(document).ajaxStart($.blockUI({ message: '<h1 class="block_ui"><img src="assets/img/loading.gif" /> Saving menu, please wait.<br/></h1>' })).ajaxStop($.unblockUI);
		
		delay(function(){
			$.post(menu_processor,{ task: "save_menu", menu: curr_menu, s: serialized, nn: newname, nsn: newsname },
				function(data){
					if (data == "") {
						window.location.search = jQuery.query.set("menu_selector", newsname);
					} else {
						alert(data);
					}
				}, "html");
		}, 1000);
		
	} else {
		alert("Nothing to save!");
	}
}


function updateTips(tips_selector, text) {
	tips_selector.text(text).addClass( "ui-state-highlight" );
	setTimeout(function() {
		tips_selector.removeClass( "ui-state-highlight", 1500 );
	}, 500 );
}


function checkLength(o, n, min, max, tips_selector) {
	if (o.val().length > max || o.val().length < min) {
		o.addClass("ui-state-error");
		updateTips(tips_selector, "Length of "+n+" must be between "+min+" and "+max+".");
		return false;
	} else {
		return true;
	}
}

function checkRegexp(o, regexp, n, tips_selector) {
	if (!( regexp.test(o.val()))) {
		o.addClass("ui-state-error");
		updateTips(tips_selector, n);
		return false;
	} else {
		return true;
	}
}

function checkRegexp_separated_reg(o, regex, flags, n, tips_selector) {
	var re = new RegExp(regex,flags);
	if (!(re.test(o.val()))) {
		o.addClass("ui-state-error");
		updateTips(tips_selector, n);
		return false;
	} else {
		return true;
	}
}




/* ADD NEW MENU FUNCTION */
$(function() {
	var name = $( "#name" ), safe_name = $( "#safe_name" ), allFields = $( [] ).add( name ).add( safe_name ), tips_selector = $( ".validateTips" );
	
	$( "#create_menu-form" ).dialog({
		autoOpen: false,
		modal: true,
		width: 350,
		draggable: false, 
		resizable: false, 
		position: ["center", 150], 
		show: "fade", 
		buttons: {
			"Create Menu": function(e) {
				var bValid = true;
				allFields.removeClass( "ui-state-error" );
				bValid = bValid && checkLength( name, "username", 3, 16, tips_selector );
				bValid = bValid && checkLength( safe_name, "safe_name", 3, 16, tips_selector );
				bValid = bValid && checkRegexp( name, /^[a-z]([0-9a-z_ ])+$/i, "Menu name may consist of a-z, 0-9, underscores, spaces and begin with a letter.", tips_selector);
				bValid = bValid && checkRegexp( safe_name, /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores and begin with a letter.", tips_selector);
				if (bValid) {
					$.ajax({
						type: 'POST',
						url: menu_processor,
						data: 'task=add_menu&n='+$("#name").val()+'&s='+$("#safe_name").val(),
						success: function(data) {
							if (data == "") {
								location.reload(true);
							} else {
								alert(data);
							}
						}
					});
				}
			},
			Cancel: function() {
				$(this).dialog("close");
			}
		},
		close: function() {
			allFields.val("").removeClass("ui-state-error");
		}
	});
	$("#create_menu").button().click(function(e) {
		e.preventDefault();
		$("#create_menu-form").dialog("open");
	});
});




/* ADD NEW MENU ITEM FUNCTION */
$(function() {
	var title 			= $( "#title" ),
		class_name 		= $( "#class_name" ), 
		type 			= $( "#type" ), 
		url 			= $( "#url" ), 
		component 		= $( "#component" ),
		component_id 	= $( "#component_id" ),		
		content 		= $( "#content" ),
		code 			= $( "#code" ), 
		pdValue			= $( "#pdValue" ), 
		frame			= $( "#frame" ), 
		published		= $( "#published" ), 
		sAccess			= $( "#sAccess" ), 
		lAccess 		= $( "#lAccess" ), 
		allFields 		= $([]).add(title).add(class_name).add(type).add(url).add(component).add(component_id).add(content).add(code).add(pdValue).add(frame).add(published).add(sAccess).add(lAccess),
		tips_selector 	= $( ".validateTips" );
	
	function updateTips(t) {
		tips.text(t).addClass("ui-state-highlight");
		setTimeout(function() {
			tips.removeClass("ui-state-highlight", 1500);
		}, 500);
	}
	
	reset_CreateMenuItemForm("url");
	
	$("#type").change(function() {
		reset_CreateMenuItemForm($("#type").val());
	});
	
	$( "#create_menu_item-form" ).dialog({
		autoOpen: false,
		modal: true,
		width: 350,
		draggable: false, 
		resizable: false, 
		position: ["center", 150], 
		show: "fade",
		buttons: {
			"Create Item": function(e) {
				var bValid = true;
				allFields.removeClass("ui-state-error");
				bValid = bValid && checkLength( title, "title", 3, 50, tips_selector );
				bValid = bValid && checkRegexp_separated_reg(title, "^([0-9a-z"+special_chars+"_ -])+$", "i", "Title may consist of a-z, 0-9, underscores, minus and space.", tips_selector);
				
				if (class_name.val().length > 0) {
					bValid = bValid && checkRegexp_separated_reg(class_name, "^[a-z]([0-9a-z_])+$", "i", "Classname may consist of a-z, 0-9, underscores and begin with a letter.", tips_selector);
				}
				
				if (type.val() == "url") {
					bValid = bValid && checkLength(url, "url", 3, 50);
					if (url.val().length > 0) {
						bValid = bValid && checkRegexp_separated_reg(url, "^[a-zA-Z0-9\/.:_-]+$", "i", "URL may consist of a-z, 0-9, underscores, minus, dots and forward slash (/)", tips_selector);
					}
				}
				
				if (type.val() == "component") {
					if (component_id.val().length > 0) {
						bValid = bValid && checkRegexp_separated_reg(component_id, "^[a-zA-Z0-9_]+$", "i", "Component ID may consist of a-z, 0-9 and underscores", tips_selector);
					}
				}
				
				if (bValid) {
					n = get_numberItemsInMenu()+1;
					if ($("#parent").val() == 0) {
						if (type.val() == "url") {
							//html_output = addNewMenuItemNow(n, title.val(), url.val(), type.val(), class_name.val(), "", "");
							html_output = addNewMenuItemNow(n, title.val(), url.val(), type.val(), class_name.val(), code.val(), pdValue.val(), frame.val(), published.val(), sAccess.val(), lAccess.val(), "", "");
							$('.sortable').append(html_output);
							
						} else if (type.val() == "separator") {
							html_output = addNewMenuItemNow(n, title.val(), "|-|", type.val(), class_name.val(), "", "");
							$('.sortable').append(html_output);
							
						} else if (type.val() == "component") {
							html_output = addNewMenuItemNow(n, title.val(), '|'+component.val()+'|'+component_id.val()+'|', type.val(), class_name.val(), "", "");
							$('.sortable').append(html_output);
							
						} else if (type.val() == "content") {
							html_output = addNewMenuItemNow(n, title.val(), '|'+content.val()+'|', type.val(), class_name.val(), "", "");
							$('.sortable').append(html_output);
							
						} else {
							alert("Content Type Error!");
						}
						
					} else {
						if ($('#list_'+$("#parent").val()+' ol').length > 0) {
							if (type.val() == "url") {
								//html_output = addNewMenuItemNow(n, title.val(), url.val(), type.val(), class_name.val(), "", "");
								html_output = addNewMenuItemNow(n, title.val(), url.val(), type.val(), class_name.val(), code.val(), pdValue.val(), frame.val(), published.val(), sAccess.val(), lAccess.val(), "", "");
								$('#list_'+$("#parent").val()+' ol > li:last-child').after(html_output);
								
							} else if (type.val() == "separator") {
								html_output = addNewMenuItemNow(n, title.val(), "|-|", type.val(), class_name.val(), "", "");
								$('#list_'+$("#parent").val()+' ol > li:last-child').after(html_output);
								
							} else if (type.val() == "component") {
								html_output = addNewMenuItemNow(n, title.val(), '|'+component.val()+'|'+component_id.val()+'|', type.val(), class_name.val(), "", "");
								$('#list_'+$("#parent").val()+' ol > li:last-child').after(html_output);
								
							} else if (type.val() == "content") {
								html_output = addNewMenuItemNow(n, title.val(), '|'+content.val()+'|', type.val(), class_name.val(), "", "");
								$('#list_'+$("#parent").val()+' ol > li:last-child').after(html_output);
								
							} else {
								alert("Content Type Error!");
							}
							
						} else {
							if (type.val() == "url") {
								//html_output = addNewMenuItemNow(n, title.val(), url.val(), type.val(), class_name.val(), "<ol>", "</ol>");
								html_output = addNewMenuItemNow(n, title.val(), url.val(), type.val(), class_name.val(), code.val(), pdValue.val(), frame.val(), published.val(), sAccess.val(), lAccess.val(), "<ol>", "</ol>");
								$('#list_'+$("#parent").val()).append(html_output);
								
							} else if (type.val() == "separator") {
								html_output = addNewMenuItemNow(n, title.val(), "|-|", type.val(), class_name.val(), "<ol>", "</ol>");
								$('#list_'+$("#parent").val()).append(html_output);
								
							} else if (type.val() == "component") {
								html_output = addNewMenuItemNow(n, title.val(), '|'+component.val()+'|'+component_id.val()+'|', type.val(), class_name.val(), "<ol>", "</ol>");
								$('#list_'+$("#parent").val()).append(html_output);
								
							} else if (type.val() == "content") {
								html_output = addNewMenuItemNow(n, title.val(), '|'+content.val()+'|', type.val(), class_name.val(), "<ol>", "</ol>");
								$('#list_'+$("#parent").val()).append(html_output);
								
							} else {
								alert("Content Type Error!");
							}
						}
					}
					$(this).dialog("close");
					enableNotification("notify_green", "New item added succesfully", 2000);
					enableSaveButton();
				}
			},
			Cancel: function() {
				$(this).dialog("close");
			}
		},
		close: function() {
			allFields.val("").removeClass("ui-state-error");
		}
	});
	$("#add_item").button().click(function(e) {
		e.preventDefault();
		$("#create_menu_item-form").dialog("open");
		reset_CreateMenuItemForm("url");
		get_menuItems_NestedSetInput();
	});
});




/* EDIT MENU FUNCTION */
$(function() {
	var name = $( "#name_edit" ), safe_name = $( "#safe_name_edit" ), allFields = $( [] ).add( name ).add( safe_name ), tips_selector = $( ".validateTips" );
	
	$( "#edit_menu-form" ).dialog({
		autoOpen: false,
		modal: true,
		width: 350,
		draggable: false, 
		resizable: false, 
		position: ["center", 150], 
		show: "fade", 
		buttons: {
			"Finish Edit": function(e) {
				var bValid = true;
				allFields.removeClass( "ui-state-error" );
				bValid = bValid && checkLength(name, "username", 3, 16, tips_selector);
				bValid = bValid && checkLength(safe_name, "safe_name", 3, 16, tips_selector);
				bValid = bValid && checkRegexp(name, /^[a-z]([0-9a-z_ ])+$/i, "Menu name may consist of a-z, 0-9, underscores, spaces and begin with a letter.", tips_selector);
				bValid = bValid && checkRegexp(safe_name, /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores and begin with a letter.", tips_selector);
				if (bValid) {
					if ((name.val() != $("#current_menu_name").val()) || (safe_name.val() != $("#current_menu_safe_name").val())) {
						$("div.cname").html(name.val());
						$("div.csname").html(safe_name.val());
						enableNotification("notify_yellow", "Don't forget to SAVE menu alterations!", 2000);
						enableSaveButton();
						$(this).dialog("close");
					} else {
						$(this).dialog("close");
					}
				}
			},
			Cancel: function() {
				$(this).dialog("close");
			}
		},
		close: function() {
			allFields.val("").removeClass("ui-state-error");
		}
	});
	$("#edit_menu").button().click(function(e) {
		e.preventDefault();
		$("#edit_menu-form").dialog("open");
		name.val($("#current_menu_name").val());
		safe_name.val($("#current_menu_safe_name").val());
		if ($("#current_menu_safe_name").val() == "main_menu") {
			safe_name.prop('disabled', true);
		}
	});
});



/* REMOVE MENU FUNCTION */
$(function() {
	$( "#delete_menu-confirm" ).dialog({
		autoOpen: false,
		modal: true,
		width: 400,
		draggable: false, 
		resizable: false, 
		position: ["center", 150], 
		show: "fade", 
		buttons: {
			"Delete menu": function() {
				$.ajax({
					type: 'POST',
					url: menu_processor,
					data: 'task=rem_menu&menu='+currentMenu,
					success: function(data) {
						if (data == "") {
							window.location.search = jQuery.query.set("menu_selector", "main_menu");
						} else {
							alert(data);
						}
					}
				});
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
	$("#remove_menu").button().click(function(e) {
		e.preventDefault();
		currentMenu 	= $(e.target).parent("a").attr("href");
		currentMenu 	= currentMenu.substring(1);
		$("#delete_menu-confirm").dialog("open");
	});
});






/* EDIT MENU ITEM FUNCTION */
$(function() {
	var title 			= $( "#title_edit" ),
		class_name 		= $( "#class_name_edit" ), 
		type 			= $( "#type_edit" ), 
		url 			= $( "#url_edit" ), 
		component 		= $( "#component_edit" ),
		component_id 	= $( "#component_id_edit" ),		
		content 		= $( "#content_edit" ), 
		code 			= $( "#code_edit" ), 
		pdValue			= $( "#pdValue_edit" ), 
		frame			= $( "#frame_edit" ), 
		published		= $( "#published_edit" ), 
		sAccess			= $( "#sAccess_edit" ), 
		lAccess 		= $( "#lAccess_edit" ), 
		allFields 		= $([]).add(title).add(class_name).add(type).add(url).add(component).add(component_id).add(content).add(code).add(pdValue).add(frame).add(published).add(sAccess).add(lAccess),
		tips_selector 	= $( ".validateTips" );
	
	type.change(function() {
		if (type.val() == "separator") {
			$("#url_input_holder_edit").hide(); $("#component_input_holder_edit").hide(); $("#component_id_input_holder_edit").hide(); $("#content_input_holder_Edit").hide();
		} else if (type.val() == "component") {
			$("#url_input_holder_edit").hide(); $("#component_input_holder_edit").show(); $("#component_id_input_holder_edit").show(); $("#content_input_holder_Edit").hide();
		} else if (type.val() == "content") {
			$("#url_input_holder_edit").hide(); $("#component_input_holder_edit").hide(); $("#component_id_input_holder_edit").hide(); $("#content_input_holder_Edit").show();
		} else {
			$("#url_input_holder_edit").show(); $("#component_input_holder_edit").hide(); $("#component_id_input_holder_edit").hide(); $("#content_input_holder_Edit").hide();
		}
	});
	
	$( "#edit_menu_item-form" ).dialog({
		autoOpen: false,
		modal: true,
		width: 350,
		draggable: false, 
		resizable: false, 
		position: ["center", 150], 
		show: "fade",
		buttons: {
			"Update Item": function(e) {
				var bValid = true;
				allFields.removeClass( "ui-state-error" );
				bValid = bValid && checkLength(title, "title", 3, 16, tips_selector);
				bValid = bValid && checkRegexp_separated_reg(title, "^([0-9a-z"+special_chars+"_ -])+$", "i", "Title may consist of a-z, 0-9, underscores, spaces, and minus", tips_selector);
				
				if (class_name.val().length > 0) {
					bValid = bValid && checkRegexp_separated_reg(class_name, "^[a-z]([0-9a-z_])+$", "i", "Classname may consist of a-z, 0-9, underscores and begin with a letter.", tips_selector);
				}
				
				if (type.val() == "url") {
					bValid = bValid && checkLength(url, "url", 3, 50);
					if (url.val().length > 0) {
						bValid = bValid && checkRegexp_separated_reg(url, "^[a-zA-Z0-9\/.:_-]+$", "i", "URL may consist of a-z, 0-9, underscores, minus, dots and forward slash (/)", tips_selector);
					}
				}
				
				if (type.val() == "component") {
					if (component_id.val().length > 0) {
						bValid = bValid && checkRegexp_separated_reg(component_id, "^[a-zA-Z0-9_]+$", "i", "Component ID may consist of a-z, 0-9 and underscores", tips_selector);
					}
				}

				if (bValid) {
					if (type.val() == "url") {
						//editMenuItemNow(currentId, title.val(), url.val(), type.val(), class_name.val())
						editMenuItemNow(currentId, title.val(), url.val(), type.val(), class_name.val(), code.val(), pdValue.val(), frame.val(), published.val(), sAccess.val(), lAccess.val())
						
					} else if (type.val() == "separator") {
						editMenuItemNow(currentId, title.val(), "|-|", type.val(), class_name.val())
						
					} else if (type.val() == "component") {
						editMenuItemNow(currentId, title.val(), '|'+component.val()+'|'+component_id.val()+'|', type.val(), class_name.val())
						
					} else if (type.val() == "content") {
						editMenuItemNow(currentId, title.val(), '|'+content.val()+'|', type.val(), class_name.val())
						
					} else {
						alert("Content Type Error!");
					}
					
					$(this).dialog("close");
					enableNotification("notify_green", "Item changed succesfully", 2000);
					enableSaveButton();
				}
			},
			Cancel: function() {
				$(this).dialog("close");
			}
		},
		close: function() {
			allFields.val("").removeClass("ui-state-error");
		}
	});

	$(".edit_item_btn").button().live('click', function(e) {
		e.preventDefault();
		currentId 		= $(e.target).attr("href");
		currentId 		= currentId.substring(1);
		currentTitle	= $('#list_'+currentId+' input.db_title').val();
		currentClass	= $('#list_'+currentId+' input.db_class_name').val();
		currentType		= $('#list_'+currentId+' input.db_type').val();
		currentContents	= $('#list_'+currentId+' input.db_content').val();
		currentCode		= $('#list_'+currentId+' input.db_code').val();
		currentPdValue	= $('#list_'+currentId+' input.db_pdValue').val();
		currentFrame	= $('#list_'+currentId+' input.db_frame').val();
		currentPublished	= $('#list_'+currentId+' input.db_published').val();
		currentSAccess	= $('#list_'+currentId+' input.db_sAccess').val();
		currentLAccess	= $('#list_'+currentId+' input.db_lAccess').val();
		
		
		
		
		title.val(currentTitle);
		class_name.val(currentClass);
		code.val(currentCode);
		pdValue.val(currentPdValue);
		frame.val(currentFrame);
		published.val(currentPublished);
		sAccess.val(currentSAccess);
		lAccess.val(currentLAccess);
		
		
		type.val(currentType);
		if (type.val() == "separator") {
			$("#url_input_holder_edit").hide(); $("#component_input_holder_edit").hide(); $("#component_id_input_holder_edit").hide(); $("#content_input_holder_Edit").hide();
			
		} else if (type.val() == "component") {
			$("#url_input_holder_edit").hide(); $("#component_input_holder_edit").show(); $("#component_id_input_holder_edit").show(); $("#content_input_holder_Edit").hide();
			currentContents = currentContents.split("|");
			component.val(currentContents[1]);
			component_id.val(currentContents[2]);
			
		} else if (type.val() == "content") {
			$("#url_input_holder_edit").hide(); $("#component_input_holder_edit").hide(); $("#component_id_input_holder_edit").hide(); $("#content_input_holder_Edit").show();
			currentContents = currentContents.split("|");
			content.val(currentContents[1]);
			
		} else {
			$("#url_input_holder_edit").show(); $("#component_input_holder_edit").hide(); $("#component_id_input_holder_edit").hide(); $("#content_input_holder_Edit").hide();
			url.val(currentContents);
		}
		$("#edit_menu_item-form").dialog("open");
	});
});




/* REMOVE MENU ITEM FUNCTION */
$(function() {
	$( "#delete_item-confirm" ).dialog({
		autoOpen: false,
		modal: true,
		width: 400,
		draggable: false, 
		resizable: false, 
		position: ["center", 150], 
		show: "fade", 
		buttons: {
			"Delete item": function() {
				if($('#list_'+currentId+'').has("ol").length){
					alert("There are children menu items. Cannot delete!");
				} else {
					removeMenuItem(currentId);
					$( this ).dialog( "close" );
					enableNotification("notify_green", "Menu item has been succesfully removed!", 2000);
					enableSaveButton();
				}
			},
			Cancel: function() {
				$( this ).dialog( "close" );
				$('#list_'+currentId+'').removeClass("aboutToRemove");
			}
		}
	});
	$(".rem_item_btn").button().live('click', function(e) {
		e.preventDefault();
		currentId 		= $(e.target).attr("href");
		currentId 		= currentId.substring(1);
		$('#list_'+currentId+'').addClass("aboutToRemove");
		$("#delete_item-confirm").dialog("open");
	});
});


