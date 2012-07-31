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
 * Translation Model
 *
 */
class Translation extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';
	
	/**
	 * hasMany associations
	 *
	 * @var array
	 */
		public $hasMany = array(	'TranslationClip' => array(	'className' => 'TranslationClip',
																													'dependent' => true
																											)
															);
		
		/**
		 * Checks if the translation is ready for rendering.  It checks the returned ready_for_processing var from the VTS clips index, and
		 * the total number of clips uploaded.
		 *
		 * @param string $vtsReadyForProcessing the VTS response ready_for_processing from ClipsController Index
		 * @param string $video the key for the film from TranslationClip->videoClipsNeeded variable
		 * @return boolean
		 * @access public
		 * @author Johnathan Pulos
		 */													
		public function isReadyForRender($vtsReadyForProcessing, $video) {
			$totalClipsNeeded = count($this->TranslationClip->videoClipsNeeded[$video]);
			if(strtoupper($vtsReadyForProcessing) != 'YES') {
				return false;
			}
			$totalClipsResult = $this->TranslationClip->find('count', array('conditions' => array('translation_id' => $this->id)));
			if($totalClipsResult == $totalClipsNeeded) {
				return true;
			}else {
				return false;
			}
		}
}
