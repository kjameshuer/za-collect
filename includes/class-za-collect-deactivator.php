<?php

/**
 * Fired during plugin deactivation
 *
 * @link       kjhuer.com
 * @since      1.0.0
 *
 * @package    Za_Collect
 * @subpackage Za_Collect/includes
 */

/**
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Za_Collect
 * @subpackage Za_Collect/includes
 * @author     Kevin J Huer <kjhuer@gmail.com>
 */
class Za_Collect_Deactivator {

	/**
	 * Removes shortcode added.
	 *
	 * Removes shortcode added.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
            remove_shortcode('za-collect');
	}

}
