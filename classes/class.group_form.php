<?php
// ------------------------------------------------------------------------
// This file declares the mvob_group_form class.
// This class creates input forms for the mvob_group class              
// ------------------------------------------------------------------------
class mvob_group_form extends mvob_group {
/* - - - - - - - - - - - - - - - - - - - 
Attributes
- - - - - - - - - - - - - - - - - - - - */

/* - - - - - - - - - - - - - - - - - - - 
Constructor & Group Retrieval Functions
- - - - - - - - - - - - - - - - - - - - */
	/* Constructor function to create a new Group instance
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function __construct( $atts ) {
		parent::__construct( $atts );
	}


/* - - - - - - - - - - - - - - - - - - - 
Form Field Output Functions
- - - - - - - - - - - - - - - - - - - - */
	/* This function creates the HTML for the form fields to Add/Edit a Group
	Returns:
	$str_form_html - HTML for Group form
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function output_form_fields( $form_type = 'add' ) {
		if ( $form_type == "edit" ) {
			$str_form_html .= $this->form_field_group_id();
		}
		$str_form_html .= $this->form_field_group_name();
		$str_form_html .= $this->form_field_group_display_width();
		$str_form_html .= $this->form_field_group_display_height();

		return $str_form_html;
	}

	/* This function creates the hidden field for Group ID
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	private function form_field_group_id() {
		$str_form_field_html = '<input type="hidden" name="group_id" value="' . $this->group_id . '" id="group_id" />';

		return $str_form_field_html;
	}

	/* This function creates the input box for entering Group Name
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	private function form_field_group_name() {
		$str_form_field_html = '<div class="form-fieldset"><div class="form-fieldset-title">What Do You Want To Call This Group?</div>';
		$str_form_field_html .= '<div class="form-fieldset-input">';
		$str_form_field_html .= '<input type="text" name="group_name" value="' . $this->group_name . '" size="50" maxlength="100" onfocus="show_hide_instruction_div(\'inst_group_name\',\'show\');" onblur="show_hide_instruction_div(\'inst_group_name\',\'hide\');" />';

		// Output a div with instructions for this field
		$str_form_field_html .=  '<div class="instruction-bubble" id="inst_group_name">This name is just for your own use to identify your Groups.  It is not displayed publicly.<div class="instruction-bubble-arrow-border"></div><div class="instruction-bubble-arrow"></div></div>';

		$str_form_field_html .= '</div></div>';
		return $str_form_field_html;
	}

	/* This function creates the input field for the Display Width
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	private function form_field_group_display_width() {
		$str_form_field_html = '<div class="form-fieldset"><div class="form-fieldset-title">How Wide Do You Want Videos In This Group To Be When Displayed?</div>';
		$str_form_field_html .= '<div class="form-fieldset-input">';
		$str_form_field_html .= '<input type="text" name="group_display_width" value="' . $this->group_display_width . '" size="10" onfocus="show_hide_instruction_div(\'inst_group_display_width\',\'show\');" onblur="show_hide_instruction_div(\'inst_group_display_width\',\'hide\');" />';

		// Output a div with instructions for this field
		$str_form_field_html .=  '<div class="instruction-bubble" id="inst_group_display_width">Just make sure this is a positive integer.<div class="instruction-bubble-arrow-border"></div><div class="instruction-bubble-arrow"></div></div>';

		$str_form_field_html .= '</div></div>';
		return $str_form_field_html;
	}

	/* This function creates the input field for the Display Height
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	private function form_field_group_display_height() {
		$str_form_field_html = '<div class="form-fieldset"><div class="form-fieldset-title">How Tall Do You Want Videos In This Group To Be When Displayed?</div>';
		$str_form_field_html .= '<div class="form-fieldset-input">';
		$str_form_field_html .= '<input type="text" name="group_display_height" value="' . $this->group_display_height . '" size="10" onfocus="show_hide_instruction_div(\'inst_group_display_height\',\'show\');" onblur="show_hide_instruction_div(\'inst_group_display_height\',\'hide\');" />';

		// Output a div with instructions for this field
		$str_form_field_html .=  '<div class="instruction-bubble" id="inst_group_display_height">Just make sure this is a positive integer.<div class="instruction-bubble-arrow-border"></div><div class="instruction-bubble-arrow"></div></div>';

		$str_form_field_html .= '</div></div>';
		return $str_form_field_html;
	}

/* - - - - - - - - - - - - - - - - - - - 
Add Videos To Group Form Functions
- - - - - - - - - - - - - - - - - - - - */
	/* These functions create the HTML for the form fields for adding Videos to $this Video
	Returns:
	$str_form_html - HTML for Ad form
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function output_assign_form_fields() {
		$str_form_html = '<div class="assign-div">';
		$str_form_html .= $this->form_field_message_box();

		$str_form_html .= '<div class="assign-left">';
		$str_form_html .= $this->form_field_group_id();
		$str_form_html .= $this->form_field_unassigned_videos();
		$str_form_html .= '</div>';

		$str_form_html .= '<div class="assign-arrows">';
		$str_form_html .= $this->form_field_arrows();
		$str_form_html .= '</div>';

		$str_form_html .= '<div class="assign-right">';
		$str_form_html .= $this->form_field_assigned_videos();
		$str_form_html .= '</div></div>';

		return $str_form_html;
	}

	private function form_field_message_box() {
		$str_form_field_html = '<div id="assign-message" class="updated"></div>';

		return $str_form_field_html;
	}

	private function form_field_unassigned_videos() {
		// Get the Video Groups
		$qry_unassigned_videos = $this->get_group_videos( 'unassigned' );

		$str_form_field_html = '<h3>Unassigned Videos</h3>';
		$str_form_field_html .= '<select name="video_id_add[]" id="video_id_add" multiple="multiple" size="8" class="assign-select">';

		// If there are unassigned Groups, output them
		if ( count( $qry_unassigned_videos ) > 0 ) {
			foreach ( $qry_unassigned_videos AS $video ) {
				$str_form_field_html .= '<option value="' . $video->video_id . '">' . $video->video_name . '</option>';
			}
		}
		else {
			$str_form_field_html .= '<option value="0">All Videos Are In This Group</option>';
		}

		$str_form_field_html .= '</select>';

		return $str_form_field_html;
	}

	private function form_field_assigned_videos() {
		// Get the Videos in this Group
		$qry_assigned_videos = $this->get_group_videos( 'assigned' );

		$str_form_field_html = '<h3>Assigned Videos</h3>';
		$str_form_field_html .= '<select name="video_id_remove[]" id="video_id_remove" multiple="multiple" size="8" class="assign-select">';

		// If there are assigned Groups, output them
		if ( count( $qry_assigned_videos ) > 0 ) {
			foreach ( $qry_assigned_videos AS $video ) {
				$str_form_field_html .= '<option value="' . $video->video_id . '">' . $video->video_name . '</option>';
			}
		}
		else {
			$str_form_field_html .= '<option value="0">No Videos In This Group</option>';
		}

		$str_form_field_html .= '</select>';

		return $str_form_field_html;
	}

	private function form_field_arrows() {
		$str_form_field_html = '<input type="submit" class="right_arrow" name="video_add_button" id="video_add_button" alt="Add To Group" value="" />';
		$str_form_field_html .= '<input type="submit" class="left_arrow" name="video_remove_button" id="video_remove_button" alt="Remove From Group" value="" />';

		return $str_form_field_html;
	}

	private function form_field_arrows_ajax() {
		$str_form_field_html = '<img src="' . MVOB_IMAGES_URL . 'right-arrow.png" class="arrow" id="video_add_button" /><br/>';
		$str_form_field_html .= '<img src="' . MVOB_IMAGES_URL . 'left-arrow.png" class="arrow" id="video_remove_button" /><br/>';

		return $str_form_field_html;
	}

	private function form_field_loader() {
		$str_form_field_html = '<img src="' . MVOB_IMAGES_URL . 'ajax-loader.gif" class="ajaxloader" id="mvob_loading" />';

		return $str_form_field_html;
	}

	/* This function creates the HTML for the reorder Videos form for $this
	Returns:
	$str_form_html - HTML for Course form
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function output_reorder_form() {
		$str_form_html = $this->form_field_group_id();
		$str_form_html .= $this->form_field_video_order();

		return $str_form_html;
	}

	/* This function creates the text box for inputting Course Name, including the current value of course_name
	Returns:
	$str_form_field_html
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	private function form_field_video_order() {
		$str_form_field_html = '<div class="form-fieldset"><div class="form-fieldset-title">Use The Up & Down Arrows To Reorder Your Videos</div>';
		$str_form_field_html .= '<div class="form-fieldset-input"><div class="updown-left-side">';
		$str_form_field_html .= '<select id="video_id" width="400" style="width: 400px;" multiple size="15">';
			// Get the Videos in order and output them into the select box
			$qry_videos = $this->get_group_videos();
			$ary_video_order = array();

			foreach ( $qry_videos AS $video ) {
				$str_form_field_html .= '<option value="' . $video->video_id . '">' . $video->video_name . '</option>';
				array_push( $ary_video_order , $video->video_id );
			}
		$str_form_field_html .= '</select>';
		$str_form_field_html .= '<input type="hidden" name="video_order" id="video_order" value="' . implode( "," , $ary_video_order ) . '" /></div>';

		// Add Up/Down arrows
		$str_form_field_html .= '<div class="updown-right-side">';
		$str_form_field_html .= '<img src="' . MVOB_IMAGES_URL . 'up-arrow.png" class="arrow" onclick="move_options_up( \'video_id\' , \'video_order\' );" />';
		$str_form_field_html .= '<img src="' . MVOB_IMAGES_URL . 'down-arrow.png" class="arrow" onclick="move_options_down( \'video_id\' , \'video_order\' );" />';

		$str_form_field_html .= '</div></div></div>';
		return $str_form_field_html;
	}
}
?>