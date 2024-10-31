<?php
	// Check to see if this is a form submission.  If so, validate and submit the form
	if ( isset( $_POST['mvob_hidden_edit'] ) && ( $_POST['mvob_hidden_edit'] == 'Y' ) ) {
		// Create a Group with the form entry
		$edit_group_form = new mvob_group_form( $_POST );

		// Check to make sure the Group is valid
		$ary_errors = $edit_group_form->check_group_data( 'edit' );

		// If there are errors, output them
		if ( count( $ary_errors ) > 0 ) {
			global $error_handler;
			echo '<div class="error"><p><strong>' . $error_handler->error_array_to_string( $ary_errors ) . '</strong></p></div>';
		}
		else {
			$obj_group_update_result = $edit_group_form->update_group();

			// If the result isn't an array, the Group was successfully updated in the database
			if ( !is_array( $obj_group_update_result ) ) {
				// Output a Success message
				echo '<div class="updated"><p><strong>"' . $edit_group_form->get_group_name() . '" was updated successfully.</strong></p></div>';
			}
			// Otherwise, output an error message
			else {
				echo '<div class="error"><p><strong>' . error_array_to_string( $obj_group_update_result ) . '</strong></p></div>';
			}
		}
	}
	// If this is not a form submission, create a default Group
	else {
		// Create an instance of an input form
		$atts['group_id'] = $_REQUEST['group_id'];
		$edit_group_form = new mvob_group_form( $atts );
	}
?>

<div class="wrap">
	<div class="form-div">
		<form enctype="multipart/form-data" name="mvob_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<input type="hidden" name="mvob_hidden_edit" value="Y">
			<?php echo $edit_group_form->output_form_fields( 'edit' ); ?>
			<div class="form-submit"><input type="submit" class="button-primary" name="Update Group" value="<?php _e('Update Group', 'wpcam_trdom' ) ?>" /> <input type="reset" class="button-secondary" name="Reset Form" value="<?php _e('Reset Form', 'wpcam_trdom' ) ?>" /> | <a href="admin.php?page=mvob_groups">Return To Groups</a></div>
		</form>
	</div>
</div>