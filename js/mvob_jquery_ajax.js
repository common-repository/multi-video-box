jQuery(document).ready(function () {
	// If the Add Video To Groups button exists, listen for it
	if ( $('#group_add_button').length ) {
		// If the button was clicked...
		$('#group_add_button').click( function() {
			// Ensure that Groups have been selected before proceeding
			if ( $('#group_id_add').val() && ( $('#group_id_add').val() != 0 ) ) {
				var $groups_to_add = $('#group_id_add').val();
				var $video_id = $('#video_id').val();

				add_data = {
					action: 'mvob_add_videos_to_groups',
					add_groups: $groups_to_add,
					video_id: $video_id
				};

				$.post( ajaxurl , add_data , function( data ) {
					// Output a message and show the message
					$('#assign-message').html( '<p><strong>' + data + '</strong></p>' );
					$('#assign-message').show();
				} );

				// Loop $groups_to_add to move 
				$.each( $groups_to_add , function( index , value ) {
					// If the zero option exists in the Remove list, remove it
					if ( $('#group_id_remove option[value="0"]').text() ) {
						remove_zero_option( 'group_id_remove' );
					}

					// Remove the option from the Add list and add it to the Remove list
					option_swap_list( 'group_id_add' , 'group_id_remove' , value );

					// If there are no options left in the Add list, add the zero option
					if ( $('#group_id_add').has('option').length == 0 ) {
						add_zero_option( 'group_id_add' , 'This Video is in all Groups' );
					}
				});

				return false;
			}
		} );
	}

	// If the Remove Video From Groups button exists, listen for it
	if ( $('#group_remove_button').length ) {
		// If the button was clicked...
		$('#group_remove_button').click( function() {
			// Ensure that Groups have been selected before proceeding
			if ( $('#group_id_remove').val() && ( $('#group_id_remove').val() != 0 ) ) {
				var $groups_to_remove = $('#group_id_remove').val();
				var $video_id = $('#video_id').val();

				remove_data = {
					action: 'mvob_remove_videos_from_groups',
					remove_groups: $groups_to_remove,
					video_id: $video_id
				};

				$.post( ajaxurl , remove_data , function( data ) {
					// Output a message and show the message
					$('#assign-message').html( '<p><strong>' + data + '</strong></p>' );
					$('#assign-message').show();
				} );

				// Loop $groups_to_remove to move 
				$.each( $groups_to_remove , function( index , value ) {
					// If the zero option exists in the Add list, remove it
					if ( $('#group_id_add option[value="0"]').text() ) {
						remove_zero_option( 'group_id_add' );
					}

					// Remove the option from the Remove list and add it to the Add list
					option_swap_list( 'group_id_remove' , 'group_id_add' , value );

					// If there are no options left in the Remove list, add the zero option
					if ( $('#group_id_remove').has('option').length == 0 ) {
						add_zero_option( 'group_id_remove' , 'No Groups Assigned' );
					}
				});

				return false;
			}
		} );
	}

	// If the Add Videos To Group button exists, listen for it
	if ( $('#video_add_button').length ) {
		// If the button was clicked...
		$('#video_add_button').click( function() {
			// Ensure that Videos have been selected before proceeding
			if ( $('#video_id_add').val() && ( $('#video_id_add').val() != 0 ) ) {
				var $videos_to_add = $('#video_id_add').val();
				var $group_id = $('#group_id').val();

				add_data = {
					action: 'mvob_add_videos_to_groups',
					add_videos: $videos_to_add,
					group_id: $group_id
				};

				$.post( ajaxurl , add_data , function( data ) {
					// Output a message and show the message
					$('#assign-message').html( '<p><strong>' + data + '</strong></p>' );
					$('#assign-message').show();
				} );

				// Loop $videos_to_add to move 
				$.each( $videos_to_add , function( index , value ) {
					// If the zero option exists in the Remove list, remove it
					if ( $('#video_id_remove option[value="0"]').text() ) {
						remove_zero_option( 'video_id_remove' );
					}

					// Remove the option from the Add list and add it to the Remove list
					option_swap_list( 'video_id_add' , 'video_id_remove' , value );

					// If there are no options left in the Add list, add the zero option
					if ( $('#video_id_add').has('option').length == 0 ) {
						add_zero_option( 'video_id_add' , 'All Videos Are In This Group' );
					}
				});

				return false;
			}
		} );
	}

	// If the Remove Videos From Group button exists, listen for it
	if ( $('#video_remove_button').length ) {
		// If the button was clicked...
		$('#video_remove_button').click( function() {
			// Ensure that Videos have been selected before proceeding
			if ( $('#video_id_remove').val() && ( $('#video_id_remove').val() != 0 ) ) {
				var $videos_to_remove = $('#video_id_remove').val();
				var $group_id = $('#group_id').val();

				remove_data = {
					action: 'mvob_remove_videos_from_groups',
					remove_videos: $videos_to_remove,
					group_id: $group_id
				};

				$.post( ajaxurl , remove_data , function( data ) {
					// Output a message and show the message
					$('#assign-message').html( '<p><strong>' + data + '</strong></p>' );
					$('#assign-message').show();
				} );

				// Loop $videos_to_remove to move 
				$.each( $videos_to_remove , function( index , value ) {
					// If the zero option exists in the Add list, remove it
					if ( $('#video_id_add option[value="0"]').text() ) {
						remove_zero_option( 'video_id_add' );
					}

					// Remove the option from the Remove list and add it to the Add list
					option_swap_list( 'video_id_remove' , 'video_id_add' , value );

					// If there are no options left in the Remove list, add the zero option
					if ( $('#video_id_remove').has('option').length == 0 ) {
						add_zero_option( 'video_id_remove' , 'No Videos In This Group' );
					}
				});

				return false;
			}
		} );
	}
});

function option_swap_list( start_list , end_list , option_value ) {
	// Get the Name from the selected option in the starting list
	var $option_name = $('#' + start_list + ' option[value="' + option_value + '"]').text();

	// Add the option to the ending list
	$('#' + end_list)
		.append( $("<option></option>" )
		.attr( "value" , option_value )
		.text( $option_name ) ); 

	// Remove the option from this list
	$('#' + start_list + ' option[value="' + option_value + '"]').remove();
}

function add_zero_option( list_name , option_name ) {
	// Add the zero option to the list
	$('#' + list_name)
		.append( $("<option></option>" )
		.attr( "value" , 0 )
		.text( option_name ) ); 
}

function remove_zero_option( list_name ) {
	// Remove the option from this list
	$('#' + list_name + ' option[value="0"]').remove();
}