<?php
	// Check to see if this is a Delete form submission.  If so, delete the Video
	if( $_POST['mvob_hidden_delete'] == 'Y' ) {
		// Delete the Video
		$obj_video_update_result = mvob_video::delete_video( $_POST['video_id'] );

		// Send the user back to the Videos main page
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=admin.php?page=mvob_videos&message=video-deleted">';
		exit();
	}
	// If this is not a form submission, get the Video by $_REQUEST['video_id']
	else {
		$atts['video_id'] = $_REQUEST['video_id'];
		$delete_video = new mvob_video( $atts );
	}
?>
<div class="wrap">
	<p><strong>Are you sure you want to delete "<?php echo $delete_video->get_video_name(); ?>"?</strong></p>
	<p><div class="warning-red">This action cannot be undone.</div></p>
	<div class="form-div">
		<form enctype="multipart/form-data" name="mvob_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<input type="hidden" name="mvob_hidden_delete" value="Y">
			<input type="hidden" name="video_id" value="<?php echo $delete_video->get_video_id(); ?>">
			<div class="form-submit"><input type="submit" class="button-red" name="Delete Video" value="<?php _e('Delete Video', 'mvob_trdom' ) ?>" /> | <a href="admin.php?page=mvob_videos">Return To Videos</a></div>
		</form>
	</div>
</div>