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
 * Import the VTS Clip Model
 *
 * @author Johnathan Pulos
 */
App::import('Model', 'VideoTranslatorService.Clip');
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
	public $belongsTo = array(	'Translation' => array(	'className' => 'Translation','foreignKey' => 'translation_id'));
	/**
	 * An array of errors when saving the translation clip
	 *
	 * @var array
	 */
	public $currentSaveErrors = array();
	
	/**
	 * Find all clips provided by the user, and return an array with a key of the order, and the translation_clip details in value.
	 *
	 * @return array
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function findClipsByOrderNumber() {
		$orderNumbers = array();
		$clips = $this->find('all', array('conditions'=> 'TranslationClip.translation_id = ' . $this->Translation->id));
		foreach ($clips as $clip) {
			$orderNumbers[$clip['TranslationClip']['clip_order']] = $clip;
		}
		return $orderNumbers;
	}
	
	/**
	 * Saves the clip to the VTS API, and to the Translation Clip model
	 *
	 * @param array $data form data passed from $this->request->data
	 * @param string $localFilePath the path to the local file
	 * @return boolean
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function saveClipIncludingVts($data, $localFilePath) {
		$Clip = new Clip();
		$data['TranslationClip']['audio_file'] = WWW_ROOT.$localFilePath;
		$data['TranslationClip']['order_by'] = $data['TranslationClip']['clip_order'];
		if($Clip->save($data['TranslationClip'])) {
			$clipData = array('TranslationClip' => array(	'vts_clip_id' 		=> $Clip->id, 
															'clip_order' 		=> $data['TranslationClip']['clip_order'],
															'translation_id'	=> $this->Translation->id,
															'vts_status'		=>	'PENDING',
															'vts_file_path'		=>	'',
															'local_file_path'	=>	$localFilePath,
															'mime_type'			=>	$data['TranslationClip']['mime_type']
														)
							);
			return $this->save($clipData);
		}else {
			$this->currentSaveErrors = $Clip->invalidFields();
			return false;
		}
	}
	
	/**
	 * Update the status on all clips belonging to this translation.  It returns the array of data supplied by the Clip Model of VTS
	 *
	 * @param string $token the Translation.token 
	 * @return array
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function updateClipStatuses($token) {
		$Clip = new Clip();
		$clips = $Clip->find('all', array('conditions' => array('translation_request_token' =>	$token)));
		if((!empty($clips)) && (isset($clips['Clips']))) {
			foreach ($clips['Clips'] as $clip) {
				$translationClip = $this->find('first', array('conditions'	=> array('vts_clip_id'	=> $clip['id'])));
				$this->id = $translationClip['TranslationClip']['id'];
				$this->set(array('vts_status'	=>	$clip['status'], 'vts_file_path'	=>	substr($clip['completed_file_location'], 1)));
				$this->save();
			}
		}
		return $clips;
	}
	
	/**
	 * CakePHP's beforeDelete Callback.  It will remove the file from the local server
	 *
	 * @param boolean $cascade tells us if the delete is set to cascade to related models
	 * @return boolean
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function beforeDelete($cascade = true) {
		$clip = $this->read('local_file_path', $this->id);
		if(file_exists(WWW_ROOT.$clip['TranslationClip']['local_file_path'])) {
			unlink(WWW_ROOT.$clip['TranslationClip']['local_file_path']);
		}
		return true;
	}
	
	/**
	 * Validates the mime type is acceptable
	 *
	 * @param string $mimeType the mime type of the file
	 * @return boolean
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function validMimeType($mimeType) {
		return in_array($mimeType, array('audio/mpeg', 'audio/mp3', 'audio/x-wav', 'audio/wav'));
	}

	/**
	 * Look for the next clip that needs to be added.  Iterates over already added clips and finds the next number needed.  NOTE: This method is not aware of how many 
	 * clips belong to a translation, so you will need to check if the number returned is greater then the max number of clips.
	 *
	 * @param integer $translationId Translation.id
	 * @param integer $currentClipNumber the current Clip number
	 * @return integer
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function nextClipToAdd($translationId, $currentClipNumber) {
		$nextClipNumber = $currentClipNumber+1; 
		$addedClipSearch = $this->find('list', array(
				        																	'fields'			=> array('TranslationClip.id', 'TranslationClip.clip_order'),
				        																	'order'				=> array('TranslationClip.clip_order' => 'ASC'),
																									'conditions'	=> array('TranslationClip.translation_id' => $translationId),
				        																	'recursive'		=> 0
		));
		$addedClips = array_values($addedClipSearch);
		while(in_array($nextClipNumber, $addedClips)) {
			$nextClipNumber = $nextClipNumber+1;
		}
		return $nextClipNumber;
	}
}
