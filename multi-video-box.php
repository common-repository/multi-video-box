<?php
/*
Plugin Name: Multi Video Box
Plugin URI: http://www.nuttymango.com/multi-video-box/
Description: "Multi Video Box" gives you the capability to output numerous videos on a single page, but only take up the space of a single video.  Uses tabbed navigation to switch videos.
Version: 1.5.2
Author: Scott Kustes
Author URI: http://www.nuttymango.com/
License: GPL2
THIS PLUG-IN IS NO LONGER SUPPORTED BY THE DEVELOPER.
*/

/* - - - - - - - - - - - - - - - - - - - - - - - - - -
		Define global variables
- - - - - - - - - - - - - - - - - - - - - - - - - - */
// Call MVOB classes
include( 'classes/class_includes.php' );
// Include PPD
include( 'classes/class.paypal_donation/class.paypal_donation.php' );

global $wpdb;

/*global $ary_mvob_ajax_screens;
$ary_mvob_ajax_screens = array ( 'assign-groups' , 'assign-videos' );*/

// Constants for MVOB definition
if ( !defined( 'MVOB_DB_VERS' ) )
	define( 'MVOB_DB_VERS' , '1.5.2' );
if ( !defined( 'MVOB_PLUGIN_URL' ) )
	define( 'MVOB_PLUGIN_URL' , plugin_dir_url( __FILE__ ) );
if ( !defined( 'MVOB_PLUGIN_DIR' ) )
	define( 'MVOB_PLUGIN_DIR' , plugin_dir_path( __FILE__ ) );
if ( !defined( 'MVOB_IMAGES_URL' ) )
	define( 'MVOB_IMAGES_URL' , MVOB_PLUGIN_URL . 'images/' );
if ( !defined( 'MVOB_IMAGES_DIR' ) )
	define( 'MVOB_IMAGES_DIR' , MVOB_PLUGIN_DIR . 'images/' );
// Constants for database table names
if ( !defined( 'MVOB_VIDS_TABLE_NAME' ) )
	define( 'MVOB_VIDS_TABLE_NAME' , $wpdb->prefix . "mvob_videos" );
if ( !defined( 'MVOB_GRPS_TABLE_NAME' ) )
	define( 'MVOB_GRPS_TABLE_NAME' , $wpdb->prefix . "mvob_groups" );
if ( !defined( 'MVOB_VTOG_TABLE_NAME' ) )
	define( 'MVOB_VTOG_TABLE_NAME' , $wpdb->prefix . "mvob_videos_to_groups" );

/* Runs when plugin is activated */
register_activation_hook( __FILE__ , 'mvob_install' );

/* Runs when plugin is deactivated AND deleted */
register_uninstall_hook( __FILE__ , 'mvob_uninstall' );

/* Register Options */
add_action( 'admin_init' , 'mvob_admin_init' );

/* Register shortcodes */
add_action( 'init' , 'register_mvob_shortcodes');
function register_mvob_shortcodes () {
	/* Register Shortcode for outputting Videos */
	add_shortcode( 'mvob' , 'mvob_output' );
}

/* Installation function:
Sets up database tables
Sets up Wordpress options
------------------------------ */
function mvob_install () {
	global $wpdb;

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	// Get the SQL for creating the database tables
	$ary_create_table_sql = mvob_create_database_tables();

	// Loop the array, creating tables using Wordpress function dbDelta
	foreach ( $ary_create_table_sql AS $table_sql ) {
		dbDelta( $table_sql );
	}

	mvob_add_default_options();
}

/* Uninstall function
------------------------------ */
function mvob_uninstall () {
	global $wpdb;

	// Get the SQL for creating the database tables
	$ary_delete_table_sql = mvob_delete_database_tables();

	// Loop the array, deleting tables
	foreach ( $ary_delete_table_sql AS $table_sql ) {
		$qry_delete_table = $wpdb->query( $wpdb->prepare( $table_sql , "" ) );
	}

	delete_option( 'mvob_options' );
}

/* Function to create admin menu
------------------------------ */
add_action( 'admin_menu' , 'mvob_add_admin_menu' );
function mvob_add_admin_menu () {
	global $mvob_home;
	global $mvob_videos;
	global $mvob_groups;
	global $mvob_inst;
	global $mvob_settings;

	/* Create Menu */
	$mvob_home = add_menu_page( 'Multi Video Box', 'Multi Video Box', 'administrator', 'mvob', 'mvob_menu_main_page' );
	$mvob_inst = add_submenu_page( 'mvob', 'Instructions', 'Instructions', 'administrator', 'mvob', 'mvob_menu_main_page' );
	$mvob_videos = add_submenu_page( 'mvob', 'Videos', 'Videos', 'administrator', 'mvob_videos', 'mvob_menu_videos' );
	$mvob_groups = add_submenu_page( 'mvob', 'Video Groups', 'Video Groups', 'administrator', 'mvob_groups', 'mvob_menu_groups' );
	$mvob_settings = add_submenu_page( 'mvob', 'Settings', 'Settings', 'administrator', 'mvob_settings', 'mvob_menu_settings' );
}


/* Function to add Wordpress options
------------------------------ */
function mvob_add_default_options() {
	$mvob_options = get_option( 'mvob_options' );

	// If the option isn't an array (i.e., options don't exist), create the options
	if ( !is_array( $mvob_options ) || ( $mvob_options['mvob_version'] != MVOB_DB_VERS ) ) {
		/* Create array of options */
		$ary_options = array(
			'mvob_version' => MVOB_DB_VERS,
			'mvob_pagination' => 20,
			'mvob_default_width' => 480,
			'mvob_default_height' => 385,
			'mvob_give_props' => 'n'
			);

		update_option( 'mvob_options' , $ary_options );
	}
}

/* - Register CSS and AJAX for public side of site - */
if ( is_admin() ) {
	// Register AJAX for getting Video Embed code
	add_action( 'wp_ajax_mvob_get_video_embed' , 'ajax_mvob_get_video_embed' );
	add_action( 'wp_ajax_nopriv_mvob_get_video_embed' , 'ajax_mvob_get_video_embed' );
}
add_action( 'wp_enqueue_scripts' , 'mvob_public_styles' );
function mvob_public_styles () {
	// Public styles for Content Manager
	wp_register_style( 'mvob-public-styles', MVOB_PLUGIN_URL . 'css/mvob_public_style.css' , __FILE__ , '20130515' , 'all' );
	wp_enqueue_style( 'mvob-public-styles' );

	wp_register_script( 'jquery-mvob', 'http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js' );
	wp_enqueue_script( 'jquery-mvob' );

	// Include AJAX scripts
	wp_enqueue_script( 'mvob-ajax' , MVOB_PLUGIN_URL . 'js/mvob_public_ajax.js' , array( 'jquery' ) );
	wp_localize_script( 'mvob-ajax' , 'mvob_ajax_data' ,
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'mvob_nonce' => wp_create_nonce( 'mvob_nonce' )
		) );
}
add_action( 'admin_print_styles' , 'mvob_admin_styles');
function mvob_admin_styles () {
	global $mvob_home;
	global $mvob_videos;
	global $mvob_groups;
	global $mvob_inst;
	global $mvob_settings;

	$screen = get_current_screen();

	if ( ( $screen->id == $mvob_main ) || ( $screen->id == $mvob_settings ) || ( $screen->id == $mvob_inst ) || ( $screen->id == $mvob_videos ) || ( $screen->id == $mvob_groups ) ) {
		// Admin styles
		wp_register_style( 'mvob-styles', MVOB_PLUGIN_URL . 'css/mvob_style_compressed.css' , __FILE__ , '2013427' , 'all' );
		wp_enqueue_style( 'mvob-styles' );
	}
}

/* Register JS */
add_action( 'admin_print_scripts' , 'mvob_admin_scripts');
function mvob_admin_scripts () {
	global $mvob_home;
	global $mvob_videos;
	global $mvob_groups;
	global $mvob_inst;
	global $mvob_settings;
	global $ary_mvob_ajax_screens;

	$screen = get_current_screen();

	if ( ( $screen->id == $mvob_videos ) || ( $screen->id == $mvob_groups ) ) {
		// Include jQuery
		/*wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js' );
		wp_enqueue_script( 'jquery' );
		wp_deregister_script( 'jquery-migrate' );
		wp_register_script( 'jquery-migrate', 'http://code.jquery.com/jquery-migrate-1.2.0.min.js' );
		wp_enqueue_script( 'jquery-migrate' );

		// If this is a screen that needs AJAX functions, load them
		if ( isset( $_REQUEST['action'] ) && is_numeric( array_search( $_REQUEST['action'] , $ary_mvob_ajax_screens ) ) ) {
			// AJAX for MVOB
			wp_register_script( 'mvob-ajax', MVOB_PLUGIN_URL . 'js/mvob_jquery_ajax.js' , __FILE__ );
			wp_enqueue_script( 'mvob-ajax' );
		}*/

		if ( ( $screen->id == $mvob_videos ) || ( $screen->id == $mvob_groups ) ) {
			// Admin scripts
			wp_register_script( 'mvob-scripts', MVOB_PLUGIN_URL . 'js/mvob_scripts.js' , __FILE__ );
			wp_enqueue_script( 'mvob-scripts' );
		}
	}

	if ( $screen->id == $mvob_inst ) {
		// Javascript for Instructions page
		wp_register_script( 'mvob-inst', MVOB_PLUGIN_URL . 'js/instructions.js' , __FILE__ );
		wp_enqueue_script( 'mvob-inst' );
	}
}

/* Register AJAX */
add_action( 'admin_init' , 'register_mvob_ajax' );
function register_mvob_ajax () {
	global $mvob_main;
	$screen = get_current_screen();

	if ( $screen->id == $mvob_main ) {
		// Register AJAX for adding Videos to Groups
		add_action( 'wp_ajax_mvob_add_videos_to_groups' , 'ajax_mvob_add_videos_to_groups' );

		// Register AJAX for removing Videos to Groups
		add_action( 'wp_ajax_mvob_remove_videos_from_groups' , 'ajax_mvob_remove_videos_from_groups' );
	}
}

/* Function to initialize Admin
------------------------------ */
function mvob_admin_init() {
	// Set the default pagination and extension settings into a constant
	$mvob_options = get_option( 'mvob_options' );

	if ( !defined( 'MVOB_PAGINATION' ) )
		define( 'MVOB_PAGINATION' , $mvob_options['mvob_pagination'] );
	if ( !defined( 'MVOB_DEFAULT_WIDTH' ) )
		define( 'MVOB_DEFAULT_WIDTH' , $mvob_options['mvob_default_width'] );
	if ( !defined( 'MVOB_DEFAULT_HEIGHT' ) )
		define( 'MVOB_DEFAULT_HEIGHT' , $mvob_options['mvob_default_height'] );
}

/* Include other Wordpress specific function files to tie MVOB into WP */
include( 'functions/functions_shortcodes.php' );

/* Include files specific to MVOB functionality*/
include( 'functions/functions_menu.php' );
include( 'functions/functions_output.php' );
include( 'functions/functions_settings.php' );
include( 'functions/functions_ajax.php' );
include( 'models/database.php' );
?>