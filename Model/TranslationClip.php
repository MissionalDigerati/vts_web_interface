<?php
/**
 * This file is part of Video Translator Service Website Example.
 * 
 * Video Translator Service Website Example is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Video Translator Service Website Example is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see 
 * <http://www.gnu.org/licenses/>.
 *
 * @author Johnathan Pulos <johnathan@missionaldigerati.org>
 * @copyright Copyright 2012 Missional Digerati
 * 
 */
App::uses('AppModel', 'Model');
/**
 * TranslationClip Model
 *
 * @property Translation $Translation
 */
class TranslationClip extends AppModel {

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(	'Translation' => array(	'className' => 'Translation',
																											'foreignKey' => 'translation_id'
																										)
														);
	/**
	 * The video clips that need to be translated, keyed to the video story
	 *
	 * @var array
	 */
	public $videoClipsNeeded = array(	'compassionateFather'	=>	array(	'/files/master_files/example/the_compassionate_father_1.mp4',
																																			'/files/master_files/example/the_compassionate_father_2.mp4',
																																			'/files/master_files/example/the_compassionate_father_3.mp4',
																																			'/files/master_files/example/the_compassionate_father_4.mp4',
																																			'/files/master_files/example/the_compassionate_father_5.mp4',
																																			'/files/master_files/example/the_compassionate_father_6.mp4',
																																			'/files/master_files/example/the_compassionate_father_7.mp4',
																																			'/files/master_files/example/the_compassionate_father_8.mp4',
																																			'/files/master_files/example/the_compassionate_father_9.mp4',
																																			'/files/master_files/example/the_compassionate_father_10.mp4',
																																			'/files/master_files/example/the_compassionate_father_11.mp4',
																																			'/files/master_files/example/the_compassionate_father_12.mp4',
																																			'/files/master_files/example/the_compassionate_father_13.mp4'
																																		)
																	);

	/**
	 * Checks the mime-type of the file & verifies it is a mp3 audio file
	 * This is very rudimentary, but works for a sample app.
	 *
	 * @param string $file the location of the file to check
	 * @return boolean
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function isMp3($file) {
		$mimeTypes = array('audio/mpeg3', 'audio/x-mpeg-3');
		$finfo = new finfo;
		$fileInfo = $finfo->file($file, FILEINFO_MIME);
		return ((preg_match("/audio/", $fileInfo)) && (preg_match("/mpeg/", $fileInfo))) ? true : false;
	}
	
	/**
	 * Find all clips provided by the user, and return the order numbers of each clip
	 *
	 * @param integer $translationId Translation.id
	 * @return array
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function findClipsOrderNumberAndId($translationId) {
		$orderNumbers = array();
		$clips = $this->find('all', array('conditions'=> 'TranslationClip.translation_id = ' . $translationId));
		foreach ($clips as $clip) {
			$orderNumbers[$clip['TranslationClip']['clip_order']] = $clip['TranslationClip']['id'];
		}
		return $orderNumbers;
	}
}
