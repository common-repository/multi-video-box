<?php
	// Check to see if this is a Delete form submission.  If so, delete the Group
	if( $_POST['mvob_hidden_delete'] == 'Y' ) {
		// Delete the Group
		$obj_group_update_result = mvob_group::delete_group( $_POST['group_id'] );

		// Send the user back to the Groups main page
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=admin.php?page=mvob_groups&message=group-deleted">';
		exit();
	}
	// If this is not a form submission, get the Group by $_REQUEST['group_id']
	else {
		$atts['group_id'] = $_REQUEST['group_id'];
		$delete_group = new mvob_group( $atts );
	}
?>
<div class="wrap">
	<p><strong>Are you sure you want to delete "<?php echo $delete_group->get_group_name(); ?>"?</strong></p>
	<p><div class="warning-red">This action cannot be undone.</div></p>
	<div class="form-div">
		<form enctype="multipart/form-data" name="mvob_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<input type="hidden" name="mvob_hidden_delete" value="Y">
			<input type="hidden" name="group_id" value="<?php echo $delete_group->get_group_id(); ?>">
			<div class="form-submit"><input type="submit" class="button-red" name="Delete Group" value="<?php _e('Delete Group', 'mvob_trdom' ) ?>" /> | <a href="admin.php?page=mvob_groups">Return To Groups</a></div>
		</form>
	</div>
</div>