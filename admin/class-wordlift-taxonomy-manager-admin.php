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
		add_action( 'wp_ajax_set_article_term', array( $this, 'set_article_term' ) );
	}

	/**
	 * Get all posts/pages that didn't have `wl_entity_type` taxonomy term assigned.
	 *
	 * @since 1.0.0
	 *
	 * @return type
	 */
	public function get_posts() {
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
		$types = apply_filters( 'wl_taxonomy_manager_post_types', $types );

		// Will retrieve all posts that didn't have any `wl_entity_type` taxonomy term.
		$args = array(
			'post_type' => $types,
			'tax_query' => array(
				array(
					'taxonomy' => Wordlift_Entity_Types_Taxonomy_Service::TAXONOMY_NAME,
					'operator' => 'NOT EXISTS',
				),
			),
		);

		// Return the result.
		return get_posts( $args );
	}

	/**
	 * Set posts/pages default `article` entity term.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function set_article_term() {
		// Get posts.
		$posts = $this->get_posts();

		// Bail if there are no posts.
		// It will end the async loop.
		if ( empty( $posts ) ) {
			return;
		}

		// Loop throught all posts and set the default `article` term.
		foreach ( $posts as $p ) {
			// Set the default `article` term.
			wp_set_object_terms(
				$p->ID,
				'article',
				Wordlift_Entity_Types_Taxonomy_Service::TAXONOMY_NAME
			);
		}

		// Trigger the action for the next batch of posts.
		do_action( 'wp_ajax_set_article_term' );
	}
}
