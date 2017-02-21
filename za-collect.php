<?php

/**
 *
 * @link              kjhuer.com
 * @since             1.0.0
 * @package           Za_Collect
 *
 * @wordpress-plugin
 * Plugin Name:       zaCollect
 * Plugin URI:        http://kjhuer.com/portfolio_post/zacollect/
 * Description:       Displays 'collections' from Zazzle.com on posts and pages by using shortcodes
 * Version:           1.0.2
 * Author:            Kevin J Huer
 * Author URI:        http://kjhuer.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       za-collect
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-za-collect-activator.php
 */
function activate_za_collect() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-za-collect-activator.php';
	Za_Collect_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-za-collect-deactivator.php
 */
function deactivate_za_collect() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-za-collect-deactivator.php';
	Za_Collect_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_za_collect' );
register_deactivation_hook( __FILE__, 'deactivate_za_collect' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-za-collect.php';

if( ! class_exists( 'Smashing_Updater' ) ){
	include_once( plugin_dir_path( __FILE__ ) . 'class-za-collect-updater.php' );
}
if (is_admin()){
$updater = new Smashing_Updater( __FILE__ );
$updater->set_username( 'kjameshuer' );
$updater->set_repository( 'za-collect' );
$updater->initialize();
}
/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_za_collect() {

	$plugin = new Za_Collect();
	$plugin->run();

}
run_za_collect();
