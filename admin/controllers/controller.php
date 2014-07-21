<?php
/**
 * Plugin Name.
 *
 * @package   FacebookFetcher_Admin
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-plugin-name.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package FacebookFetcher_Admin
 * @author  Your Name <email@example.com>
 */
class JPController {

	protected function addScript($script) {
		wp_enqueue_script( FacebookFetcher::get_instance()->get_plugin_slug() . '-admin-script', 
											 plugins_url( '../' . $script, __FILE__ ), 
											 array( 'jquery' ), 
											 FacebookFetcher::VERSION 
		);
	}

	protected function addStyle($style) {
		wp_enqueue_style( FacebookFetcher::get_instance()->get_plugin_slug() . '-admin-style', 
											 plugins_url( '../' . $style, __FILE__ )
		);
	}

}
