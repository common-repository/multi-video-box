<?php
// ------------------------------------------------------------------------
// This file declares the mvob_video_form class.
// This class extends the mvob_video class.             
// ------------------------------------------------------------------------
class mvob_video_form extends mvob_video {
/* - - - - - - - - - - - - - - - - - - - 
Attributes
- - - - - - - - - - - - - - - - - - - - */
	protected $form_type;		// "add" or "edit"

/* - - - - - - - - - - - - - - - - - - - 
Constructor & Product Retrieval Functions
- - - - - - - - - - - - - - - - - - - - */
	/* Constructor function to create a new Video Form instance
	Parameters:
	$atts - An array of values to use in creating the Video
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function __construct( $atts ) {
		parent::__construct( $atts );
	}

/* - - - - - - - - - - - - - - - - - - - 
Form Field Output Functions
- - - - - - - - - - - - - - - - - - - - */
	/* These functions create the HTML for the form fields for a Video
	Parameters:
	$form_type - 'add' or 'edit'

	Returns:
	$str_form_html - HTML for Ad form
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function output_form_fields( $form_type = 'add' ) {
		$str_form_html = '<div class="manage-column-left">';
		if ( $form_type == 'edit' )
			$str_form_html .= $this->form_field_video_id();
		$str_form_html .= $this->form_field_video_name();
		$str_form_html .= $this->form_field_video_description();
		$str_form_html .= $this->form_field_video_url();
		$str_form_html .= '</div>';

		// Output the Groups select boxes
		if ( $form_type == "add" ) {
			$str_form_html .= '<div class="manage-column-right">';
			$str_form_html .= $this->form_field_video_groups();
			$str_form_html .= '</div>';
		}
		// If this is an Edit form, output the current Video or Content
		else if ( $form_type == "edit" ) {
			$str_form_html .= '<div class="manage-column-right">';
			$str_form_html .= '<h3>Current Video</h3>';
			$str_form_html .= $this->get_video_file_embed();
			$str_form_html .= '</div>';
		}

		return $str_form_html;
	}

	private function form_field_video_id() {
		$str_form_field_html = '<input type="hidden" name="video_id" id="video_id" value="' . $this->video_id . '" />';

		return $str_form_field_html;
	}

	private function form_field_video_name() {
		$str_form_field_html = '<div class="form-fieldset"><div class="form-fieldset-title required-field">What Is The Name Of The Video? <span class="required-field">*</span></div>';
		$str_form_field_html .= '<div class="form-fieldset-input">';
		$str_form_field_html .= '<input type="text" name="video_name" value="' . str_replace( '"' , '&quot;' , $this->video_name ) . '" maxlength="150" size="50" onfocus="show_hide_instruction_div(\'inst_video_name\',\'show\');" onblur="show_hide_instruction_div(\'inst_video_name\',\'hide\');" />';

		// Output a div with instructions for this field
		$str_form_field_html .= '<div class="instruction-bubble" id="inst_video_name">This is the name that will be displayed publicly.  It must be unique.<div class="instruction-bubble-arrow-border"></div><div class="instruction-bubble-arrow"></div></div>';

		$str_form_field_html .= '</div></div>';
		return $str_form_field_html;
	}

	private function form_field_video_description() {
		$str_form_field_html = '<div class="form-fieldset"><div class="form-fieldset-title">Give This Video A Description</div>';
		$str_form_field_html .= '<div class="form-fieldset-input">';
		$str_form_field_html .= '<textarea name="video_description" rows="8" cols="50" onfocus="show_hide_instruction_div(\'inst_video_description\',\'show\');" onblur="show_hide_instruction_div(\'inst_video_description\',\'hide\');">' . $this->video_description . '</textarea>';

		// Output a div with instructions for this field
		$str_form_field_html .= '<div class="instruction-bubble" id="inst_video_description">This is a description of the video and will be displayed publicly.<div class="instruction-bubble-arrow-border"></div><div class="instruction-bubble-arrow"></div></div>';

		$str_form_field_html .= '</div></div>';
		return $str_form_field_html;
	}

	private function form_field_video_url() {
		$str_form_field_html = '<div class="form-fieldset"><div class="form-fieldset-title required-field">Enter The Video URL Here: <span class="required-field">*</span></div>';
		$str_form_field_html .= '<div class="form-fieldset-input">';
		$str_form_field_html .= '<input type="text" name="video_url" value="' . htmlentities( $this->video_url ) . '" maxlength="200" size="50" onfocus="show_hide_instruction_div(\'inst_video_url\',\'show\');" onblur="show_hide_instruction_div(\'inst_video_url\',\'hide\');" />';

		// Output a div with instructions for this field
		$str_form_field_html .= '<div class="instruction-bubble" id="inst_video_url">Paste the url of the video you want to use here.<div class="instruction-bubble-arrow-border"></div><div class="instruction-bubble-arrow"></div></div>';

		$str_form_field_html .= '</div></div>';
		return $str_form_field_html;
	}

	private function form_field_video_groups() {
		// Get the Video Groups
		$qry_video_groups = mvob_group::get_group_ids();

		$str_form_field_html = '<div class="form-fieldset"><div class="form-fieldset-title">Do You Want To Assign This Video To Any Groups?</div>';
		$str_form_field_html .= '<div class="form-fieldset-input">';
		$str_form_field_html .= '<select name="group_id[]" multiple="multiple" size="8">';

		foreach ( $qry_video_groups AS $group ) {
			$current_group = new mvob_group( array( 'group_id' => $group ) );

			$str_form_field_html .= '<option value="' . $current_group->get_group_id() . '">' . $current_group->get_group_name() . '</option>';
		}

		$str_form_field_html .= '</select>';

		$str_form_field_html .= '</div></div>';
		return $str_form_field_html;
	}

/* - - - - - - - - - - - - - - - - - - - 
Add Video To Groups Form Functions
- - - - - - - - - - - - - - - - - - - - */
	/* These functions create the HTML for the form fields for adding $this Video to Groups
	Returns:
	$str_form_html - HTML for Ad form
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function output_assign_form_fields() {
		$str_form_html = '<div class="assign-div">';
		$str_form_html .= $this->form_field_message_box();

		$str_form_html .= '<div class="assign-left">';
		$str_form_html .= $this->form_field_video_id();
		$str_form_html .= $this->form_field_unassigned_groups();
		$str_form_html .= '</div>';

		$str_form_html .= '<div class="assign-arrows">';
		$str_form_html .= $this->form_field_arrows();
		$str_form_html .= '</div>';

		$str_form_html .= '<div class="assign-right">';
		$str_form_html .= $this->form_field_assigned_groups();
		$str_form_html .= '</div></div>';

		return $str_form_html;
	}

	private function form_field_message_box() {
		$str_form_field_html = '<div id="assign-message" class="updated"></div>';

		return $str_form_field_html;
	}

	private function form_field_unassigned_groups() {
		// Get the Video Groups
		$qry_unassigned_groups = $this->get_video_groups( 'unassigned' );

		$str_form_field_html = '<h3>Unassigned Groups</h3>';
		$str_form_field_html .= '<select name="group_id_add[]" id="group_id_add" multiple="multiple" size="8" class="assign-select">';

		// If there are unassigned Groups, output them
		if ( count( $qry_unassigned_groups ) > 0 ) {
			foreach ( $qry_unassigned_groups AS $group ) {
				$str_form_field_html .= '<option value="' . $group->group_id . '">' . $group->group_name . '</option>';
			}
		}
		else {
			$str_form_field_html .= '<option value="0">This Video Is In All Groups</option>';
		}

		$str_form_field_html .= '</select>';

		return $str_form_field_html;
	}

	private function form_field_assigned_groups() {
		// Get the Video Groups
		$qry_assigned_groups = $this->get_video_groups( 'assigned' );

		$str_form_field_html = '<h3>Assigned Groups</h3>';
		$str_form_field_html .= '<select name="group_id_remove[]" id="group_id_remove" multiple="multiple" size="8" class="assign-select">';

		// If there are assigned Groups, output them
		if ( count( $qry_assigned_groups ) > 0 ) {
			foreach ( $qry_assigned_groups AS $group ) {
				$str_form_field_html .= '<option value="' . $group->group_id . '">' . $group->group_name . '</option>';
			}
		}
		else {
			$str_form_field_html .= '<option value="0">No Groups Assigned</option>';
		}

		$str_form_field_html .= '</select>';

		return $str_form_field_html;
	}

	private function form_field_arrows() {
		$str_form_field_html = '<input type="submit" class="right_arrow" name="group_add_button" id="group_add_button" alt="Assign To Groups" value="" />';
		$str_form_field_html .= '<input type="submit" class="left_arrow" name="group_remove_button" id="group_remove_button" alt="Remove From Groups" value="" />';

		return $str_form_field_html;
	}

	private function form_field_arrows_ajax() {
		$str_form_field_html = '<img src="' . MVOB_IMAGES_URL . 'right-arrow.png" class="arrow" id="group_add_button" /><br/>';
		$str_form_field_html .= '<img src="' . MVOB_IMAGES_URL . 'left-arrow.png" class="arrow" id="group_remove_button" /><br/>';

		return $str_form_field_html;
	}
}
?>