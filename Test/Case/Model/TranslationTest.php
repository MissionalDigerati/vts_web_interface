<?php
App::uses('Translation', 'Model');

/**
 * Translation Test Case
 *
 */
class TranslationTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.translation');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Translation = ClassRegistry::init('Translation');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Translation);

		parent::tearDown();
	}

}
