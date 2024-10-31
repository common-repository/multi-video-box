<?php
/* Display functions based on menu selection and URL
------------------------------ */
function mvob_menu_main_page () {
	if ( trim( $_REQUEST['action'] ) == "" ) {
		$action = "display";
	}
	else {
		$action = $_REQUEST['action'];
	}

	if ( $action == "display" ) { 
		include( MVOB_PLUGIN_DIR . 'views/common/instructions.php' );
	}
	else if ( $action == "shortcode" ) { 
		include( MVOB_PLUGIN_DIR . 'views/common/instructions_shortcode.php' );
	}
}

function mvob_menu_videos () {
	mvob_navigation_tabs();

	if ( trim( $_REQUEST['action'] ) == "" ) {
		$action = "display";
	}
	else {
		$action = $_REQUEST['action'];
	}

	if ( $action == "display" ) { 
		include( MVOB_PLUGIN_DIR . 'views/video/display_videos.php' );
	}
	else if ($action == "add") {
		include( MVOB_PLUGIN_DIR . 'views/video/add_video.php' );
	}
	else if ($action == "edit") {
		include( MVOB_PLUGIN_DIR . 'views/video/edit_video.php' );
	}
	else if ($action == "delete") {
		include( MVOB_PLUGIN_DIR . 'views/video/delete_video.php' );
	}
	else if ($action == "assign-groups") {
		include( MVOB_PLUGIN_DIR . 'views/video/add_video_to_groups.php' );
	}
	else if ($action == "get-shortcode") {
		include( MVOB_PLUGIN_DIR . 'views/video/get_shortcode.php' );
	}
}

function mvob_menu_groups () {
	mvob_navigation_tabs();

	if ( trim( $_REQUEST['action'] ) == "" ) {
		$action = "display";
	}
	else {
		$action = $_REQUEST['action'];
	}

	if ( $action == "display" ) { 
		include( MVOB_PLUGIN_DIR . 'views/group/display_groups.php' );
	}
	else if ($action == "add") {
		include( MVOB_PLUGIN_DIR . 'views/group/add_group.php' );
	}
	else if ($action == "edit") {
		include( MVOB_PLUGIN_DIR . 'views/group/edit_group.php' );
	}
	else if ($action == "delete") {
		include( MVOB_PLUGIN_DIR . 'views/group/delete_group.php' );
	}
	else if ($action == "get-shortcode") {
		include( MVOB_PLUGIN_DIR . 'views/group/get_shortcode.php' );
	}
	else if ($action == "assign-videos") {
		include( MVOB_PLUGIN_DIR . 'views/group/add_videos_to_group.php' );
	}
	else if ($action == "videos-order") {
		include( MVOB_PLUGIN_DIR . 'views/group/video_order.php' );
	}
}

function mvob_menu_settings () {
	if ( trim( $_REQUEST['action'] ) == "" ) {
		$action = "display";
	}
	else {
		$action = $_REQUEST['action'];
	}

	if ( $action == "display" ) { // Display the main Settings page
		include( MVOB_PLUGIN_DIR . 'views/common/mvob_settings.php' );
	}
}
?>