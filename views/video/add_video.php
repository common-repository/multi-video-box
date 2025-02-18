<?php
	// Check to see if this is a form submission.  If so, validate and submit the form
	if ( isset( $_POST['mvob_hidden_add'] ) && ( $_POST['mvob_hidden_add'] == 'Y' ) ) {
		// Create a Video with the form entry
		$add_video_form = new mvob_video_form( $_POST );

		// Check to make sure the Attachment is valid
		$ary_errors = $add_video_form->check_video_data( $_FILES['video_file_name']['name'] );

		// If there are errors, output them
		if ( count( $ary_errors ) > 0 ) {
			global $error_handler;
			echo '<div class="error"><p><strong>' . $error_handler->error_array_to_string( $ary_errors ) . '</strong></p></div>';
		}
		else {
			$obj_video_insert_result = $add_video_form->add_video_to_database();

			// If the result isn't an array, the Video was added to the database
			if ( !is_array( $obj_video_insert_result ) ) {
				// If Groups were selected, add the Video to the Groups
				if ( $_POST['group_id'] != "" ) {
					$str_add_to_groups = $add_video_form->add_video_to_groups( $_POST['group_id'] );

					if ( $str_add_to_groups != "" )
						echo '<div class="updated"><p><strong>' . $str_add_to_groups . '</strong></p></div>';
				}

				// Output a Success message
				echo '<div class="updated"><p><strong>"' . $add_video_form->get_video_name() . '" was added successfully.</strong> <input type="button" class="button-primary" name="Edit This Video" value="' . __( 'Edit This Video', 'mvob_trdom' ) . '" onClick="parent.location=\'admin.php?page=mvob_videos&action=edit&video_id=' . $add_video_form->get_video_id() . '\'" /></p></div>';

				// Create an instance of an input form
				$add_video_form = new mvob_video_form();
			}
			// Otherwise, output an error message
			else {
				echo '<div class="error"><p><strong>' . error_array_to_string( $obj_video_insert_result ) . '</strong></p></div>';
			}
		}
	}
	// If this is not a form submission, create a default Video
	else {
		// Create an instance of an input form
		$add_video_form = new mvob_video_form();
	}
?>

<div class="wrap">
	<div class="form-div">
		<form enctype="multipart/form-data" name="mvob_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<input type="hidden" name="mvob_hidden_add" value="Y">
			<?php echo $add_video_form->output_form_fields( 'add' ); ?>
			<div class="form-submit"><input type="submit" class="button-primary" name="Add Video" value="<?php _e('Add Video', 'mvob_trdom' ) ?>" /> <input type="reset" class="button-secondary" name="Reset Form" value="<?php _e('Reset Form', 'mvob_trdom' ) ?>" /> | <a href="admin.php?page=mvob_videos">Return To Videos</a></div>
		</form>
	</div>
</div>