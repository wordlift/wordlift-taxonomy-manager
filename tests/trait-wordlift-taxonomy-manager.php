<?php
/**
 * Trait Wordlift_Taxonomy_Manager_Term_Service_Trait
 */
trait Wordlift_Taxonomy_Manager_Term_Service_Trait {

	/**
	 * @inheritdoc
	 */
	function set_up_unit_test() {
		// Activate WooCommerce related plugins.
		if ( ! is_plugin_active( 'wordlift/wordlift.php' ) ) {
			$this->assertNull( activate_plugin( 'wordlift/wordlift.php' ) );
		}

		// Allow plugins to set up themselves.
		do_action( 'plugins_loaded' );
		do_action( 'after_setup_theme' );
		do_action( 'init' );

		// Check that WooCommerce is properly installed.
		$this->assertTrue( class_exists( 'Wordlift_Entity_Types_Taxonomy_Service' ) );
	}
}