<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordlift.io
 * @since      1.0.0
 *
 * @package    Wordlift_Taxonomy_Manager
 * @subpackage Wordlift_Taxonomy_Manager/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wordlift_Taxonomy_Manager
 * @subpackage Wordlift_Taxonomy_Manager/admin
 * @author     WordLift <contact@wordlift.io>
 */
class Wordlift_Taxonomy_Manager_Admin {
	/**
	 * The post types that will be processed
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $post_types Post types that will be processed.
	 */
	protected $post_types = array(
		'post',
		'page'
	);

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		// TO DO : Find a way to async process all posts.
	}

	/**
	 * Check whether the post has entity type associated and set default term if it hasn't.
	 *
	 * @since 1.0.0
	 *
	 * @param int $id The {@link WP_Post}'s id.
	 */
	protected function maybe_set_default_term( $id ) {
		// Check whether the post has any of the WordLift entity types.
		$has_term = has_term( '', Wordlift_Entity_Types_Taxonomy_Service::TAXONOMY_NAME, $id );

		// Bail if the term is associated with entity types already.
		if ( ! empty( $has_term ) ) {
			return;
		}

		// Set the default `article` term.
		wp_set_object_terms( $id, 'article', Wordlift_Entity_Types_Taxonomy_Service::TAXONOMY_NAME );

	}

	// TO DO : Find a way to async process all posts.
	/**
	 * Get all posts that didn't have `wl_entity_type` taxonomy term assigned.
	 *
	 * @since 1.0.0
	 *
	 * @return type 
	 */
	public function get_posts() {
		// Will retrieve all posts that didn't have any `wl_entity_type` taxonomy term.
		$args = array(
			'post_type' => $this->get_post_types(),
			'tax_query' => array(
				array(
					'taxonomy' => Wordlift_Entity_Types_Taxonomy_Service::TAXONOMY_NAME,
					'operator' => 'NOT EXISTS',
				),
			),
		);

		// Get the posts.
		$posts = get_posts( $args );

		foreach ( $posts as $p ) {
			$this->maybe_set_default_term( $p->ID );
		}
	}

	/**
	 * Returns the post types that will be processed.
	 *
	 * @since 1.0.0
	 * 
	 * @return array Post types that will be processed.
	 */
	protected function get_post_types() {
		// Get the existing types.
		$types = $this->post_types;

		/**
		 * Filter: 'wl_taxonomy_manager_post_types' - Allow third parties to
		 * hook and extend the manager post types.
		 *
		 * @since  1.0.0
		 *
		 * @param  array $types The post types that will be used by manager.
		 */
		return apply_filters( 'wl_taxonomy_manager_post_types', $types );
	}

}
