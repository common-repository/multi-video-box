<?php
/* This function ties Videos and Groups together
Parameters: Requires one of the following parameter sets:
$_POST['video_id'] - The ID of the Video to add to Groups
$_POST['add_groups'] - The Groups to add

$_POST['group_id'] - The ID of the Group to add Videos to
$_POST['add_videos'] - The Videos to add
------------------------------ */
function ajax_mvob_add_videos_to_groups() {
	// If $_POST['video_id'] was passed
	if ( isset( $_POST['video_id'] ) && is_numeric( $_POST['video_id'] ) ) {
		//  If 1 or more Groups were passed, add the Groups to the Video
		if ( isset( $_POST['add_groups'] ) && ( trim( $_POST['add_groups'] ) != "" ) ) {
			// Create a Video object
			$video = new mvob_video( array( 'video_id' => $_POST['video_id'] ) );

			// Add the Groups to the Video
			$str_add_result = $video->add_video_to_groups( $_POST['add_groups'] );
		}
	}
	// If $_POST['group_id'] was passed
	else if ( isset( $_POST['group_id'] ) && is_numeric( $_POST['group_id'] ) ) {
		//  If 1 or more Videos were passed, add the Videos to the Group
		if ( isset( $_POST['add_videos'] ) && ( trim( $_POST['add_videos'] ) != "" ) ) {
			// Create a Group object
			$group = new mvob_group( array( 'group_id' => $_POST['group_id'] ) );

			// Add the Groups to the Video
			$str_add_result = $group->add_videos_to_group( $_POST['add_videos'] );
		}
	}

	echo $str_add_result;
	exit;
}

/* This function unties Videos and Groups
Parameters: Requires one of the following parameter sets:
$_POST['video_id'] - The ID of the Video to remove from Groups
$_POST['remove_groups'] - The Groups to remove

$_POST['group_id'] - The ID of the Group to remove Videos from
$_POST['remove_videos'] - The Videos to remove
------------------------------ */
function ajax_mvob_remove_videos_from_groups() {
	// If $_POST['video_id'] was passed
	if ( isset( $_POST['video_id'] ) && is_numeric( $_POST['video_id'] ) ) {
		//  If 1 or more Groups were passed, remove the Video from the Groups
		if ( isset( $_POST['remove_groups'] ) && ( trim( $_POST['remove_groups'] ) != "" ) ) {
			// Create a Video object
			$video = new mvob_video( array( 'video_id' => $_POST['video_id'] ) );

			// Remove the Video from the Groups
			$str_remove_result = $video->remove_video_from_groups( $_POST['remove_groups'] );
		}
	}
	// If $_POST['group_id'] was passed
	else if ( isset( $_POST['group_id'] ) && is_numeric( $_POST['group_id'] ) ) {
		//  If 1 or more Videos were passed, remove the Videos from the Group
		if ( isset( $_POST['remove_videos'] ) && ( trim( $_POST['remove_videos'] ) != "" ) ) {
			// Create a Group object
			$group = new mvob_group( array( 'group_id' => $_POST['group_id'] ) );

			// Remove the Videos from the Group
			$str_remove_result = $group->remove_videos_from_group( $_POST['remove_videos'] );
		}
	}

	echo $str_remove_result;
	exit;
}

/* This function gets the Video Embed code and Description for a given Video ID
Parameters:
$_POST['video_id'] - The ID of the Video 

Return:
JSON object with:
video_embed - The embed code for the Video ID
video_description - The description for the Video
------------------------------ */
function ajax_mvob_get_video_embed() {
	$nonce = $_POST['mvob_nonce'];

	// Make sure Nonce is valid
	if ( !wp_verify_nonce( $nonce , 'mvob_nonce' ) )
		die ( 'Invalid nonce' );

	// Make sure the Video ID is numeric
	if ( is_numeric( $_POST['video_id'] ) ) {
		$bin_valid_video_id = 1;

		// Get Video Description and embed code
		$video = new mvob_video( array( 'video_id' => $_POST['video_id'] ) );
		$ary_video['video_embed'] = $video->get_video_file_embed( $_POST['group_id'] );
		$ary_video['video_description'] = $video->get_video_description();
	}
	else {
		$bin_valid_video_id = 0;
	}

	if ( $bin_valid_video_id ) 
		echo json_encode( $ary_video );
	else
		echo "Invalid Video ID";

	exit;
}
?>