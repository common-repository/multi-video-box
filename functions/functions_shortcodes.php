<?php
/* Callback function for shortcode [mvob]
Parameters:
$atts - An array of attributes, which can contain: 
	group - The ID of the Group to display
	video - The ID of the single Video to display
	tab - Where should the tabs be displayed?  Accepts: top (default), left, bottom, right
	tab_side - Deprecated.  Same as tab
	title - Where should the title be displayed? Accepts: top (default), bottom, and none
	title_output - Deprecated. Same as title
	description - Where should the video description be displayed? Accepts: top (default), bottom, and none
	description_output - Deprecated. Same as description

Either group or video is required.  Group will be used if both are passed.

Return:
This code returns the output HTML for the display options
------------------------------ */
function mvob_output ( $atts ) {
	// Array of allowed values for text parameters
	$ary_allowed_values = array(
		'tab' => array( 'left' , 'right' , 'top' , 'bottom' ),
		'title' => array( 'top' , 'bottom' , 'none' ),
		'description' => array( 'top' , 'bottom' , 'none' )
		);
	$ary_num_tabs = array(
		'left' => 8,
		'right' => 8,
		'top' => 5,
		'bottom' => 5
		);

	// Get the passed parameters
	extract( shortcode_atts( array(
		'group' => 0,
		'video' => 0,
		'tab' => 'top',
		'title' => 'top',
		'description' => 'top',
		'tab_side' => '',
		'title_output' => '',
		'description_output' => ''
	), $atts ) );

	// If deprecated parameters were used, set them into the new values
	if ( $tab_side != '' )
		$tab = $tab_side;
	if ( $title_output != '' )
		$title = $title_output;
	if ( $description_output != '' )
		$description = $description_output;

	// Set defaults if proper format wasn't passed
	if ( array_search( $tab , $ary_allowed_values['tab'] ) === false )
		$tab = 'top';
	if ( array_search( $title , $ary_allowed_values['title'] ) === false )
		$title = 'top';
	if ( array_search( $description , $ary_allowed_values['description'] ) === false )
		$description = 'top';
	if ( !is_numeric( $group ) )
		$group = 0;
	if ( !is_numeric( $video ) )
		$video = 0;

	// If Group ID was passed, create the output for the Group ID
	if ( $group != 0 ) {
		// Create an object for the Group
		$the_group = new mvob_group( array( 'group_id' => $group ) );
		// Get the Videos in this Group
		$group_videos = $the_group->get_group_videos();

		// Set the max_tabs based on where the tabs are
		$num_tabs = $ary_num_tabs[$tab];

		/* - - - - - - - - - - - - - - - - - 
		Create the tab output
		- - - - - - - - - - - - - - - - - */
		$mvob_tabs = '<div id="mvob-tabs-' . $tab . '" class="mvob-tabs-' . $tab . '">';

		// Output the Previous tab
		$mvob_tabs .= '<div id="mvob-prev" class="mvob-' . $tab . '-tab mvob-prev-tab mvob-prev-tab-' .  $tab . ' mvob-tab-inactive">';

		// Include the previous arrow (always starts deactivated)
		$mvob_tabs .= '<img id="mvob-prev-arrow" src="' . MVOB_IMAGES_URL . 'prev-next-arrow.png" class="mvob-arrow mvob-prev-arrow-' .  $tab . ' mvob-inactive" />';

		$mvob_tabs .= '</div>';

		// Output the Video tabs
		for( $i = 0; $i < $num_tabs; $i++ ) {
			// Create the Tab div
			$mvob_tabs .= '<div id="mvob-tab-' . $i . '" class="mvob-' . $tab . '-tab';

			// Set whether this is a left/right tab or a top/bottom tab
			if ( ( $tab == "left" ) || ( $tab == "right" ) )
				$mvob_tabs .= ' mvob-tab-lr';
			else
				$mvob_tabs .= ' mvob-tab-tb';

			// Output the Video Name, if there are at least $i videos, and close the tab
			$mvob_tabs .= ( $i == 0 ? ' mvob-selected' : '' ) . ( $i >= count( $group_videos ) ? ' mvob-empty' : '' ) . '">' . ( $i < count( $group_videos ) ? substr( $group_videos[$i]->video_name , 0 , 15 ) : '' ) . '</div>';
		}

		// Output the Next tab
		$mvob_tabs .= '<div id="mvob-next" class="mvob-' . $tab . '-tab mvob-next-tab mvob-next-tab-' .  $tab . ( count( $group_videos ) > $num_tabs ? '' : ' mvob-tab-inactive' ) . '">';

		// Include the next arrow
		$mvob_tabs .= '<img id="mvob-next-arrow" src="' . MVOB_IMAGES_URL . 'prev-next-arrow.png" class="mvob-arrow mvob-next-arrow-' .  $tab . ( count( $group_videos ) > $num_tabs ? '' : ' mvob-inactive' ) . '" />';

		$mvob_tabs .= '</div>';

		$mvob_tabs .= '</div>';

		/* - - - - - - - - - - - - - - - - - 
		End of tab output
		Build the AJAX Controls output
		- - - - - - - - - - - - - - - - - */
		$ary_video_ids = array();
		$ary_video_names = array();
		foreach ( $group_videos AS $video ) {
			array_push( $ary_video_ids , $video->video_id );
			array_push( $ary_video_names , $video->video_name );
		}

		$mvob_ajax = '<input type="hidden" id="total_videos" value="' . count( $group_videos ) . '" />';
		$mvob_ajax .= '<input type="hidden" id="video_ids" value="' . implode( "_|_" , $ary_video_ids ) . '" />';
		$mvob_ajax .= '<input type="hidden" id="video_names" value="' . htmlspecialchars( implode( "_|_" , $ary_video_names ) ) . '" />';
		$mvob_ajax .= '<input type="hidden" id="first_video" value="1" />';
		$mvob_ajax .= '<input type="hidden" id="last_video" value="' . $num_tabs . '" />';
		$mvob_ajax .= '<input type="hidden" id="tab" value="' . $tab . '" />';
		$mvob_ajax .= '<input type="hidden" id="selected_video_id" value="' . $group_videos[0]->video_id . '" />';
		$mvob_ajax .= '<input type="hidden" id="group_id" value="' . $the_group->get_group_id() . '" />';

		/* - - - - - - - - - - - - - - - - - 
		End of AJAX Controls output
		Build the Video output
		- - - - - - - - - - - - - - - - - */
		$mvob_output = $mvob_ajax . '<div id="mvob" class="mvob-wrapper">';

		// If tabs are on top or left, add them to the output
		if ( ( $tab == "top" ) || ( $tab == "left" ) )
			$mvob_output .= $mvob_tabs;

		$mvob_output .= '<div id="mvob-video-inner" class="mvob-video-inner mvob-video-inner-' . $tab . '">';

		// Output the Title
		if ( $title == "top" )
			$mvob_output .= '<div id="mvob-video-title" class="mvob-video-title-top">' . $group_videos[0]->video_name . '</div>';

		// Output the loading image
		$mvob_output .= '<div class="mvob-loading-div"><img src="' . MVOB_IMAGES_URL . 'ajax-loading-bar.gif" id="mvob-processing" /></div>';

		// Output the Description
		if ( $description == "top" )
			$mvob_output .= '<div class="mvob-video-description-top"><p id="mvob-video-description">' . $group_videos[0]->video_description . '</p></div>';

		// Output the Video
		$first_video = new mvob_video( array( 'video_id' => $group_videos[0]->video_id ) );
		$mvob_output .= '<div id="mvob-video-embed">' . $first_video->get_video_file_embed( $group ) . '</div>';

		// Output the Title
		if ( $title == "bottom" )
			$mvob_output .= '<div id="mvob-video-title" class="mvob-video-title-bottom">' . $group_videos[0]->video_name . '</div>';

		// Output the loading image
		$mvob_output .= '<div class="mvob-loading-div"><img src="' . MVOB_IMAGES_URL . 'ajax-loading-bar.gif" id="mvob-processing" /></div>';

		// Output the Description
		if ( $description == "bottom" )
			$mvob_output .= '<div class="mvob-video-description-bottom"><p id="mvob-video-description">' . $group_videos[0]->video_description . '</p></div>';

		$mvob_output .= '</div>';

		// If tabs are on right or bottom, add them to the output
		if ( ( $tab == "right" ) || ( $tab == "bottom" ) )
			$mvob_output .= $mvob_tabs;

		$mvob_output .= '</div>';

		// Determine if the "Powered By..." link should be displayed
		$mvob_options = get_option( 'mvob_options' );
		if ( $mvob_options['mvob_give_props'] == "y" )
			$mvob_output .= '<div id="mvob-link" class="mvob-powered-link"><a href="http://www.nuttymango.com/plugins/multi-video-box/" target="_blank">Powered By The Multi Video Box Plug-In</a></div>';

		return $mvob_output;
	}
	// If Video ID was passed, create the output for the Video ID
	else if ( $video != 0  ) {
		// Create an object for the Video
		$the_video = new mvob_video( array( 'video_id' => $video ) );
		
		/* - - - - - - - - - - - - - - - - - 
		Build the Video output
		- - - - - - - - - - - - - - - - - */
		$mvob_output = '<div id="mvob" class="mvob-wrapper-single">';

		$mvob_output .= '<div id="mvob-video-inner" class="mvob-video-inner mvob-video-inner-single">';

		// Output the Title, Description, and Video for the first Video
		if ( $title == "top" )
			$mvob_output .= '<div id="mvob-video-title" class="mvob-video-title-top">' . $the_video->get_video_name() . '</div>';
		if ( $description == "top" )
			$mvob_output .= '<div class="mvob-video-description-top"><p id="mvob-video-description">' . $the_video->get_video_description() . '</p></div>';

		// Output the Video
		$mvob_output .= '<div id="mvob-video-embed">' . $the_video->get_video_file_embed() . '</div>';

		if ( $title == "bottom" )
			$mvob_output .= '<div id="mvob-video-title" class="mvob-video-title-bottom">' . $the_video->get_video_name() . '</div>';
		if ( $description == "bottom" )
			$mvob_output .= '<div class="mvob-video-description-bottom"><p id="mvob-video-description">' . $the_video->get_video_description() . '</p></div>';

		$mvob_output .= '</div>';

		$mvob_output .= '</div>';

		// Determine if the "Powered By..." link should be displayed
		$mvob_options = get_option( 'mvob_options' );
		if ( $mvob_options['mvob_give_props'] == "y" )
			$mvob_output .= '<div id="mvob-link" class="mvob-powered-link"><a href="http://www.nuttymango.com/plugins/multi-video-box/" target="_blank">Powered By The Multi Video Box Plug-In</a></div>';

		return $mvob_output;
	}
	else {
		return false;
	}
}
?>