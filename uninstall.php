<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       kjhuer.com
 * @since      1.0.0
 *
 * @package    Za_Collect
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$option_name = 'za-collect';
 
delete_option($option_name);
 
// for site options in Multisite
delete_site_option($option_name);