function show_hide_instruction_div ( div_name , show_or_hide ) {
	the_div = document.getElementById( div_name );

	if ( show_or_hide == "show" ) {
		the_div.style.display = "inline";
	}
	else { 
		the_div.style.display = "none";
	}
}

// These functions move Select list items up and down the list
// Adapted from http://blog.pothoven.net/2006/10/move-options-up-and-down-select-lists.html
function move_options_up ( select_id , hidden_id ) {
	var selectList = document.getElementById( select_id );
	var hiddenID = document.getElementById( hidden_id );
	var selectOptions = selectList.getElementsByTagName( 'option' );

	for (var i = 1; i < selectOptions.length; i++) {
		var opt = selectOptions[i];
		if (opt.selected) {
			selectList.removeChild(opt);
			selectList.insertBefore(opt, selectOptions[i - 1]);
		}
	}

	for (var i = 0; i < selectOptions.length; i++) {
		if ( i != 0 ) {
			var list_order = list_order + "," + selectOptions[i].value;
		}
		else {
			var list_order = selectOptions[i].value;
		}
	}
	hiddenID.value = list_order;
}

function move_options_down ( select_id , hidden_id ) {
	var selectList = document.getElementById( select_id );
	var hiddenID = document.getElementById( hidden_id );
	var selectOptions = selectList.getElementsByTagName( 'option' );

	for (var i = selectOptions.length - 2; i >= 0; i--) {
		var opt = selectOptions[i];
		if (opt.selected) {
			var nextOpt = selectOptions[i + 1];
			opt = selectList.removeChild(opt);
			nextOpt = selectList.replaceChild(opt, nextOpt);
			selectList.insertBefore(nextOpt, opt);
		}
	}

	for (var i = 0; i < selectOptions.length; i++) {
		if ( i != 0 ) {
			var list_order = list_order + "," + selectOptions[i].value;
		}
		else {
			var list_order = selectOptions[i].value;
		}
	}
	hiddenID.value = list_order;
}