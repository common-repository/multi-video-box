<?php
	// If "message" was passed on the query string, output the correct message
	if ( isset( $_REQUEST['message'] ) && ( $_REQUEST['message'] == "video-deleted" ) ) {
		echo '<div class="updated"><p><strong>Video deleted</strong></p></div>';
	}

	$ary_all_video_ids = mvob_video::get_video_ids();
	$pagination = new pagination( array( 'items_per_page' => MVOB_PAGINATION ) );
	$ary_video_ids = $pagination->paginate_results( $ary_all_video_ids );
?>

<div class="wrap">
	<?php echo $pagination->pagination_html( ( $ary_all_video_ids !== false ? count( $ary_all_video_ids ) : 0 ) ); ?>

	<table class="widefat">
		<thead>
			<tr>
				<th width="15%" class="column-title">Video</th>
				<th width="45%" class="column-title">Description</th>
				<th width="10%" class="column-title">Domain</th>
				<th width="30%" class="column-title">Actions</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th width="15%" class="column-title">Video</th>
				<th width="45%" class="column-title">Description</th>
				<th width="10%" class="column-title">Domain</th>
				<th width="30%" class="column-title">Actions</th>
			</tr>
		</tfoot>
		<tbody>
	<?php
		// If there are records, output them
		if ( is_array( $ary_video_ids ) ) {
			foreach ( $ary_video_ids AS $key => $video_id ) {
				$atts['video_id'] = $video_id;
				$current_video = new mvob_video( $atts ); ?>

				<tr<?php echo ( $key%2 == 1 ? ' class="even-row"' : '' ); ?>>
				<td><?php echo $current_video->get_video_name(); ?></td>
				<td><?php echo substr( $current_video->get_video_description() , 0 , 80 ) . ( strlen( $current_video->get_video_description() ) > 80 ? '...' : '' ); ?></td>
				<td class="center-column"><a href="<?php echo $current_video->get_video_url(); ?>" target="_blank"><?php echo $current_video->get_video_domain(); ?></a></td>
				<td class="center-column"><a href="admin.php?page=mvob_videos&action=edit&video_id=<?php echo $current_video->get_video_id(); ?>">Edit</a> | <a href="admin.php?page=mvob_videos&action=assign-groups&video_id=<?php echo $current_video->get_video_id(); ?>">Assign To Groups (<?php echo $current_video->count_assigned_groups(); ?>)</a> | <a href="admin.php?page=mvob_videos&action=get-shortcode&video_id=<?php echo $current_video->get_video_id(); ?>">Get Shortcode</a> | <a href="admin.php?page=mvob_videos&action=delete&video_id=<?php echo $current_video->get_video_id(); ?>">Delete</a></td>
				</tr>
		<?php
			}
		}
		else { ?>
			<tr><td colspan="4">You have not added any videos yet</td></tr>
		<?php } ?>
		</tbody>
	</table>
	<p class="submit"><input type="button" class="button-primary" name="Add A New Video" value="<?php _e('Add A New Video', 'skcm_trdom' ) ?>" onClick="parent.location='admin.php?page=mvob_videos&action=add'" /></p>
	<?php
		global $mvob_paypal;
		echo $mvob_paypal->paypal_donation_form( 'footer' );
	?>
</div>