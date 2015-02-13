<?php 

require_once 'factory.php';

class CH_EDD_Connect_UnitTestCase extends WP_UnitTestCase {
	
	public function setUp() {
		parent::setUp();
		$this->factory = new Charitable_UnitTest_Factory;		
	}
}