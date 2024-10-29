<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://knowhalim.com
 * @since             1.0.0
 * @package           Kh_Auto_Ai_Featured_Image
 *
 * @wordpress-plugin
 * Plugin Name:       Auto AI Featured Image 
 * Plugin URI:        https://knowhalim.com/app/auto-ai-featured-image
 * Description:       Generate featured image easily and copyright-free using this powerful AI text to image generator by Artsmart
 * Version:           1.0.0
 * Author:            Halim
 * Author URI:        https://knowhalim.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       kh-auto-ai-featured-image
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'KH_AUTO_AI_FEATURED_IMAGE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-kh-auto-ai-featured-image-activator.php
 */
function activate_kh_auto_ai_featured_image() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kh-auto-ai-featured-image-activator.php';
	Kh_Auto_Ai_Featured_Image_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-kh-auto-ai-featured-image-deactivator.php
 */
function deactivate_kh_auto_ai_featured_image() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-kh-auto-ai-featured-image-deactivator.php';
	Kh_Auto_Ai_Featured_Image_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_kh_auto_ai_featured_image' );
register_deactivation_hook( __FILE__, 'deactivate_kh_auto_ai_featured_image' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-kh-auto-ai-featured-image.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_kh_auto_ai_featured_image() {

	$plugin = new Kh_Auto_Ai_Featured_Image();
	$plugin->run();

}
run_kh_auto_ai_featured_image();
