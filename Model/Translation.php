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
	 * The accessible attributes for mass assignment
	 *
	 * @var array
	 */
	public $attrAccessible	=	array('title', 'language', 'user_id', 'video_prefix');
	
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
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(	'User' => array(	'className' => 'User',
																								'foreignKey' => 'user_id'
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
	
	/**
	 * Can they edit the translation clips
	 *
	 * @param string $status Translation.status
	 * @return boolean
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function isEditable($status) {
		return (strtolower($status) == 'pending' || strtolower($status) == 'error') ? true : false;
	}
	
	/**
	 * Can they play the translation movie
	 *
	 * @param string $status Translation.status
	 * @return boolean
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function isPlayable($status) {
		return (strtolower($status) == 'rendered' || strtolower($status) == 'published') ? true : false;
	}
	
	/**
	 * cakePHP afterFind callback
	 *
	 * @param array $results the results found
	 * @return array
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function afterFind($results) {
		foreach ($results as $key => $val) {
			if(isset($val[$this->alias])) {
				$results[$key][$this->alias]['isEditable'] = $this->isEditable($val[$this->alias]['status']);
				$results[$key][$this->alias]['isPlayable'] = $this->isPlayable($val[$this->alias]['status']);
			}else if(isset($val['status'])) {
				$results['isEditable'] = $this->isEditable($val['status']);
				$results['isPlayable'] = $this->isPlayable($val['status']);
			}
		}
		return $results;
	}
	
	/**
	 * Download the final file from remote server, and publish the Translation.  Returns true if the file is downloaded successfully.
	 *
	 * @param string $remoteFile the current location of the final file
	 * @return boolean
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function downloadAndPublish($remoteFile) {
		$localFilePath = 'files' . DS . 'completed' . DS . basename($remoteFile);
		$this->downloadRemoteFile($remoteFile, WWW_ROOT . $localFilePath);
		if(file_exists($localFilePath)) {
			$this->set('master_recording_file', $localFilePath);
			$this->set('status', 'PUBLISHED');
			$this->save();
			return true;
		} else{
			return false;
		}
	}
}
