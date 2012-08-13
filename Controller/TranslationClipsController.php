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
			$this->TranslationClip->Translation->recursive = -1;
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
			 * Verify the clip number is set
			 *
			 * @author Johnathan Pulos
			 */
			if($this->action != 'index') {
				if (!$this->request->clip_number) {
					throw new NotFoundException(__('Invalid Clip Number'));
				}
			}
			if($this->action == 'edit') {
				/**
				 * Verify the TranslationClip exists
				 *
				 * @author Johnathan Pulos
				 */
				$this->TranslationClip->id = $this->request->id;
				if (!$this->TranslationClip->exists()) {
					throw new NotFoundException(__('Invalid Translation Clip'));
				}
			}
			$this->Uploader = new Uploader(array('tempDir' => TMP));
		}
		
		/**
		 * index method
		 *
		 * @return void
		 * @access public
		 * @author Johnathan Pulos
		 */
			public function index($translationId = null) {
				$videoClipData = $this->SpycYAML->toArray(ROOT . DS . APP_DIR . DS . 'Config' . DS . 'clip_settings.yml');
				$this->TranslationClip->updateClipStatuses($this->currentTranslation['Translation']['token']);
				$this->set('videoClipData', $videoClipData);
				$this->set('clipOrderNumberAndIdArray', $this->TranslationClip->findClipsByOrderNumber());
			}
			
		/**
		 * add method
		 *
		 * @return void
		 * @access public
		 * @author Johnathan Pulos
		 */
			public function add($translationId = null, $clipNumber = null) {
				if(!empty($this->request->data)) {
					$localFilePath = $this->handleSubmittedFile();
					$this->request->data['TranslationClip']['mime_type'] = $this->Uploader->mimeType(WWW_ROOT.$localFilePath);
					if($this->TranslationClip->saveClipIncludingVts($this->request->data, $localFilePath)) {
						$message = __('Clip # %s has been uploaded.');
						$this->Session->setFlash(sprintf($message, $clipNumber), '_flash_msg', array('msgType' => 'info'));
						$this->redirect("/translations/" . $translationId . "/clips");
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
		 * @access public
		 * @author Johnathan Pulos
		 */
			public function edit($translationId = null, $clipNumber = null, $id = null) {
				if(!empty($this->request->data)) {
					$localFilePath = $this->handleSubmittedFile();
					$this->request->data['TranslationClip']['mime_type'] = $this->Uploader->mimeType(WWW_ROOT.$localFilePath);
					if($this->TranslationClip->saveClipIncludingVts($this->request->data, $localFilePath)) {
						$message = __('Clip # %s has been uploaded.');
						$this->Session->setFlash(sprintf($message, $clipNumber), '_flash_msg', array('msgType' => 'info'));
						$this->redirect("/translations/" . $translationId . "/clips");
					}else {
						throw new CakeException(__('Unable to upload the clip.'));
					}
				}
				$translationClip = $this->TranslationClip->read(null);
				if(($translationClip['TranslationClip']['vts_file_path'] == '') || ($translationClip['TranslationClip']['vts_file_path'] == null)) {
					/**
					 * lets get info about the clip, so we can update the file path
					 *
					 * @author Johnathan Pulos
					 */
					$this->Clip->id = $translationClip['TranslationClip']['vts_clip_id'];
					$clip = $this->Clip->find('first', array('conditions'	=>	array('translation_request_token'	=>	$this->currentTranslation['Translation']['token'])));
					if($clip['Clip']['status'] == 'COMPLETE') {
						$this->TranslationClip->set(array('vts_status'	=>	$clip['Clip']['status'], 'vts_file_path'	=>	substr($clip['Clip']['completed_file_location'], 1)));
						$this->TranslationClip->save();
						$translationClip = $this->TranslationClip->read(null);
					}
				}
				$videoClips = $this->SpycYAML->toArray(ROOT . DS . APP_DIR . DS . 'Config' . DS . 'clip_settings.yml');
				$this->set('currentClip', $videoClips['clip_' . $clipNumber]);
				$this->set('clipCount', array('current'	=>	$clipNumber, 'total'	=>	count($videoClips)));
				$this->set('translationClip', $translationClip);
			}

			/**
			 * Checks the submission type, and handles the uploading of the file.  If it was recorded,  it does nothing.  It
			 * returns the relative path to the audio file relative to webroot.
			 *
			 * @return string
			 * @access private
			 * @author Johnathan Pulos
			 */
			private function handleSubmittedFile() {
				switch (strtolower($this->request->data['TranslationClip']['submission_type'])) {
					case 'upload':
						/**
						 * A file needs to be uploaded
						 *
						 * @author Johnathan Pulos
						 */
						if ($data = $this->Uploader->upload('audio_file')) {
							/**
							 * Remove the leading directory seperator
							 *
							 * @author Johnathan Pulos
							 */
							return substr($data['path'], 1);
						}else {
							throw new CakeException(__('Unable to upload the clip.'));
						}
					break;
					case 'recorded':
						/**
						 * They recorded via the browser.  We will send the file like this
						 *
						 * @author Johnathan Pulos
						 */
						return $this->request->data['TranslationClip']['audio_file_path'];
					break;
					default:
						throw new CakeException(__('Invalid submission_type.'));
					break;
				}
			}
}
