<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that
 * also follow WordPress Coding Standards and PHP best practices.
 *
 * @package   FacebookFetcher
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 *
 * @wordpress-plugin
 * Plugin Name:       WP Facebook Fetcher
 * Plugin URI:        @TODO
 * Description:       Use it to fetch post and medias from facebook page or user.
 * Version:           1.0.0
 * Author:            Julien Petit
 * Author URI:        http://www.julienpetit.fr
 * Text Domain:       facebook-fetcher
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/<owner>/<repo>
 * WordPress-Plugin-Boilerplate: v2.6.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/


function print_r_html($v) {
  echo "<pre>";
  print_r($v);
  echo "</pre>";
}
/*
 * @TODO:
 *
 * - replace `class-plugin-name.php` with the name of the plugin's class file
 *
 */
require_once( plugin_dir_path( __FILE__ ) . 'public/class-facebook-fetcher.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 * @TODO:
 *
 * - replace FacebookFetcher with the name of the class defined in
 *   `class-plugin-name.php`
 */
register_activation_hook( __FILE__, array( 'FacebookFetcher', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'FacebookFetcher', 'deactivate' ) );

/*
 * @TODO:
 *
 * - replace FacebookFetcher with the name of the class defined in
 *   `class-plugin-name.php`
 */
add_action( 'plugins_loaded', array( 'FacebookFetcher', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * @TODO:
 *
 * - replace `class-plugin-name-admin.php` with the name of the plugin's admin file
 * - replace FacebookFetcher_Admin with the name of the class defined in
 *   `class-plugin-name-admin.php`
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() ) {
  require_once( plugin_dir_path( __FILE__ ) . 'admin/libs/facebook.php' );
  require_once( plugin_dir_path( __FILE__ ) . 'admin/models/class-facebook-album-model.php' );
  require_once( plugin_dir_path( __FILE__ ) . 'admin/models/class-facebook-post-model.php' );
  require_once( plugin_dir_path( __FILE__ ) . 'admin/models/class-facebook-fetcher-auth.php' );
  require_once( plugin_dir_path( __FILE__ ) . 'admin/controllers/controller.php' );
  require_once( plugin_dir_path( __FILE__ ) . 'admin/controllers/default-controller.php' );
  require_once( plugin_dir_path( __FILE__ ) . 'admin/controllers/album-controller.php' );
  require_once( plugin_dir_path( __FILE__ ) . 'admin/controllers/post-controller.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-facebook-fetcher-admin.php' );
	add_action( 'plugins_loaded', array( 'FacebookFetcher_Admin', 'get_instance' ) );

}
