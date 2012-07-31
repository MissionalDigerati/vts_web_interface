<?php
App::uses('TranslationClip', 'Model');

/**
 * TranslationClip Test Case
 *
 */
class TranslationClipTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.translation_clip', 'app.translation');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->TranslationClip = ClassRegistry::init('TranslationClip');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TranslationClip);

		parent::tearDown();
	}

}
