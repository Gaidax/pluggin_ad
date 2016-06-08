<?php

/**
 * @package Prototype
 * @version 0.01
 */
/*
Plugin Name: MyPrototype
Plugin URI: not-yet.com
Description: Testing plugin
Author: Oleg Olegov
Version: Alpha
Author URI: not-yet.com
*/

define( 'PLUGIN_DIR', dirname(__FILE__).'/' );  
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
include( plugin_dir_path( __FILE__ ) . '/functions.php');
include( plugin_dir_path( __FILE__ ) . '/metabox.php');
include( plugin_dir_path( __FILE__ ) . '/templater.php');
include( plugin_dir_path( __FILE__ ) . '/ChromePhp.php');


function insert_thing() {
	//$directory = get_stylesheet_directory_uri();
	wp_enqueue_script('script0', plugin_dir_url(__FILE__) . 'js/test.js');
	echo "<p id = 'directory'></p>";
}

add_action( 'admin_notices', 'insert_thing' );

function dir_css() {
	
	$x = is_rtl() ? 'left' : 'right';

	echo "
	<style type='text/css'>
	#dolly {
		float: $x;
		padding-$x: 15px;
		padding-top: 5px;		
		margin: 0;
		font-size: 11px;
	}
	</style>
	";
}

add_action( 'admin_head', 'dir_css' );

?>