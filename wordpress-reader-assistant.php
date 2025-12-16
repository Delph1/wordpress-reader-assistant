<?php
/**
 * Plugin Name: WordPress Reader Assistant
 * Plugin URI: https://github.com/Delph1/wordpress-reader-assistant
 * Description: A floating reader assistant that makes it easier to navigate long articles with a collapsible table of contents and search functionality.
 * Version: 1.0.0
 * Author: Andreas Galistel
 * License: Apache 2.0
 * Text Domain: wordpress-reader-assistant
 * Domain Path: /languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants
define( 'WRA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WRA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WRA_PLUGIN_VERSION', '1.0.0' );

// Include the main plugin class
require_once WRA_PLUGIN_DIR . 'inc/class-reader-assistant.php';

// Initialize the plugin
function wra_init() {
	$reader_assistant = new WordPress_Reader_Assistant();
}
add_action( 'plugins_loaded', 'wra_init' );
