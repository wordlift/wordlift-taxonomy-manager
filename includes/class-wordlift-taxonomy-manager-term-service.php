<?php

/**
 * Handles all `wl_entity_term` related actions
 *
 * @link       https://wordlift.io
 * @since      1.0.0
 *
 * @package    Wordlift_Taxonomy_Manager
 */

/**
 * Handles all `wl_entity_term` related actions
 *
 * @package    Wordlift_Taxonomy_Manager
 */
class Wordlift_Taxonomy_Manager_Term_Service {

	/**
	 * The post types that will be processed
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array $post_types Post types that will be processed.
	 */
	protected static $post_types = array(
		'post',
		'page',
	);

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_wl_set_article_term_bulk', array(
			$this,
			'set_article_term_bulk',
		) );
	}

	/**
	 * Get all posts/pages that didn't have `wl_entity_type` taxonomy term assigned.
	 *
	 * @since 1.0.0
	 *
	 * @return array List of posts.
	 */
	private function get_posts_without_entity_term() {
		/**
		 * Filter: 'wl_taxonomy_manager_post_types' - Allow third parties to
		 * hook and extend the manager post types.
		 *
		 * @since  1.0.0
		 *
		 * @param  array $types The post types that will be used by manager.
		 */
		$types = apply_filters( 'wl_taxonomy_manager_post_types', self::$post_types );

		// Will retrieve all posts that didn't have any `wl_entity_type` taxonomy term.
		$args = array(
			'cache_results' => false,
			'post_type'     => $types,
			'tax_query'     => array(
				array(
					'taxonomy' => Wordlift_Entity_Types_Taxonomy_Service::TAXONOMY_NAME,
					'operator' => 'NOT EXISTS',
				),
			),
		);

		// Return the result.
		return get_posts( $args );
	}

	public function set_article_term_bulk() {

		$this->validate();

		$this->do_set_article_term_bulk();

		wp_send_json_success();

	}

	private function validate() {

		if ( defined( Wordlift_Entity_Types_Taxonomy_Service::TAXONOMY_NAME ) ) {
			echo( '`Wordlift_Entity_Types_Taxonomy_Service::TAXONOMY_NAME` is not set.' );
			wp_die();
		}

		$term = get_term_by( 'slug', 'article', Wordlift_Entity_Types_Taxonomy_Service::TAXONOMY_NAME );

		if ( false === $term ) {
			echo( 'Term or taxonomy not found.' );
			wp_die();
		}

	}

	/**
	 * Set posts/pages default `article` entity term.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function do_set_article_term_bulk() {
		// Get posts.
		$posts = $this->get_posts_without_entity_term();

		// Bail if there are no posts.
		// It will end the async loop.
		if ( empty( $posts ) ) {
			return;
		}

		// Loop through all posts and set the default `article` term.
		foreach ( $posts as $p ) {
			// Set the default `article` term.
			$term_taxonomy_ids = wp_set_object_terms(
				$p->ID,
				'article',
				Wordlift_Entity_Types_Taxonomy_Service::TAXONOMY_NAME
			);

			// Add notice in the logs if the term was not set.
			if ( is_wp_error( $term_taxonomy_ids ) ) {
				error_log( 'There was an error when processing post ID: ' . $p->ID . ' and the article term couldn\'t be set.' );
				wp_die( 'An error occurred: ' . $term_taxonomy_ids->get_error_message() );
			}
		}

		// Trigger the action for the next batch of posts.
		do_action( 'wp_ajax_wl_set_article_term_bulk' );

	}

}
