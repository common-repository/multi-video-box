<?php
function mvob_create_database_tables() {
	/* Setup the database tables */
	$str_create_mvob_videos_table = "CREATE TABLE " . MVOB_VIDS_TABLE_NAME . " (
		video_id int(11) NOT NULL AUTO_INCREMENT,
		video_name char(150) NOT NULL,
		video_description text NOT NULL,
		video_url varchar(255) NOT NULL,
		PRIMARY KEY (video_id)
		);";
	$str_create_mvob_groups_table = "CREATE TABLE " . MVOB_GRPS_TABLE_NAME . " (
		group_id int(11) NOT NULL AUTO_INCREMENT,
		group_name char(100) NOT NULL,
		group_display_width int(11) NOT NULL,
		group_display_height int(11) NOT NULL,
		PRIMARY KEY (group_id)
		);";
	$str_create_mvob_vtog_table = "CREATE TABLE " . MVOB_VTOG_TABLE_NAME . " (
		video_id int(11) NOT NULL,
		group_id int(11) NOT NULL,
		video_order int(11) NOT NULL,
		PRIMARY KEY (video_id,group_id)
		);";

	// Return an array of the Create Table strings
	$ary_table_sql = array ( $str_create_mvob_videos_table , $str_create_mvob_groups_table , $str_create_mvob_vtog_table );
	return $ary_table_sql;
}

function mvob_delete_database_tables() {
	$str_delete_vids_table = "DROP TABLE IF EXISTS " . MVOB_VIDS_TABLE_NAME . ";";
	$str_delete_grps_table = "DROP TABLE IF EXISTS " . MVOB_GRPS_TABLE_NAME . ";";
	$str_delete_vtog_table = "DROP TABLE IF EXISTS " . MVOB_VTOG_TABLE_NAME . ";";

	// Return an array of the Delete Table strings
	$ary_table_sql = array ( $str_delete_vids_table , $str_delete_grps_table , $str_delete_vtog_table );
	return $ary_table_sql;
}
?>