<?php
App::uses('Boo', 'Model');

/**
 * Boo Test Case
 *
 */
class BooTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.boo');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Boo = ClassRegistry::init('Boo');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Boo);

		parent::tearDown();
	}

}
