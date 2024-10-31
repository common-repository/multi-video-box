<?php
	// Check to see if this is a form submission.  If so, validate and submit the form
	if ( isset( $_POST['mvob_hidden_add'] ) && ( $_POST['mvob_hidden_add'] == 'Y' ) ) {
		// Create a Group with the form entry
		$add_group_form = new mvob_group_form( $_POST );

		// Check to make sure the Attachment is valid
		$ary_errors = $add_group_form->check_group_data( $_FILES['group_file_name']['name'] );

		// If there are errors, output them
		if ( count( $ary_errors ) > 0 ) {
			global $error_handler;
			echo '<div class="error"><p><strong>' . $error_handler->error_array_to_string( $ary_errors ) . '</strong></p></div>';
		}
		else {
			$obj_group_insert_result = $add_group_form->add_group_to_database();

			// If the result isn't an array, the Group was added to the database
			if ( !is_array( $obj_group_insert_result ) ) {
				// If Groups were selected, add the Group to the Groups
				if ( $_POST['group_id'] != "" ) {
					$str_add_to_groups = $add_group_form->add_group_to_groups( $_POST['group_id'] );

					if ( $str_add_to_groups != "" )
						echo '<div class="updated"><p><strong>"' . $str_add_to_groups . '</strong></p></div>';
				}

				// Output a Success message
				echo '<div class="updated"><p><strong>"' . $add_group_form->get_group_name() . '" was added successfully.</strong> <input type="button" class="button-primary" name="Edit This Group" value="' . __( 'Edit This Group', 'mvob_trdom' ) . '" onClick="parent.location=\'admin.php?page=mvob_groups&action=edit&group_id=' . $add_group_form->get_group_id() . '\'" /></p></div>';

				// Create an instance of an input form
				$add_group_form = new mvob_group_form();
			}
			// Otherwise, output an error message
			else {
				echo '<div class="error"><p><strong>' . error_array_to_string( $obj_group_insert_result ) . '</strong></p></div>';
			}
		}
	}
	// If this is not a form submission, create a default Group
	else {
		// Create an instance of an input form
		$add_group_form = new mvob_group_form();
	}
?>

<div class="wrap">
	<div class="form-div">
		<form enctype="multipart/form-data" name="mvob_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<input type="hidden" name="mvob_hidden_add" value="Y">
			<?php echo $add_group_form->output_form_fields( 'add' ); ?>
			<div class="form-submit"><input type="submit" class="button-primary" name="Add Group" value="<?php _e('Add Group', 'mvob_trdom' ) ?>" /> <input type="reset" class="button-secondary" name="Reset Form" value="<?php _e('Reset Form', 'mvob_trdom' ) ?>" /> | <a href="admin.php?page=mvob_groups">Return To Groups</a></div>
		</form>
	</div>
</div>