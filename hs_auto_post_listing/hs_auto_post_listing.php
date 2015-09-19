<?php
/**
 * @package Auto-Post Listing
 * @version 0.0.1
 */
/*
Plugin Name: Auto-Post Listing from PremiumPress to Facebook and Tumblr.
Plugin URI: http://silverhoneymedia.com
Description: Auto-post listing to social media.
Author: Honey Silvas
Version: 0.0.1
Author URI: http://silverhoneymedia.com
*/

function hs_auto_post_listing(){
	hs_auto_post_to_facebook();
	hs_auto_post_to_tumblr();	
}

function hs_auto_post_main(){
	require_once ( plugin_dir_path( __FILE__ ) . "vendor/autoload.php" );	
	require_once ( plugin_dir_path( __FILE__ ) . "script/listing.php" );	
	require_once ( plugin_dir_path( __FILE__ ) . "script/tumblr.php" );	
	require_once ( plugin_dir_path( __FILE__ ) . "script/facebook.php" );	
	
	add_action( "hook_add_form_post_save", "hs_auto_post_listing" );
}

hs_auto_post_main();

?>