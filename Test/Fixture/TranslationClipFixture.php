<?php
/**
 * TranslationClipFixture
 *
 */
class TranslationClipFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'translation_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'vts_clip_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'clip_order' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'translation_id' => 1,
			'vts_clip_id' => 1,
			'clip_order' => 1,
			'created' => '2012-07-27 16:32:02',
			'modified' => '2012-07-27 16:32:02'
		),
	);
}
