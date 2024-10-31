<?php
/* This function creates the Settings tabs
Return:
$ary_settings_errors - Array of errors in Settings updates
------------------------------ */
function mvob_settings_tabs( $current = 'general' ) {
	$tabs = array( 'general' => 'General Settings' , 
			'css' => 'CSS Settings' ,
			'donate' => 'Donate' );
	echo '<div id="icon-themes" class="icon32"><br/></div>';
	echo '<h2 class="nav-tab-wrapper">';

	foreach( $tabs as $tab => $name ) {
		$class = ( $tab == $current ? ' nav-tab-active' : '' );
		echo '<a class="nav-tab' . $class . '" href="?page=mvob_settings&tab=' . $tab . '">' . $name . '</a>';
	}

	// Output the Settings and Donate tabs
	echo '<a class="nav-tab nav-tab-right" href="?page=mvob_groups">All Groups</a>';
	echo '<a class="nav-tab nav-tab-right" href="?page=mvob_videos">All Videos</a>';
	echo '<a class="nav-tab nav-tab-right" href="?page=mvob">Instructions</a>';

	echo '</h2>';
}


/* This function creates the settings form
Parameters:
No parameters

Return:
$str_settings_form - The HTML for the Settings form
------------------------------ */
function mvob_output_settings_form( $tab = 'general' ) {
	mvob_settings_tabs( $tab );

	switch( $tab ) {
		case "general":
			$str_settings_form = mvob_general_settings_form();
			break;
		case "css":
			$str_settings_form = mvob_css_settings_form();
			break;
		case "donate":
			global $mvob_paypal;
			$str_settings_form = $mvob_paypal->paypal_donation_form( 'large' , 0 );
			break;
		default:
			$str_settings_form = mvob_general_settings_form();
			break;
	}

	return $str_settings_form;
}


/* This function creates the General Settings form
------------------------------ */
function mvob_general_settings_form() {
	$mvob_options = get_option( 'mvob_options' );
	$str_settings_form = '<div class="form-div"><form name="mvob_form" method="post" action="' . str_replace( '%7E', '~', $_SERVER['REQUEST_URI']) . '"><input type="hidden" name="mvob_hidden_settings" value="Y">';

// Pagination field
	$str_settings_form .= '<div class="form-fieldset"><div class="form-fieldset-title">How Many Items Do You Want To Show On Admin Pages?</div>';
	$str_settings_form .= '<div class="form-fieldset-input">';
	$str_settings_form .= '<input type="text" name="mvob_pagination" value="' . $mvob_options['mvob_pagination'] . '" maxlength="10" size="10" />';
	$str_settings_form .= '</div></div>';

// Default Width field
	$str_settings_form .= '<div class="form-fieldset"><div class="form-fieldset-title">What Do You Want To Set The Default Video Display Width To?</div>';
	$str_settings_form .= '<div class="form-fieldset-input">';
	$str_settings_form .= '<input type="text" name="mvob_default_width" value="' . $mvob_options['mvob_default_width'] . '" maxlength="4" size="6" />';
	$str_settings_form .= '</div></div>';

// Default Height field
	$str_settings_form .= '<div class="form-fieldset"><div class="form-fieldset-title">What Do You Want To Set The Default Video Display Height To?</div>';
	$str_settings_form .= '<div class="form-fieldset-input">';
	$str_settings_form .= '<input type="text" name="mvob_default_height" value="' . $mvob_options['mvob_default_height'] . '" maxlength="4" size="6" />';
	$str_settings_form .= '</div></div>';

// Give Props field
	$str_settings_form .= '<div class="form-fieldset"><div class="form-fieldset-title">Do You Want To Let Others Know That This Plugin Created Your Awesome Video Display?</div>';
	$str_settings_form .= '<div class="form-fieldset-input">';
	$str_settings_form .= '<select name="mvob_give_props">';
	$str_settings_form .= '<option value="n"' . ( $mvob_options['mvob_give_props'] == "n" ? ' SELECTED' : '' ) . '>No</option>';
	$str_settings_form .= '<option value="y"' . ( $mvob_options['mvob_give_props'] == "y" ? ' SELECTED' : '' ) . '>Yes</option>';
	$str_settings_form .= '</select>';
	$str_settings_form .= '</div></div>';

// Submit buttons and close form and divs
	$str_settings_form .= '<div class="form-submit"><input type="submit" class="button-primary" name="Update Settings" value="' . __('Update Settings', 'mvob_trdom' ) . '" /> <input type="reset" class="button-secondary" name="Reset Form" value="' . __('Reset Form', 'mvob_trdom' ) . '" /></div>';
	$str_settings_form .= '</form></div>';

	return $str_settings_form;
}


/* This function validates the settings form
Return:
$ary_settings_errors - Array of errors in Settings updates
------------------------------ */
function mvob_validate_settings_form( $ary_settings ) {
	$ary_settings_errors = array();

	// Validate Default Pagination setting
	if ( !is_numeric( $_POST['mvob_pagination'] ) || ( $_POST['mvob_pagination'] < 1 ) )
		array_push( $ary_settings_errors , "The number of items per page must be greater than zero." );
	// Validate Default Width setting
	if ( !is_numeric( $_POST['mvob_default_width'] ) || ( $_POST['mvob_default_width'] < 1 ) )
		array_push( $ary_settings_errors , "The default width must be greater than zero." );
	// Validate Default Height setting
	if ( !is_numeric( $_POST['mvob_default_height'] ) || ( $_POST['mvob_default_height'] < 1 ) )
		array_push( $ary_settings_errors , "The default height must be greater than zero." );

	return $ary_settings_errors;
}


/* This function creates the CSS Settings form
------------------------------ */
function mvob_css_settings_form() {
	// Read in the Stylesheet
	$str_css = file_get_contents( MVOB_PLUGIN_DIR . "css/mvob_public_style.css" );

	$str_css_form = '<div class="css-editor"><form name="mvob_form" method="post" action="' . str_replace( '%7E', '~', $_SERVER['REQUEST_URI']) . '"><input type="hidden" name="mvob_hidden_css" value="Y">';

	$str_css_form .= '<textarea name="mvob_public_style" rows="20" cols="125">';
	$str_css_form .= $str_css;
	$str_css_form .= '</textarea>';

// Submit buttons and close form and divs
	$str_css_form .= '<div class="css-editor-buttons"><input type="submit" class="button-primary" name="Update Styles" value="' . __('Update Styles', 'mvob_trdom' ) . '" /> <input type="reset" class="button-secondary" name="Reset Form" value="' . __('Reset Form', 'mvob_trdom' ) . '" /></div></form>';
	$str_css_form .= '<div class="css-editor-reset"><form name="mvob_form" method="post" action="' . str_replace( '%7E', '~', $_SERVER['REQUEST_URI']) . '"><input type="hidden" name="mvob_hidden_reset_css" value="Y"><input type="submit" class="button-primary" name="Reset Styles To Default" value="' . __('Reset Styles To Default', 'mvob_trdom' ) . '" /></form></div>';
	$str_css_form .= '</div>';

	return $str_css_form;
}

/* This function writes a new CSS stylesheet
Return:
True if successful, False if not
------------------------------ */
function mvob_write_css( $new_css ) {
	// Make sure CSS was passed
	if ( trim( $new_css ) == "" ) { 
		return false;
	}
	else {
		$write_css = file_put_contents( MVOB_PLUGIN_DIR . "css/mvob_public_style.css" , $new_css );

		// If the write operation was successful, return true
		if ( $write_css !== false ) {
			return true;
		}
		else {
			return false;
		}
	}
}


/* This function resets the CSS to defaults
Return:
True if successful, False if not
------------------------------ */
function mvob_reset_css() {
	// Read in the Stylesheet
	$str_css = file_get_contents( MVOB_PLUGIN_DIR . "css/mvob_public_style_default.css" );

	$write_css = mvob_write_css( $str_css );

	// If the write operation was successful, return true
	if ( $write_css !== false ) {
		return true;
	}
	else {
		return false;
	}
}
?>