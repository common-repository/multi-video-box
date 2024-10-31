<?php
// ------------------------------------------------------------------------
// This file declares the mvob_video class
// ------------------------------------------------------------------------
class mvob_video {
/* - - - - - - - - - - - - - - - - - - - 
Attributes
- - - - - - - - - - - - - - - - - - - - */
	protected $video_id;		// The database ID of the Video
	protected $video_name;		// The name of the Video
	protected $video_description;	// A description of the Video
	protected $video_url;		// The URL the user goes to when the Video is clicked
	protected $video_checked;	// 0 - Video hasn't had video data checked, 1 - Video has had video data checked

/* - - - - - - - - - - - - - - - - - - - 
Constructor & Retrieval Functions
- - - - - - - - - - - - - - - - - - - - */
	/* Constructor function to create a new Video instance
	Parameters:
	$atts - Optional.  Array holding Video Attributes
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function __construct( $atts = '' ) {
		// If there is a Video ID, get the Video information from the database
		if ( ( trim( $atts['video_id'] ) != "" ) && is_numeric( $atts['video_id'] ) && ( trim( $atts['video_name'] == "" ) ) ) {
			global $wpdb;

			// Get the record matching $atts['video_id']
			$qry_get_video = $wpdb->get_row( $wpdb->prepare( 
				"SELECT *
				FROM " . MVOB_VIDS_TABLE_NAME . "
				WHERE video_id = %d" , $atts['video_id'] ) );

			// If a Video was found, set it into $this
			if ( $qry_get_video != NULL ) {
				$this->video_id = $qry_get_video->video_id;
				$this->video_name = $qry_get_video->video_name;
				$this->video_description = $qry_get_video->video_description;
				$this->video_url = $qry_get_video->video_url;
				$this->video_checked = 1;
			}
			else {
				echo "The Video ID is invalid";
			}
		}
		// If $atts were passed, but Video ID wasn't passed, create a Video with the passed values
		else if ( is_array( $atts ) ) {
			$this->video_id = $atts['video_id'];
			$this->video_name = stripslashes_deep( $atts['video_name'] );
			$this->video_description = stripslashes_deep( $atts['video_description'] );
			$this->video_url = stripslashes_deep( $atts['video_url'] );
			$this->video_checked = 0;
		}
		// Otherwise, create a Video with defaults
		else {
			$this->video_id = "";
			$this->video_name = "";
			$this->video_description = "";
			$this->video_url = "";
			$this->video_checked = 0;
		}
	}

	/* This function returns all Video IDs based on passed parameters
	Parameters:
	$atts - Array of parameters
		$video_external - OPTIONAL. Show only externally linked videos.  Accepts 1 = external, 0 = internal, -1 = all (default)

	Returns:
	An array of Video IDs
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public static function get_video_ids( $atts ) {
		global $wpdb;
		// Get all Video IDs matching WHERE clause
		$qry_get_videos = $wpdb->get_results( $wpdb->prepare( 
			"SELECT video_id
			FROM " . MVOB_VIDS_TABLE_NAME . " 
			ORDER BY video_name" , "" ) );

		// If Video IDs were found, push them into an array and return the array
		if ( count( $qry_get_videos ) > 0 ) {
			$ary_video_ids = array();
			foreach( $qry_get_videos AS $video ) {
				array_push( $ary_video_ids , $video->video_id );
			}

			// Return the array
			return $ary_video_ids;
		}
		// If no Video IDs were found, return false
		else {
			return false;
		}
	}

/* - - - - - - - - - - - - - - - - - - - 
Add, Update, & Delete Video Functions
- - - - - - - - - - - - - - - - - - - - */
	/* This function inserts $this Video into the database after running check_Video_data to ensure data is valid
	Parameters:
	No parameters

	Returns:
	$video_id - The Video ID from the database insert
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function add_video_to_database() {
		// If the data in $this hasn't been checked, return an error
		if ( $this->video_checked == 0 ) {
			return "Please run the check_video_data function first.";
		}
		// Otherwise, there are no errors
		else {
			// Add $this to the database
			global $wpdb;

			// Insert the Video
			$qry_insert_video = $wpdb->insert(
						MVOB_VIDS_TABLE_NAME,
						array(
							'video_name' => $this->video_name,
							'video_description' => $this->video_description,
							'video_url' => $this->video_url
						),
						array(
							'%s',
							'%s',
							'%s'
						)
					);
			$this->video_id = $wpdb->insert_id;

			// Return Video ID
			return $this->video_id;
		}
	}

	/* This function updates $this Video
	Returns:
	True if update was successful, false if not.
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function update_video() {
		// Make sure check_video_data has been run successfully
		if ( $this->video_checked != 1 ) {
			return "Please run the check_video_data function before attemping to update this Video.";
		}
		// Otherwise, there are no errors
		else {
			global $wpdb;

			// Update the Video
			$qry_update_video = $wpdb->update(
							MVOB_VIDS_TABLE_NAME,
							array(
								'video_name' => $this->video_name,
								'video_description' => $this->video_description,
								'video_url' => $this->video_url
							),
							array( 'video_id' => $this->video_id ),
							array(
								'%s',
								'%s',
								'%s'
							),
							array( '%d' )
						);

			// If the Video was successfully updated, return true
			if( $qry_update_video > 0 ) {
				return true;
			}
			else {
				return false;
			}
		}
	}

	/* This function deletes a Video based on a Video ID
	Parameters:
	$video_id - Numeric video ID
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public static function delete_video( $video_id ) {
		// Ensure VideoID is numeric
		if( !is_numeric( $video_id ) ) {
			return "Video ID must be numeric.";
		}

		// Reorder all Videos after this one
		mvob_video::reorder_videos( $video_id );

		global $wpdb;

		// Delete the ties to Groups
		$qry_delete_video = $wpdb->query( $wpdb->prepare( 
			"DELETE FROM " . MVOB_VTOG_TABLE_NAME . "
			WHERE video_id = %d" , $video_id ) );

		// Delete the Video
		$qry_delete_video = $wpdb->query( $wpdb->prepare( 
			"DELETE FROM " . MVOB_VIDS_TABLE_NAME . "
			WHERE video_id = %d" , $video_id ) );

		return true;
	}

	/* This function reorders all Videos that come after $video_id in Groups
	Parameters:
	$video_id - Numeric video ID
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public static function reorder_videos( $video_id ) {
		// Ensure VideoID is numeric
		if( !is_numeric( $video_id ) ) {
			return "Video ID must be numeric.";
		}

		global $wpdb;

		// Get all Groups and Video Order corresponding to the Video ID
		$qry_video_order = $wpdb->get_results( $wpdb->prepare(
			"SELECT group_id, video_order
			FROM " . MVOB_VTOG_TABLE_NAME . " 
			WHERE video_id = %d" , $video_id ) );

		if ( count( $qry_video_order ) > 0 ) {
			foreach( $qry_video_order AS $video ) {
				$qry_update_video_order = $wpdb->query( $wpdb->prepare(
					"UPDATE " . MVOB_VTOG_TABLE_NAME . " 
					SET video_order = video_order - 1
					WHERE group_id = %d
					AND video_order > %d" , $video->group_id , $video->video_order ) );
			}
		}

		return true;
	}

/* - - - - - - - - - - - - - - - - - - - 
Data Check & Correction Functions
- - - - - - - - - - - - - - - - - - - - */
	/* This function checks the values in $this Attributes and returns errors, if there are any
	Returns:
	$ary_data_errors - Array of errors, one error per element
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function check_video_data( $form_type = 'add' ) {
		$ary_data_errors = array();

		// Check to ensure all required fields are input
		if ( trim ( $this->video_name ) == "" )
			array_push( $ary_data_errors , "Please enter the Video Name." );

		// Ensure video URL is valid
		if ( ( trim ( $this->video_url ) == "" ) || ( !filter_var( $this->video_url , FILTER_VALIDATE_URL ) ) )
			array_push( $ary_data_errors , "Please enter the URL for this Video." );

		// Ensure the video is from a supported domain
		$video_embed = new video_embed( $this->video_url );
		if ( !$video_embed->is_domain_supported_public() )
			array_push( $ary_data_errors , "Sorry, that video hosting site is not supported. Please use a video from YouTube, Vimeo, Dailymotion, Vevo, or Metacafe." );

		// Check that Video Name is unique
		global $wpdb;
		$qry_check_video_name = $wpdb->get_results( $wpdb->prepare(
			"SELECT *
			FROM " . MVOB_VIDS_TABLE_NAME . "
			WHERE video_name = %s" . ( $form_type == 'edit' ? ' AND video_id != ' . $this->video_id : '' ) , $this->video_name ) );

		if ( $wpdb->num_rows > 0 )
			array_push( $ary_data_errors , "That Video Name is already used." );

		if ( count( $ary_data_errors ) > 0 )
			$this->video_checked = 0;
		else
			$this->video_checked = 1;

		return $ary_data_errors;
	}

/* - - - - - - - - - - - - - - - - - - - 
Group Interaction Functions
- - - - - - - - - - - - - - - - - - - - */
	/* This function return the count of Groups this Video is assigned to
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function count_assigned_groups() {
		global $wpdb;
		$qry_count_groups = $wpdb->get_row( $wpdb->prepare( 
			"SELECT COUNT(group_id) AS groups_count
			FROM " . MVOB_VTOG_TABLE_NAME . " 
			WHERE video_id = %d" , $this->video_id ) );

		return $qry_count_groups->groups_count;
	}

	/* This function returns a query of Groups corresponding to the input parameters
	Parameters:
	$assigned - Return Groups that are assigned/unassigned to this Video.  Accepts "assigned" (default), "unassigned"
	$return_type - What type of data should be returned? 'query' returns the entire query, 'list' returns a list of the Group IDs

	Returns: 1 of the following, based on $return_type
	A query of Groups - Includes Group ID, Group Name, Group Display Width/Height
	A list of Group IDs
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function get_video_groups( $assigned = 'assigned' , $return_type = 'query' ) {
		global $wpdb;

		// Run the query based on $assigned
		switch ( $assigned ) {
			case "unassigned":
				$qry_video_groups = $wpdb->get_results( $wpdb->prepare( 
					"SELECT grps.group_id, group_name, group_display_width, group_display_height
					FROM " . MVOB_GRPS_TABLE_NAME . " grps
					WHERE group_id NOT IN (SELECT group_id FROM " . MVOB_VTOG_TABLE_NAME . "
								WHERE video_id = %d)
					ORDER BY group_id" , $this->video_id ) );
				break;

			default: // If $assigned == "assigned" or any other value
				$qry_video_groups = $wpdb->get_results( $wpdb->prepare( 
					"SELECT grps.group_id, group_name, group_display_width, group_display_height
					FROM " . MVOB_GRPS_TABLE_NAME . " grps
						JOIN " . MVOB_VTOG_TABLE_NAME . " vtog
							ON grps.group_id = vtog.group_id
					WHERE vtog.video_id = %d
					ORDER BY group_id" , $this->video_id ) );
		}

		if ( $return_type == "query" ) {
			return $qry_video_groups;
		}
		else {
			// Create an array of the Groups
			$ary_assigned_groups = array();
			foreach ( $qry_video_groups AS $group ) {
				array_push( $ary_assigned_groups , $group->group_id );
			}

			// Implode into a list and return
			return implode( "," , $ary_assigned_groups );
		}
	}

	/* This function assigns $this Video to Groups
	Parameters:
	$groups - Form field input of Group IDs

	Returns: 
	Text string
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function add_video_to_groups( $groups ) {
		global $wpdb;

		// Loop the array of Groups to add
		if ( count( $groups ) > 0 ) {
			foreach ( $groups AS $group ) {	
				// If the Video can be added to this Group, add it
				if ( $this->is_video_in_group( $group ) === false ) {
					// Keep track of how many Groups were added
					$num_total_groups_added += 1;

					// Set the Video Order for this Video
					$video_order = mvob_group::count_group_videos_by_id( $group ) + 1;

					$wpdb->insert( MVOB_VTOG_TABLE_NAME ,
						array(
							'video_id' => $this->video_id,
							'group_id' => $group,
							'video_order' => $video_order
						)
					);
				}
			}
		}

		return '"' . $this->video_name . '" added to ' . $num_total_groups_added . ( $num_total_groups_added != 1 ? ' Groups' : ' Group' );
	}

	/* This function checks to see if a Video is already in a Group
	Parameters:
	$group_id - The Group ID for the Group to check $this Video against
	
	Returns:
	True if there are no errors
	String output of error if there are errors
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function is_video_in_group( $group_id ) {
		// Make sure Group ID is numeric
		if ( !is_numeric ( $group_id ) ) {
			return "Group ID must be numeric.";
		}
		else {
			global $wpdb;
			$qry_is_video_in_group = $wpdb->get_results( $wpdb->prepare( 
				"SELECT COUNT(*) AS group_count
				FROM " . MVOB_VTOG_TABLE_NAME . " vtog
				WHERE vtog.video_id = %d
				AND vtog.group_id = %d" , $this->video_id , $group_id ) );

			// If COUNT returned 0, this video is not in the group
			if ( $qry_is_video_in_group->group_count == 0 )
				return false;
			else
				return true;
		}
	}

	/* This function removes $this Video from Groups
	Parameters:
	$groups - Form field input of Group IDs

	Returns: 
	Text string
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function remove_video_from_groups( $groups ) {
		global $wpdb;

		// Loop the array of Groups to add
		if ( count( $groups ) > 0 ) {
			foreach ( $groups AS $group ) {
				// Keep track of how many Groups were added
				$num_total_groups_removed += 1;

				$qry_remove_group = $wpdb->query( $wpdb->prepare( 
					"DELETE FROM " . MVOB_VTOG_TABLE_NAME . " 
					WHERE video_id = %d
					AND group_id = %d" , $this->video_id , $group ) );
			}
		}

		return '"' . $this->video_name . '" removed from ' . $num_total_groups_removed . ( $num_total_groups_removed != 1 ? ' Groups' : ' Group' );
	}

/* - - - - - - - - - - - - - - - - - - - 
Attribute Access Functions
- - - - - - - - - - - - - - - - - - - - */
	/* These function return a single Attribute for $this Video
	Parameters:
	No parameters
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function get_video_id() {
		return $this->video_id;
	}
	public function get_video_name() {
		return $this->video_name;
	}
	public static function get_video_name_by_id( $video_id ) {
		// Ensure $video_id is numeric
		if ( !is_numeric( $video_id ) )
			return "Video ID must be numeric.";
		else {
			global $wpdb;
			$qry_video_name = $wpdb->get_row( $wpdb->prepare(
				"SELECT video_name
				FROM " . MVOB_VIDS_TABLE_NAME . "
				WHERE video_id = %d" , $video_id ) );

			// If a Video was found, return the Video Name
			if ( $qry_video_name != NULL )
				return $qry_video_name->video_name;
			else
				echo "The Video ID is invalid";
		}
	}
	public function get_video_description() {
		return $this->video_description;
	}
	public function get_video_file_embed( $group_id = 0 ) {
		// Determine the $width and $height
		if ( $group_id != 0 ) {
			$group = new mvob_group( array( 'group_id' => $group_id ) );
			$width = $group->get_group_display_width();
			$height = $group->get_group_display_height();
		}
		else {
			$mvob_options = get_option( 'mvob_options' );

			$width = $mvob_options['mvob_default_width'];
			$height = $mvob_options['mvob_default_height'];
		}

		$video_embed = new video_embed;
		return $video_embed->get_embed_code( $this->video_url , $width , $height );
	}
	public function get_video_url() {
		return $this->video_url;
	}
	public function get_video_domain() {
		return str_replace( "www." , "" , parse_url( $this->video_url , PHP_URL_HOST ) );
	}
}
?>