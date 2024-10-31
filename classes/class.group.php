<?php
// ------------------------------------------------------------------------
// This file declares the mvob_group class.
// ------------------------------------------------------------------------
class mvob_group {
/* - - - - - - - - - - - - - - - - - - - 
Attributes
- - - - - - - - - - - - - - - - - - - - */
	protected $group_id;			// The Group ID
	protected $group_name;			// The name of the Group
	protected $group_display_width;		// The display width for videos in this Group
	protected $group_display_height;	// The display height for videos in this Group
	protected $group_checked;		// Has the Group been run through check_group_data successfully?

/* - - - - - - - - - - - - - - - - - - - 
Constructor & Group Retrieval Functions
- - - - - - - - - - - - - - - - - - - - */
	/* Constructor function to create a new Group instance
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function __construct( $atts = '' ) {
		// If there is a Video ID, get the Video information from the database
		if ( ( trim( $atts['group_id'] ) != "" ) && is_numeric( $atts['group_id'] ) && ( trim( $atts['group_name'] == "" ) ) ) {
			global $wpdb;

			// Get the record matching $atts['video_id']
			$qry_get_group = $wpdb->get_row( $wpdb->prepare( 
				"SELECT *
				FROM " . MVOB_GRPS_TABLE_NAME . "
				WHERE group_id = %d" , $atts['group_id'] ) );

			// If a Video was found, set it into $this
			if ( $qry_get_group != NULL ) {
				$this->group_id = $qry_get_group->group_id;
				$this->group_name = $qry_get_group->group_name;
				$this->group_display_width = $qry_get_group->group_display_width;
				$this->group_display_height = $qry_get_group->group_display_height;
				$this->group_checked = 1;
			}
			else {
				echo "The Group ID is invalid";
			}
		}
		// If $atts were passed, but Video ID wasn't passed, create a Video with the passed values
		else if ( is_array( $atts ) ) {
			$this->group_id = $atts['group_id'];
			$this->group_name = stripslashes_deep( $atts['group_name'] );
			$this->group_display_width = $atts['group_display_width'];
			$this->group_display_height = $atts['group_display_height'];
			$this->group_checked = 0;
		}
		// Otherwise, create a Video with defaults
		else {
			$this->group_id = '';
			$this->group_name = '';
			$this->group_display_width = MVOB_DEFAULT_WIDTH;
			$this->group_display_height = MVOB_DEFAULT_HEIGHT;
			$this->group_checked = 0;
		}
	}

	/* This function retrieves all Group IDs that match WHERE parameter
	Parameters:
	$video_id - The ID of the Video for which to find associated Groups

	Returns:
	$ary_group_ids - Array with Group IDs or False
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public static function get_group_ids( $video_id = '' ) {
		global $wpdb;

		$str_where_clause = '';

		// If a Video ID was passed, add a WHERE clause to restrict the query
		if ( ( trim( $video_id ) != "" ) && is_numeric( $video_id ) ) {
			$str_where_clause .= ' WHERE group_id IN ( SELECT group_id FROM ' . MVOB_VTOG_TABLE_NAME . ' WHERE video_id = ' . $video_id . ')';
		}

		// Get all Group IDs
		$qry_get_groups = $wpdb->get_results( $wpdb->prepare( 
			"SELECT group_id
			FROM " . MVOB_GRPS_TABLE_NAME . $str_where_clause . "
			ORDER BY group_id" , "" ) );

		// If Group IDs were found, push them into an array and return the array
		if ( count( $qry_get_groups ) > 0 ) {
			$ary_group_ids = array();
			foreach( $qry_get_groups AS $group ) {
				array_push( $ary_group_ids , $group->group_id );
			}

			// Return the array
			return $ary_group_ids;
		}
		// If no Group IDs were found, return false
		else {
			return false;
		}
	}

/* - - - - - - - - - - - - - - - - - - - 
Add, Update, & Delete Group Functions
- - - - - - - - - - - - - - - - - - - - */
	/* This function inserts $this Group into the database after running check_group_data to ensure data is valid
	Returns:
	$group_id - The Group ID from the database insert or an error
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function add_group_to_database() {
		// Make sure check_course_data has been run successfully
		if ( $this->group_checked != 1 ) {
			return "Please run the check_group_data function before adding this Group to the database.";
		}
		// Otherwise, there are no errors
		else {
			// Add $this to the database
			global $wpdb;

			// Find any rows with the same Group Name
			$qry_insert_group = $wpdb->insert(
							MVOB_GRPS_TABLE_NAME,
							array(
								'group_name' => $this->group_name,
								'group_display_width' => $this->group_display_width,
								'group_display_height' => $this->group_display_height
							),
							array(
								'%s',
								'%d',
								'%d'
							)
						);
			$this->group_id = $wpdb->insert_id;

			// Return Group ID
			return $this->group_id;
		}
	}

	/* This function updates $this Group after running check_group_data to ensure data is valid
	Returns:
	True if update was successful, false if not.
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function update_group() {
		// Run the data check function
		$ary_data_errors = $this->check_group_data();

		// If an array of errors was passed back, return the array and do not insert
		if ( count( $ary_data_errors ) > 0 ) {
			return $ary_data_errors;
		}
		// Otherwise, there are no errors
		else {
			// Update the Group
			global $wpdb;
			$qry_update_group = $wpdb->update(
							MVOB_GRPS_TABLE_NAME,
							array(
								'group_name' => $this->group_name,
								'group_display_width' => $this->group_display_width,
								'group_display_height' => $this->group_display_height
							),
							array( 'group_id' => $this->group_id ),
							array(
								'%s',
								'%d',
								'%d'
							),
							array( '%d' )
						);

			// If the Group was successfully updated, return true
			if( $qry_update_group > 0 ) {
				return true;
			}
			else {
				return false;
			}
		}
	}

	/* This function deletes a Group (and associated Attachments) based on a Group ID
	Parameters:
	$group_id - Numeric group ID

	Returns:
	True
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public static function delete_group( $group_id ) {
		// Ensure Group ID is numeric
		if( !is_numeric( $group_id ) ) {
			return "Group ID must be numeric.";
		}

		global $wpdb;

		// Delete the ties to Videos
		$qry_delete_video = $wpdb->query( $wpdb->prepare( 
			"DELETE FROM " . MVOB_VTOG_TABLE_NAME . "
			WHERE group_id = %d" , $group_id ) );

		// Delete the Group record
		$qry_delete_group = $wpdb->query( $wpdb->prepare( 
			"DELETE FROM " . MVOB_GRPS_TABLE_NAME . "
			WHERE group_id = %d" , $group_id ) );

		return true;
	}

	/* This function returns a query of Videos corresponding to the input parameters
	Parameters:
	$assigned - Return Videos that are assigned/unassigned to this Group.  Accepts "assigned" (default), "unassigned"
	$return_type - What type of data should be returned? 'query' returns the entire query, 'list' returns a list of the Video IDs

	Returns: 1 of the following, based on $return_type
	A query of Videos - Includes Video ID, Video Name, Video Description, Video URL
	A list of Video IDs
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function get_group_videos( $assigned = 'assigned' , $return_type = 'query' ) {
		global $wpdb;

		// Run the query based on $assigned
		switch ( $assigned ) {
			case "unassigned":
				$qry_group_videos = $wpdb->get_results( $wpdb->prepare( 
					"SELECT vids.video_id, video_name, video_description, video_url
					FROM " . MVOB_VIDS_TABLE_NAME . " vids
					WHERE video_id NOT IN (SELECT video_id FROM " . MVOB_VTOG_TABLE_NAME . "
								WHERE group_id = %d)
					ORDER BY video_id" , $this->group_id ) );
				break;

			default: // If $assigned == "assigned" or any other value
				$qry_group_videos = $wpdb->get_results( $wpdb->prepare( 
					"SELECT vids.video_id, video_name, video_description, video_url
					FROM " . MVOB_VIDS_TABLE_NAME . " vids
						JOIN " . MVOB_VTOG_TABLE_NAME . " vtog
							ON vids.video_id = vtog.video_id
					WHERE vtog.group_id = %d
					ORDER BY video_order ASC" , $this->group_id ) );
		}

		if ( $return_type == "query" ) {
			return $qry_group_videos;
		}
		else {
			// Create an array of the Groups
			$ary_assigned_videos = array();
			foreach ( $qry_group_videos AS $video ) {
				array_push( $ary_assigned_videos, $video->video_id );
			}

			// Implode into a list and return
			return implode( "," , $ary_assigned_videos );
		}
	}

	/* This function assigns Videos to $this Group
	Parameters:
	$videos - Form field input of Video IDs

	Returns: 
	Text string
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function add_videos_to_group( $videos ) {
		global $wpdb;

		// Loop the array of Videos to add
		if ( count( $videos ) > 0 ) {
			foreach ( $videos AS $video ) {
				// Create a Video object to check if the Video is already assigned to the Group
				$current_video = new mvob_video( array( 'video_id' => $video ) );

				// If the Video can be added to this Group, add it
				if ( $current_video->is_video_in_group( $this->group_id ) === false ) {
					// Keep track of how many Videos were added
					$num_total_videos_added += 1;

					// Set the Video Order for this Video
					$video_order = $this->count_group_videos() + 1;

					$wpdb->insert( MVOB_VTOG_TABLE_NAME ,
						array(
							'video_id' => $video,
							'group_id' => $this->group_id,
							'video_order' => $video_order
						)
					);
				}
			}
		}

		return $num_total_videos_added . ( $num_total_videos_added != 1 ? ' Videos' : ' Video' ) . ' added to "' . $this->group_name . '"';
	}

	/* This function removes Videos from $this Group
	Parameters:
	$videos - Form field input of Video IDs

	Returns: 
	Text string
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function remove_videos_from_group( $videos ) {
		global $wpdb;

		// Loop the array of Videos to remove
		if ( count( $videos ) > 0 ) {
			foreach ( $videos AS $video ) {
				// Keep track of how many Videos were removed
				$num_total_videos_removed += 1;

				$qry_remove_video = $wpdb->query( $wpdb->prepare(
					"DELETE FROM " . MVOB_VTOG_TABLE_NAME . " 
					WHERE video_id = %d
					AND group_id = %d" , $video , $this->group_id ) );
			}
		}

		$this->reorder_videos( $this->get_group_videos( 'assigned' , 'list' ) );

		return $num_total_videos_removed . ( $num_total_videos_removed != 1 ? ' Videos' : ' Video' ) . ' removed from "' . $this->group_name . '"';
	}

/* - - - - - - - - - - - - - - - - - - - 
Data Check & Correction Functions
- - - - - - - - - - - - - - - - - - - - */
	/* This function checks the values in $this Attributes and returns errors, if there are any
	Returns:
	$ary_data_errors - Array of errors, one error per element
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function check_group_data( $form_type = 'add' ) {
		$ary_data_errors = array();

		// Ensure this Group has a name
		if ( trim( $this->group_name ) == "" )
			array_push( $ary_data_errors , "Please give this Group a name." );

		// Ensure Group Display Width and Height are positive integers
		if ( !is_numeric( $this->group_display_width ) || ( $this->group_display_width < 1 ) || ( $this->group_display_width != round( $this->group_display_width ) ) )
			array_push( $ary_data_errors , "Display Width must be a positive integer." );
		if ( !is_numeric( $this->group_display_height ) || ( $this->group_display_height < 1 ) || ( $this->group_display_height != round( $this->group_display_height ) ) )
			array_push( $ary_data_errors , "Display Height must be a positive integer." );

		// Check that Group Name is unique
		global $wpdb;
		$qry_check_group_name = $wpdb->get_results( $wpdb->prepare(
			"SELECT *
			FROM " . MVOB_GRPS_TABLE_NAME . "
			WHERE group_name = %s" . ( $form_type == 'edit' ? ' AND group_id != ' . $this->group_id : '' ) , $this->group_name ) );

		if ( $wpdb->num_rows > 0 )
			array_push( $ary_data_errors , "That Group Name is already used." );

		if ( count( $ary_data_errors ) > 0 )
			$this->group_checked = 0;
		else
			$this->group_checked = 1;

		return $ary_data_errors;
	}

	/* This function reorders the Videos for $this Group
	Parameters:
	$video_ids - A list of the Videos in order

	Returns:
	True
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function reorder_videos( $video_ids ) {
		// Ensure a list was passed
		if( trim( $video_ids ) == "" ) {
			return "A list of Video IDs is required.";
		}

		$ary_video_ids = explode( "," , $video_ids );

		// Reorder the Videos in the database
		global $wpdb;

		foreach( $ary_video_ids AS $key => $video_id ) {
			// Set the order of the Video
			$qry_update_course = $wpdb->update(
							MVOB_VTOG_TABLE_NAME,
							array(
								'video_order' => $key + 1
							),
							array( 
								'video_id' => $video_id,
								'group_id' => $this->group_id ),
							array(
								'%d'
							),
							array( '%d' , '%d' )
						);
		}

		return true;
	}

/* - - - - - - - - - - - - - - - - - - - 
Attribute Access Functions
- - - - - - - - - - - - - - - - - - - - */
	/* These function return a single Attribute for $this Group
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function get_group_id() {
		return $this->group_id;
	}
	public function get_group_name() {
		return $this->group_name;
	}
	public static function get_group_name_by_id( $group_id ) {
		global $wpdb;

		if ( is_numeric( $group_id ) ) {
			$qry_get_group_name = $wpdb->get_row( $wpdb->prepare(
				"SELECT group_name
				FROM " . MVOB_GRPS_TABLE_NAME . " 
				WHERE group_id = %d" , $group_id ) );

			return $qry_get_group_name->group_name;
		}
		else {
			return "Group ID is required.";
		}
	}
	public function get_group_display_width() {
		return $this->group_display_width;
	}
	public function get_group_display_height() {
		return $this->group_display_height;
	}

	/* This function returns the number of Videos in this Group
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function count_group_videos() {
		global $wpdb;

		// Get a count of Videos
		$qry_count_group_videos = $wpdb->get_row( $wpdb->prepare( 
			"SELECT COUNT(video_id) AS video_count
			FROM " . MVOB_VTOG_TABLE_NAME . "
			WHERE group_id = %d" , $this->group_id ) );

		return $qry_count_group_videos->video_count;
	}

	/* This function returns the number of Videos in this Group
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public static function count_group_videos_by_id( $group_id ) {
		global $wpdb;

		if ( is_numeric( $group_id ) ) {
			// Get a count of Videos
			$qry_count_group_videos = $wpdb->get_row( $wpdb->prepare( 
				"SELECT COUNT(video_id) AS video_count
				FROM " . MVOB_VTOG_TABLE_NAME . "
				WHERE group_id = %d" , $group_id ) );

			return $qry_count_group_videos->video_count;
		}
		else {
			return "Group ID is required.";
		}
	}
}
?>