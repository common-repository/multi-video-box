<p>
	<?php echo __( 'To display this Group, copy and paste the following code where you\'d like the video display to appear.', 'mvob_trdom' ); ?>
</p>
<p>
	<?php echo __( 'For additional parameters, see the <a href="?page=mvob&action=shortcode">Shortcode Instructions page.</a>', 'mvob_trdom' ); ?>
</p>

<div style="width:100%;">
	<blockquote style="border: 1px solid black; padding: 10px;">
		<?php echo "<strong>[mvob group=" . $_REQUEST['group_id'] . "]</strong>"; ?>
	</blockquote>
</div>