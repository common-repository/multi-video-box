<?php
	// If the Add button was pressed
	if ( isset( $_POST['video_add_button'] ) ) {
		// Create a Group object
		$group = new mvob_group( array( 'group_id' => $_REQUEST['group_id'] ) );

		// If a Video was chosen
		if ( isset( $_POST['video_id_add'] ) && is_array( $_POST['video_id_add'] ) ) {
			// Add the Videos to the Group
			$str_add_result = $group->add_videos_to_group( $_POST['video_id_add'] );

			// Output the confirmation message
			echo '<div class="updated"><p><strong>' . $str_add_result . '</strong></p></div>';
		}
		else {
			echo '<div class="error"><p><strong>Please select the Videos you want to assign to "' . $group->get_group_name() . '".</strong></p></div>';
		}
	}
	// If the Remove button was pressed
	else if ( isset( $_POST['video_remove_button'] ) ) {
		// Create a Group object
		$group = new mvob_group( array( 'group_id' => $_REQUEST['group_id'] ) );

		// If a Video was chosen
		if ( isset( $_POST['video_id_remove'] ) && is_array( $_POST['video_id_remove'] ) ) {
			// Remove the Video from the Groups
			$str_remove_result = $group->remove_videos_from_group( $_POST['video_id_remove'] );

			// Output the confirmation message
			echo '<div class="updated"><p><strong>' . $str_remove_result . '</strong></p></div>';
		}
		else {
			echo '<div class="error"><p><strong>Please select the Videos you want to remove from "' . $group->get_group_name() . '".</strong></p></div>';
		}
	}

	$group_form = new mvob_group_form( array( 'group_id' => $_REQUEST['group_id'] ) );
?>

<div class="wrap">
	<div class="form-div">
		<form enctype="multipart/form-data" name="mvob_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<?php echo $group_form->output_assign_form_fields(); ?>
		</form>
	</div>
</div>