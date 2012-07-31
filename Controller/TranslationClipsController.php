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
	 */
		public $uses = array('TranslationClip', 'VideoTranslatorService.Clip', 'VideoTranslatorService.MasterRecording');

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
				$this->TranslationClip->Translation->id = $translationId;
				if (!$this->TranslationClip->Translation->exists()) {
					throw new NotFoundException(__('Invalid Translation'));
				}
				$translation = $this->TranslationClip->Translation->read(null, $translationId);
				$this->set('translation', $translation);
				$this->set('currentClipOrderNumberAndIds', $this->TranslationClip->findClipsOrderNumberAndId($translationId));
				$show = (isset($this->request->query['show'])) ? explode(',', $this->request->query['show']) : array();
				$this->set('show', $show);
				if(!empty($translation['Translation']['vts_master_recording_id'])) {
					/**
					 * We need to see if the master recording is done
					 *
					 * @author Johnathan Pulos
					 */
					$renderState = 'PROCESSING';
					$conditions = array('conditions' => array(	'id' => $translation['Translation']['vts_master_recording_id'],
																											'translation_request_token'	=>	$translation['Translation']['token']
														));
					if($masterRecording = $this->MasterRecording->find('first', $conditions)) {
						if($masterRecording['MasterRecording']['status'] == 'COMPLETE') {
							$renderState = 'COMPLETE';
						}
					}
				} else{
					$allClips = $this->Clip->find('all', array('conditions'	=>	array('translation_request_token' => $translation['Translation']['token'])));
					$renderState = ($this->TranslationClip->Translation->isReadyForRender($allClips['Translation']['ready_for_processing'], 'compassionateFather')) ? 'READY' : 'PENDING';
				}
				$this->set('masterRecording', $masterRecording);
				$this->set('renderState', $renderState);
			}
			
		/**
		 * add method
		 *
		 * @return void
		 */
			public function add($translationId = null) {
				$this->TranslationClip->Translation->id = $translationId;
				if (!$this->TranslationClip->Translation->exists()) {
					throw new NotFoundException(__('Invalid Translation'));
				}
				$translation = $this->TranslationClip->Translation->read(null, $translationId);
				if(!empty($this->request->data)) {
					if($this->request->data['TranslationClip']['audio_file']['tmp_name'] != '') {
						if($this->TranslationClip->isMp3($this->request->data['TranslationClip']['audio_file']['tmp_name'])) {
							$this->Uploader = new Uploader(array('tempDir' => TMP));
							if ($data = $this->Uploader->upload('audio_file')) {
								/**
								 * Set the path to the uploaded file to audio_file
								 *
								 * @author Johnathan Pulos
								 */
								$this->request->data['TranslationClip']['audio_file'] = WWW_ROOT.$data['path'];
								if ($this->Clip->save($this->request->data['TranslationClip'], false)) {
									$clipData = array('TranslationClip' => array(	'vts_clip_id' => $this->Clip->id, 
																																'clip_order' => $this->request->data['TranslationClip']['clip_order'],
																																'translation_id'	=> $translationId));
									if($this->TranslationClip->save($clipData, false)) {
										$message = __('Clip # %s has been uploaded.');
										$this->Session->setFlash(sprintf($message, $this->request->data['TranslationClip']['clip_order']));
										$this->redirect("/translations/" . $this->TranslationClip->Translation->id . "/clips");
									}else {
										throw new CakeException(__('Unable to upload the clip.'));
									}
								}else {
									throw new CakeException(__('Unable to upload the clip.'));
								}
							}
						}else {
							$this->Session->setFlash(__('Only mp3 files are accepted.'));
							$this->redirect("/translations/" . $translationId . "/clips");
						}
					} else{
						$this->Session->setFlash(__('You must supply a valid mp3.'));
						$this->redirect("/translations/" . $translationId . "/clips");
					}
				}
			}
			
		/**
		 * edit a translation clip
		 *
		 * @return void
		 */
			public function edit($translationId = null) {
				$this->TranslationClip->Translation->id = $translationId;
				if (!$this->TranslationClip->Translation->exists()) {
					throw new NotFoundException(__('Invalid Translation'));
				}
				$translation = $this->TranslationClip->Translation->read(null, $translationId);
				if(!empty($this->request->data)) {
					$translationClip = $this->TranslationClip->read(null, $this->request->data['TranslationClip']['id']);
					if($this->TranslationClip->isMp3($this->request->data['TranslationClip']['audio_file']['tmp_name'])) {
						$this->Uploader = new Uploader(array('tempDir' => TMP));
						if ($data = $this->Uploader->upload('audio_file')) {
							/**
							 * Set the path to the uploaded file to audio_file
							 *
							 * @author Johnathan Pulos
							 */
							$this->request->data['TranslationClip']['audio_file'] = WWW_ROOT.$data['path'];
							/**
							 * Since we are updating, we need to pas an id to the vts api
							 *
							 * @author Johnathan Pulos
							 */
							$this->request->data['TranslationClip']['vts_clip_id'] = $translationClip['TranslationClip']['vts_clip_id'];
							if ($this->Clip->save($this->request->data['TranslationClip'], false)) {
								$clipData = array('TranslationClip' => array(	'vts_clip_id' => $this->Clip->id, 
																															'clip_order' => $this->request->data['TranslationClip']['clip_order'],
																															'translation_id'	=> $translationId));
								if($this->TranslationClip->save($clipData, false)) {
									$message = __('Clip # %s has been updated.');
									$this->Session->setFlash(sprintf($message, $this->request->data['TranslationClip']['clip_order']));
									$this->redirect("/translations/" . $this->TranslationClip->Translation->id . "/clips");
								}else {
									throw new CakeException(__('Unable to update the clip.'));
								}
							}else {
								throw new CakeException(__('Unable to update the clip.'));
							}
						}
					}else {
						$this->Session->setFlash(__('Only mp3 files are accepted.'));
						$this->redirect("/translations/" . $this->Translation->id . "/clips");
					}
				}
			}

}
