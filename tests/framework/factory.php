<?php 
class Charitable_UnitTest_Factory extends WP_UnitTest_Factory {
	/**
	 * @var Charitable_UnitTest_Factory_For_Campaign
	 */
	public $campaign;

	/**
	 * @var Charitable_UnitTest_Factory_For_Donation
	 */
	public $discount;

	public function __construct() {
		parent::__construct();

		$this->campaign = new Charitable_UnitTest_Factory_For_Campaign( $this );
		$this->donation = new Charitable_UnitTest_Factory_For_Donation( $this );
	}
}

class Charitable_UnitTest_Factory_For_Campaign extends WP_UnitTest_Factory_For_Thing {
	public function __construct( $factory = null ) {
		parent::__construct( $factory );
		$this->default_generation_definitions = array(
			'post_status' => 'publish',
			'post_title' => new \WP_UnitTest_Generator_Sequence( 'Campaign title %s' ),
			'post_content' => new \WP_UnitTest_Generator_Sequence( 'Campaign content %s' ),
			'post_excerpt' => new \WP_UnitTest_Generator_Sequence( 'Campaign excerpt %s' ),
			'post_type' => 'campaign'
		);
	}

	public function create_object( $args ) {
		return wp_insert_post( $args );
	}

	public function update_object( $post_id, $fields ) {
		$fields['ID'] = $post_id;
		return wp_update_post( $fields );
	}

	public function get_object_by_id( $post_id ) {
		return get_post( $post_id );
	}
}

class Charitable_UnitTest_Factory_For_Donation extends WP_UnitTest_Factory_For_Thing {
	public function __construct( $factory = null ) {
		parent::__construct( $factory );
		$this->default_generation_definitions = array(
			'post_status' => 'publish',
			'post_title' => new \WP_UnitTest_Generator_Sequence( 'Donation title %s' ),
			'post_content' => new \WP_UnitTest_Generator_Sequence( 'Donation content %s' ),
			'post_excerpt' => new \WP_UnitTest_Generator_Sequence( 'Donation excerpt %s' ),
			'post_type' => 'donation'
		);
	}

	public function create_object( $args ) {
		return wp_insert_post( $args );
	}

	public function update_object( $post_id, $fields ) {
		$fields['ID'] = $post_id;
		return wp_update_post( $fields );
	}

	public function get_object_by_id( $post_id ) {
		return get_post( $post_id );
	}		
}