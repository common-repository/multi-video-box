<?php
	// If $_GET['tab'] isn't set or is set and is 'general', validate the settings
	if ( !isset( $_GET['tab'] ) || ( isset( $_GET['tab'] ) && ( $_GET['tab'] == "general" ) ) ) {
		// Check to see if this is a form submission.  If so, validate and submit the form
		if ( isset( $_POST['mvob_hidden_settings']) && ( $_POST['mvob_hidden_settings'] == 'Y' ) ) {
			// Validate the POST variables
			$ary_settings_errors = mvob_validate_settings_form( $_POST );

			// If there are no errors, update the options and output a success message
			if ( count( $ary_settings_errors ) == 0 ) {
				$mvob_options = get_option( 'mvob_options' );

				$mvob_options['mvob_pagination'] = $_POST['mvob_pagination'];
				$mvob_options['mvob_default_width'] = $_POST['mvob_default_width'];
				$mvob_options['mvob_default_height'] = $_POST['mvob_default_height'];
				$mvob_options['mvob_give_props'] = $_POST['mvob_give_props'];

				update_option( 'mvob_options' , $mvob_options );

				echo '<div class="updated"><p><strong>Settings updated</strong></p></div>';
			}
			else {
				global $error_handler;
				echo '<div class="error"><p><strong>' . $error_handler->error_array_to_string( $ary_settings_errors ) . '</strong></p></div>';
			}
		}
	}
	// If $_GET['tab'] is css, validate the CSS
	else if ( isset( $_GET['tab'] ) && ( $_GET['tab'] == "css" ) ) {
		// If the user is submitting updated CSS, write the file
		if ( isset( $_POST['mvob_hidden_css'] ) && ( $_POST['mvob_hidden_css'] == "Y" ) ) {
			$bin_write_css = mvob_write_css( $_POST['mvob_public_style'] );

			if ( $bin_write_css === true ) {
				echo '<div class="updated"><p><strong>Stylesheet updated</strong></p></div>';
			}
			else {
				echo '<div class="error"><p><strong>There was an error updating the stylesheet.</strong></p></div>';
			}
		}
		else if ( isset( $_POST['mvob_hidden_reset_css'] ) && ( $_POST['mvob_hidden_reset_css'] == "Y" ) ) {
			$bin_reset_css = mvob_reset_css();

			if ( $bin_reset_css === true ) {
				echo '<div class="updated"><p><strong>Stylesheet set to default</strong></p></div>';
			}
			else {
				echo '<div class="error"><p><strong>There was an error resetting the stylesheet.</strong></p></div>';
			}
		}
	}
?>

<div class="wrap">
	<?php 
		if ( isset ( $_GET['tab'] ) )
			echo mvob_output_settings_form( $_GET['tab'] ); 
		else
			echo mvob_output_settings_form(); 
	?>
</div>