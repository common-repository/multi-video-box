<?php
/* This function creates the Settings tabs
------------------------------ */
function mvob_navigation_tabs() {
	$tabs = array( 'videos' => array(
				'tab_name' => 'All Videos',
				'tab_url' => 'page=mvob_videos',
				'current_tab' => 'n'
				),
			'groups' => array(
				'tab_name' => 'All Groups',
				'tab_url' => 'page=mvob_groups',
				'current_tab' => 'n'
				)
			);

	// If this is the Videos tab, determine if it's an add, edit, delete, or assign to groups page
	if ( isset( $_REQUEST['page'] ) && ( $_REQUEST['page'] == 'mvob_videos' ) ) {
		// If this is an Add page, add the tab for it
		if ( isset( $_REQUEST['action'] ) && ( $_REQUEST['action'] == 'add' ) ) {
			$action_tab = array(
				'tab_name' => 'Add A Video',
				'tab_url' => 'page=' . $_REQUEST['page'] . '&action=' . $_REQUEST['action'],
				'current_tab' => 'y'
			);
			$tabs['action_tab'] = $action_tab;
		}
		// If this is an Edit page, add the tab for it
		else if ( isset( $_REQUEST['action'] ) && ( $_REQUEST['action'] == 'edit' ) ) {
			$action_tab = array(
				'tab_name' => 'Edit Video: ' . mvob_video::get_video_name_by_id( $_REQUEST['video_id'] ),
				'tab_url' => 'page=' . $_REQUEST['page'] . '&action=' . $_REQUEST['action'] . '&video_id=' . $_REQUEST['video_id'],
				'current_tab' => 'y'
			);
			$tabs['action_tab'] = $action_tab;		
		}
		// If this is a Delete page, add the tab for it
		else if ( isset( $_REQUEST['action'] ) && ( $_REQUEST['action'] == 'delete' ) ) {
			$action_tab = array(
				'tab_name' => 'Delete Video: ' . mvob_video::get_video_name_by_id( $_REQUEST['video_id'] ),
				'tab_url' => 'page=' . $_REQUEST['page'] . '&action=' . $_REQUEST['action'] . '&video_id=' . $_REQUEST['video_id'],
				'current_tab' => 'y'
			);
			$tabs['action_tab'] = $action_tab;
		}
		// If this is an Assign To Groups page, add the tab for it
		else if ( isset( $_REQUEST['action'] ) && ( $_REQUEST['action'] == 'assign-groups' ) ) {
			$action_tab = array(
				'tab_name' => 'Assign To Groups: ' . mvob_video::get_video_name_by_id( $_REQUEST['video_id'] ),
				'tab_url' => 'page=' . $_REQUEST['page'] . '&action=' . $_REQUEST['action'] . '&video_id=' . $_REQUEST['video_id'],
				'current_tab' => 'y'
			);
			$tabs['action_tab'] = $action_tab;
		}
		// If this is a Get Shortcodes page, add the tab for it
		else if ( isset( $_REQUEST['action'] ) && ( $_REQUEST['action'] == 'get-shortcode' ) ) {
			$action_tab = array(
				'tab_name' => 'Shortcode: ' . mvob_video::get_video_name_by_id( $_REQUEST['video_id'] ),
				'tab_url' => 'page=' . $_REQUEST['page'] . '&action=' . $_REQUEST['action'] . '&video_id=' . $_REQUEST['video_id'],
				'current_tab' => 'y'
			);
			$tabs['action_tab'] = $action_tab;
		}
		// Otherwise, this is the main Videos page
		else {
			// Set the main Videos tab to current
			$tabs['videos']['current_tab'] = 'y';
		}
	}
	// If this is the Groups tab, determine if it's an add, edit, delete, or assign videos page
	else if ( isset( $_REQUEST['page'] ) && ( $_REQUEST['page'] == 'mvob_groups' ) ) {
		// If this is an Add page, add the tab for it
		if ( isset( $_REQUEST['action'] ) && ( $_REQUEST['action'] == 'add' ) ) {
			$action_tab = array(
				'tab_name' => 'Add A Group',
				'tab_url' => 'page=' . $_REQUEST['page'] . '&action=' . $_REQUEST['action'],
				'current_tab' => 'y'
			);
			$tabs['action_tab'] = $action_tab;
		}
		// If this is an Edit page, add the tab for it
		else if ( isset( $_REQUEST['action'] ) && ( $_REQUEST['action'] == 'edit' ) ) {
			$action_tab = array(
				'tab_name' => 'Edit Group: ' . mvob_group::get_group_name_by_id( $_REQUEST['group_id'] ),
				'tab_url' => 'page=' . $_REQUEST['page'] . '&action=' . $_REQUEST['action'] . '&group_id=' . $_REQUEST['group_id'],
				'current_tab' => 'y'
			);
			$tabs['action_tab'] = $action_tab;		
		}
		// If this is a Delete page, add the tab for it
		else if ( isset( $_REQUEST['action'] ) && ( $_REQUEST['action'] == 'delete' ) ) {
			$action_tab = array(
				'tab_name' => 'Delete Group: ' . mvob_group::get_group_name_by_id( $_REQUEST['group_id'] ),
				'tab_url' => 'page=' . $_REQUEST['page'] . '&action=' . $_REQUEST['action'] . '&group_id=' . $_REQUEST['group_id'],
				'current_tab' => 'y'
			);
			$tabs['action_tab'] = $action_tab;
		}
		// If this is an Assign To Groups page, add the tab for it
		else if ( isset( $_REQUEST['action'] ) && ( $_REQUEST['action'] == 'assign-videos' ) ) {
			$action_tab = array(
				'tab_name' => 'Add Videos To Group: ' . mvob_group::get_group_name_by_id( $_REQUEST['group_id'] ),
				'tab_url' => 'page=' . $_REQUEST['page'] . '&action=' . $_REQUEST['action'] . '&group_id=' . $_REQUEST['group_id'],
				'current_tab' => 'y'
			);
			$tabs['action_tab'] = $action_tab;
		}
		// If this is a Get Shortcodes page, add the tab for it
		else if ( isset( $_REQUEST['action'] ) && ( $_REQUEST['action'] == 'get-shortcode' ) ) {
			$action_tab = array(
				'tab_name' => 'Shortcode: ' . mvob_group::get_group_name_by_id( $_REQUEST['group_id'] ),
				'tab_url' => 'page=' . $_REQUEST['page'] . '&action=' . $_REQUEST['action'] . '&group_id=' . $_REQUEST['group_id'],
				'current_tab' => 'y'
			);
			$tabs['action_tab'] = $action_tab;
		}
		// If this is an Order Videos page, add the tab for it
		else if ( isset( $_REQUEST['action'] ) && ( $_REQUEST['action'] == 'videos-order' ) ) {
			$action_tab = array(
				'tab_name' => 'Set Video Order: ' . mvob_group::get_group_name_by_id( $_REQUEST['group_id'] ),
				'tab_url' => 'page=' . $_REQUEST['page'] . '&action=' . $_REQUEST['action'] . '&group_id=' . $_REQUEST['group_id'],
				'current_tab' => 'y'
			);
			$tabs['action_tab'] = $action_tab;
		}
		// Otherwise, this is the main Videos page
		else {
			// Set the main Videos tab to current
			$tabs['groups']['current_tab'] = 'y';
		}
	}

	echo '<div id="icon-themes" class="icon32"><br/></div>';
	echo '<h2 class="nav-tab-wrapper">';

	foreach( $tabs as $tab => $values ) {
		echo '<a class="nav-tab' . ( $values['current_tab'] == 'y' ? ' nav-tab-active' : '' ) . '" href="?' . $values['tab_url'] . '">' . $values['tab_name'] . '</a>';
	}

	// Output the Settings and Donate tabs
	echo '<a class="nav-tab nav-tab-right" href="?page=mvob_settings&tab=donate">Donate</a>';
	echo '<a class="nav-tab nav-tab-right" href="?page=mvob_settings">Settings</a>';
	echo '<a class="nav-tab nav-tab-right" href="?page=mvob">Instructions</a>';

	echo '</h2>';
}
?>