<?php
	// If the Add button was pressed
	if ( isset( $_POST['group_add_button'] ) ) {
		$video = new mvob_video( array( 'video_id' => $_REQUEST['video_id'] ) );

		// If a Group was chosen
		if ( isset( $_POST['group_id_add'] ) && is_array( $_POST['group_id_add'] ) ) {
			// Add the Video to the Groups
			$str_add_result = $video->add_video_to_groups( $_POST['group_id_add'] );

			// Output the confirmation message
			echo '<div class="updated"><p><strong>' . $str_add_result . '</strong></p></div>';
		}
		else {
			echo '<div class="error"><p><strong>Please select the Groups you want to assign "' . $video->get_video_name() . '" to.</strong></p></div>';
		}
	}
	// If the Remove button was pressed
	else if ( isset( $_POST['group_remove_button'] ) ) {
		$video = new mvob_video( array( 'video_id' => $_REQUEST['video_id'] ) );

		// If a Group was chosen
		if ( isset( $_POST['group_id_remove'] ) && is_array( $_POST['group_id_remove'] ) ) {
			// Remove the Video from the Groups
			$str_remove_result = $video->remove_video_from_groups( $_POST['group_id_remove'] );

			// Output the confirmation message
			echo '<div class="updated"><p><strong>' . $str_remove_result . '</strong></p></div>';
		}
		else {
			echo '<div class="error"><p><strong>Please select the Groups you want to remove "' . $video->get_video_name() . '" from.</strong></p></div>';
		}
	}

	$video_form = new mvob_video_form( array( 'video_id' => $_REQUEST['video_id'] ) );
?>

<div class="wrap">
	<div class="form-div">
		<form enctype="multipart/form-data" name="mvob_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<?php echo $video_form->output_assign_form_fields(); ?>
		</form>
	</div>
</div>