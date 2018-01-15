<?php
/**
 * Tests: Taxonomy Manager Term Service.
 *
 * @since      1.0.0
 * @package    Wordlift_Taxonomy_Manager
 * @subpackage Wordlift_Taxonomy_Manager/tests
 */

/**
 * Define the {@link Wordlift_Taxonomy_Manager_Term_Service} class.
 *
 * @since      1.0.0
 * @package    Wordlift_Taxonomy_Manager
 * @subpackage Wordlift_Taxonomy_Manager/tests
 */
class Wordlift_Taxonomy_Manager_Term_Service_Test extends WP_UnitTestCase {

	use Wordlift_Taxonomy_Manager_Term_Service_Trait;

	/**
	 * @inheritdoc
	 */
	function setUp() {
		parent::setUp();

		$this->set_up_unit_test();
	}
	/**
	 * Test that `article` terms is set to all posts/page
	 * that didn't have any 'wl_entity_type' term.
	 *
	 * @since 1.0.0
	 */
	public function test_set_article_term() {

		// Create posts and pages for the test.
		$post_id_1 = $this->factory->post->create( array(
			'post_type' => 'post',
		) );

		$post_id_2 = $this->factory->post->create( array(
			'post_type' => 'post',
		) );

		$page_id_1 = $this->factory->post->create( array(
			'post_type' => 'page',
		) );

		// Assing `event` type to post 2,
		$test = wp_set_object_terms( $post_id_2, 'event', Wordlift_Entity_Types_Taxonomy_Service::TAXONOMY_NAME );

		// Trigger the term service.
		do_action( 'set_article_term' );

		// Get terms
		$post_1_terms = wp_get_post_terms(
			$post_id_1, // The object ID.
			Wordlift_Entity_Types_Taxonomy_Service::TAXONOMY_NAME, // The taxonomy.
			array(
				'fields' => 'slugs', // We need only slugs for the conditions below.
			)
		);

		$post_2_terms = wp_get_post_terms(
			$post_id_2, // The object ID.
			Wordlift_Entity_Types_Taxonomy_Service::TAXONOMY_NAME, // The taxonomy.
			array(
				'fields' => 'slugs', // We need only slugs for the conditions below.
			)
		);

		$page_1_terms = wp_get_post_terms(
			$page_id_1, // The object ID.
			Wordlift_Entity_Types_Taxonomy_Service::TAXONOMY_NAME, // The taxonomy.
			array(
				'fields' => 'slugs', // We need only slugs for the conditions below.
			)
		);

		// Test that post1 and page1 have `article` term assigned.
		$this->assertContains( 'article', $post_1_terms );
		$this->assertContains( 'article', $page_1_terms );

		// Test that post2 is not affected by the manager.
		$this->assertContains( 'event', $post_2_terms );
		$this->assertNotContains( 'article', $post_2_terms );
	}

}
