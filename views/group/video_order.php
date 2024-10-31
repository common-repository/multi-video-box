<?php
	$reorder_form = new mvob_group_form( array( 'group_id' => $_REQUEST['group_id'] ) );

	// Check to see if this is a form submission.  If so, validate and submit the form
	if ( isset( $_POST['mvob_hidden_order'] ) && ( $_POST['mvob_hidden_order'] == 'Y' ) ) {
		$reorder_form->reorder_videos( $_POST['video_order'] );

		// Output a Success message
		echo '<div class="updated"><p><strong>Video order set successfully.</strong></p></div>';
	}
?>

<div class="wrap">
	<div class="form-div">
		<form enctype="multipart/form-data" name="mvob_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<input type="hidden" name="mvob_hidden_order" value="Y">
			<?php 
				echo $reorder_form->output_reorder_form(); 
			?>
			<div class="form-submit"><input type="submit" class="button-primary" name="Update Video Order" value="<?php _e('Update Video Order', 'mvob_trdom' ) ?>" /></div>
		</form>
	</div>
</div>