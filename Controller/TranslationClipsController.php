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
/**
 * Import the AppController
 *
 * @author Johnathan Pulos
 */
App::uses('AppController', 'Controller');
/**
 * Import the Uploader class in the Vendor Directory
 *
 * @author Johnathan Pulos
 */
App::import('Vendor', 'Uploader.Uploader');
/**
 * A sample controller that interacts with the plugin
 *
 * @package default
 * @author Johnathan Pulos
 */
class TranslationClipsController extends AppController {
	/**
	 * Controller name
	 *
	 * @var string
	 * @access public
	 */
		public $name = 'TranslationClips';

	/**
	 * This controller uses the plugins models
	 *
	 * @var array	
	 * @access public
	 */
		public $uses = array('TranslationClip', 'VideoTranslatorService.Clip', 'VideoTranslatorService.MasterRecording');
		
		/**
		 * Call any necessary Components
		 *
		 * @var array
		 * @access public
		 */
		public $components = array('SpycYAML');
		
		/**
		 * The current translation
		 *
		 * @var array
		 * @access public
		 */
		public $currentTranslation = array();

		/**
		 * Declare a cakePHP callback to set the video clips needed
		 *
		 * @return void
		 * @access public
		 * @author Johnathan Pulos
		 */
		public function beforeFilter() {	
			parent::beforeFilter();
			/**
			 * Setup the current translation
			 *
			 * @author Johnathan Pulos
			 */
			$this->TranslationClip->Translation->id = $this->request->translation_id;
			if (!$this->TranslationClip->Translation->exists()) {
				throw new NotFoundException(__('Invalid Translation'));
			}
			$this->currentTranslation = $this->TranslationClip->Translation->read(null);
			$this->set('translation', $this->currentTranslation);
			/**
			 * Block users who do not have access to it
			 *
			 * @author Johnathan Pulos
			 */
			if($this->currentTranslation['Translation']['user_id'] != $this->Auth->user('id')) {
				throw new NotFoundException(__('You do not have permission.'));
			}
			/**
			 * Get an array of all the clips needed
			 *
			 * @author Johnathan Pulos
			 */
			$this->set('videoClipsNeeded', $this->TranslationClip->videoClipsNeeded);
		}
		
		/**
		 * index method
		 *
		 * @return void
		 */
			public function index($translationId = null) {
				$masterRecording = array();
				$renderState = 'PENDING';
				if(!empty($this->currentTranslation['Translation']['vts_master_recording_id'])) {
					/**
					 * We need to see if the master recording is done
					 *
					 * @author Johnathan Pulos
					 */
					$renderState = 'PROCESSING';
					$conditions = array('conditions' => array(	'id' 												=> $this->currentTranslation['Translation']['vts_master_recording_id'],
																											'translation_request_token'	=>	$this->currentTranslation['Translation']['token']
														));
					if($masterRecording = $this->MasterRecording->find('first', $conditions)) {
						if($masterRecording['MasterRecording']['status'] == 'COMPLETE') {
							$renderState = 'COMPLETE';
						}
					}
				} else{
					$allClips = $this->Clip->find('all', array('conditions'	=>	array('translation_request_token' => $this->currentTranslation['Translation']['token'])));
					$renderState = ($this->TranslationClip->Translation->isReadyForRender($allClips['Translation']['ready_for_processing'], 'compassionateFather')) ? 'READY' : 'PENDING';
				}
				$this->set('currentClipOrderNumberAndIds', $this->TranslationClip->findClipsOrderNumberAndId());
				$show = (isset($this->request->query['show'])) ? explode(',', $this->request->query['show']) : array();
				$this->set('show', $show);
				$this->set('masterRecording', $masterRecording);
				$this->set('renderState', $renderState);
			}
			
		/**
		 * add method
		 *
		 * @return void
		 */
			public function add($translationId = null, $clipNumber = null) {
				if (!$clipNumber) {
					throw new NotFoundException(__('Invalid Clip Number'));
				}
				$localFilePath = "";
				if(!empty($this->request->data)) {
					$this->Uploader = new Uploader(array('tempDir' => TMP));
					if((isset($this->request->data['TranslationClip']['audio_file'])) && ($this->request->data['TranslationClip']['audio_file']['tmp_name'] != '')) {
						/**
						 * A file needs to be uploaded
						 *
						 * @author Johnathan Pulos
						 */
						if ($data = $this->Uploader->upload('audio_file')) {
							/**
							 * Set the path to the uploaded file to audio_file
							 *
							 * @author Johnathan Pulos
							 */
							$localFilePath = $data['path'];
						}else {
							throw new CakeException(__('Unable to upload the clip.'));
						}
					}else if((isset($this->request->data['TranslationClip']['audio_file_path'])) && ($this->request->data['TranslationClip']['audio_file_path'])) {
						/**
						 * They recorded via the browser.  We will send the file like this
						 *
						 * @author Johnathan Pulos
						 */
						$localFilePath = $this->request->data['TranslationClip']['audio_file_path'];
					}else {
						$this->Session->setFlash(__('You must supply a valid mp3.'), '_flash_msg', array('msgType' => 'error'));
						$this->redirect("/translations/" . $translationId . "/clip/".$clipNumber."/edit");
					}
					$this->request->data['TranslationClip']['mime_type'] = $this->Uploader->mimeType(WWW_ROOT.$localFilePath);
					if($this->TranslationClip->saveClipIncludingVts($this->request->data, $localFilePath)) {
						$message = __('Clip # %s has been uploaded.');
						$this->Session->setFlash(sprintf($message, $this->request->data['TranslationClip']['clip_order']), '_flash_msg', array('msgType' => 'info'));
						$this->redirect("/translations/" . $translationId . "/clip/".$clipNumber."/edit");
					}else {
						throw new CakeException(__('Unable to upload the clip.'));
					}
				}
				$videoClips = $this->SpycYAML->toArray(ROOT . DS . APP_DIR . DS . 'Config' . DS . 'clip_settings.yml');
				$this->set('currentClip', $videoClips['clip_' . $clipNumber]);
				$this->set('clipCount', array('current'	=>	$clipNumber, 'total'	=>	count($videoClips)));
			}
			
		/**
		 * edit a translation clip
		 *
		 * @return void
		 */
			public function edit($translationId = null) {
				if(!empty($this->request->data)) {
					$translationClip = $this->TranslationClip->read(null, $this->request->data['TranslationClip']['id']);
					if($this->TranslationClip->isMp3($this->request->data['TranslationClip']['audio_file']['tmp_name'])) {
						$this->Uploader = new Uploader(array('tempDir' => TMP));
						if ($data = $this->Uploader->upload('audio_file')) {
							/**
							 * Since we are updating, we need to pas an id to the vts api
							 *
							 * @author Johnathan Pulos
							 */
							$this->request->data['TranslationClip']['vts_clip_id'] = $translationClip['TranslationClip']['vts_clip_id'];
							$this->request->data['TranslationClip']['mime_type'] = $this->Uploader->mimeType(WWW_ROOT.$data['path']);
							if($this->TranslationClip->saveClipIncludingVts($this->request->data, $data['path'])) {
								$message = __('Clip # %s has been uploaded.');
								$this->Session->setFlash(sprintf($message, $this->request->data['TranslationClip']['clip_order']), '_flash_msg', array('msgType' => 'info'));
								$this->redirect("/translations/" . $translationId . "/clips");
							}else {
								throw new CakeException(__('Unable to upload the clip.'));
							}
						}else {
							$this->Session->setFlash(__('Only mp3 files are accepted.'), '_flash_msg', array('msgType' => 'error'));
							$this->redirect("/translations/" . $translationId . "/clips");
						}
					}else {
						$this->Session->setFlash(__('Only mp3 files are accepted.'), '_flash_msg', array('msgType' => 'error'));
						$this->redirect("/translations/" . $translationId . "/clips");
					}
				}
			}

}
