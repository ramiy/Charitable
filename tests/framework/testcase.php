<?php 

require_once 'factory.php';

class Charitable_UnitTestCase extends WP_UnitTestCase {
	
	protected $charitable;

	public function setUp() {
		parent::setUp();
		$this->factory = new Charitable_UnitTest_Factory;
		$this->charitable = get_charitable();
	}
}