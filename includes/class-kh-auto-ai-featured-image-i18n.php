<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://knowhalim.com
 * @since      1.0.0
 *
 * @package    Kh_Auto_Ai_Featured_Image
 * @subpackage Kh_Auto_Ai_Featured_Image/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Kh_Auto_Ai_Featured_Image
 * @subpackage Kh_Auto_Ai_Featured_Image/includes
 * @author     Halim <knowhalimofficial@gmail.com>
 */
class Kh_Auto_Ai_Featured_Image_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'kh-auto-ai-featured-image',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
