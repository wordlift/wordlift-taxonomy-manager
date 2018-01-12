<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wordlift.io
 * @since      1.0.0
 *
 * @package    Wordlift_Taxonomy_Manager
 * @subpackage Wordlift_Taxonomy_Manager/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wordlift_Taxonomy_Manager
 * @subpackage Wordlift_Taxonomy_Manager/includes
 * @author     WordLift <contact@wordlift.io>
 */
class Wordlift_Taxonomy_Manager_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wordlift-taxonomy-manager',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
