<?php
	// If "message" was passed on the query string, output the correct message
	if ( isset( $_REQUEST['message'] ) && ( $_REQUEST['message'] == "group-deleted" ) ) {
		echo '<div class="updated"><p><strong>Group deleted</strong></p></div>';
	}

	$ary_all_group_ids = mvob_group::get_group_ids();
	$pagination = new pagination( array( 'items_per_page' => MVOB_PAGINATION ) );
	$ary_group_ids = $pagination->paginate_results( $ary_all_group_ids );
?>

<div class="wrap">
	<?php echo $pagination->pagination_html( ( $ary_all_group_ids !== false ? count( $ary_all_group_ids ) : 0 ) ); ?>

	<table class="widefat">
		<thead>
			<tr>
				<th width="30%" class="column-title">Group</th>
				<th width="20%" class="column-title">Display Size</th>
				<th width="20%" class="column-title">Video Count</th>
				<th width="30%" class="column-title">Actions</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th width="30%" class="column-title">Group</th>
				<th width="20%" class="column-title">Display Size</th>
				<th width="20%" class="column-title">Video Count</th>
				<th width="30%" class="column-title">Actions</th>
			</tr>
		</tfoot>
		<tbody>
	<?php
		// If there are records, output them
		if ( is_array( $ary_group_ids ) ) {
			foreach ( $ary_group_ids AS $key => $group_id ) {
				$current_group = new mvob_group( array( 'group_id' => $group_id ) ); ?>

				<tr<?php echo ( $key%2 == 1 ? ' class="even-row"' : '' ); ?>>
				<td><?php echo $current_group->get_group_name(); ?></td>
				<td class="center-column"><?php echo $current_group->get_group_display_width() . ' x ' . $current_group->get_group_display_height(); ?></td>
				<td class="center-column"><?php echo $current_group->count_group_videos(); ?></td>
				<td class="center-column"><a href="admin.php?page=mvob_groups&action=edit&group_id=<?php echo $current_group->get_group_id(); ?>">Edit</a> | <a href="admin.php?page=mvob_groups&action=assign-videos&group_id=<?php echo $current_group->get_group_id(); ?>">Assign Videos</a> | <a href="admin.php?page=mvob_groups&action=videos-order&group_id=<?php echo $current_group->get_group_id(); ?>">Set Video Order</a> | <a href="admin.php?page=mvob_groups&action=get-shortcode&group_id=<?php echo $current_group->get_group_id(); ?>">Get Shortcode</a> | <a href="admin.php?page=mvob_groups&action=delete&group_id=<?php echo $current_group->get_group_id(); ?>">Delete</a></td>
				</tr>
		<?php
			}
		}
		else { ?>
			<tr><td colspan="3">You have not added any Groups yet</td></tr>
		<?php } ?>
		</tbody>
	</table>
	<p class="submit"><input type="button" class="button-primary" name="Add A New Group" value="<?php _e('Add A New Group', 'skcm_trdom' ) ?>" onClick="parent.location='admin.php?page=mvob_groups&action=add'" /></p>
	<?php
		global $mvob_paypal;
		echo $mvob_paypal->paypal_donation_form( 'footer' );
	?>
</div>