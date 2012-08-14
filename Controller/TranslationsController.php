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
 * A sample controller that interacts with the plugin
 *
 * @package default
 * @author Johnathan Pulos
 */
class TranslationsController extends AppController {
	
	/**
	 * Controller name
	 *
	 * @var string
	 * @access public
	 */
		public $name = 'Translations';
		
		/**
		 * Define needed CakePHP Helpers
		 *
		 * @author Johnathan Pulos
		 * @access public
		 */
		public $helpers = array('Time');

		/**
		 * Define pagination settings
		 *
		 * @var array
		 */
		public $paginate = array('limit' => 25);

	/**
	 * This controller uses the plugins models
	 *
	 * @var array
	 */
		public $uses = array('Translation', 'VideoTranslatorService.TranslationRequest', 'VideoTranslatorService.MasterRecording');
		
		/**
		 * Declare CakePHP's callback
		 *
		 * @return void
		 * @access public
		 * @author Johnathan Pulos
		 */
		public function beforeFilter() {
			parent::beforeFilter();
			$this->Auth->allow('view', 'download');
		}
		
	/**
	 * index method
	 *
	 * @return void
	 */
		public function index() {
			$id = $this->Auth->user('id');
			$this->set('translations', $this->Translation->find('all', array('conditions'=> array('Translation.user_id'	=>	$id))));
		}
		
		/**
		 * View the Translation
		 *
		 * @param integer $id Translation.id
		 * @return void
		 * @access public
		 * @author Johnathan Pulos
		 */
		public function view($id = null) {
			$this->Translation->id = $id;
			if (!$this->Translation->exists()) {
				throw new NotFoundException(__('Invalid translation'));
			}
			$translation = $this->Translation->read(null, $id);
			$this->set('translation', $translation);
			$this->set('videoUrl', $translation['Translation']['master_recording_file']);
		}
		
		/**
		 * Download the remote file
		 *
		 * @param integer $id Translation.id
		 * @return void
		 * @access public
		 * @author Johnathan Pulos
		 */
		public function download($id = null) {
			$this->Translation->id = $id;
			if (!$this->Translation->exists()) {
				throw new NotFoundException(__('Invalid translation'));
			}
			$translation = $this->Translation->read(null, $id);
			$this->set('videoUrl', $translation['Translation']['master_recording_file']);
			$this->layout = false;
		}
		
		/**
		 * Create a new translation
		 *
		 * @return void
		 * @access public
		 * @author Johnathan Pulos
		 */
		public function add() {
			$this->TranslationRequest->create();
			if(!empty($this->request->data)) {
				$this->request->data['Translation']['user_id'] = $this->Auth->user('id');
				if ($this->TranslationRequest->save(array(), false)) {
					$translationRequest = $this->TranslationRequest->read(null, $this->TranslationRequest->id);
					if ($this->Translation->save($this->request->data, true, $this->Translation->attrAccessible)) {
						$this->Translation->saveField('vts_translation_request_id', $translationRequest['TranslationRequest']['id']);
						$this->Translation->saveField('token', $translationRequest['TranslationRequest']['token']);
						$this->Translation->saveField('expires_at', $translationRequest['TranslationRequest']['expires_at']);
						$this->Session->setFlash(__('The translation has been created.  Now upload your audio files for each clip.'), '_flash_msg', array('msgType' => 'info'));
						$this->redirect("/translations/" . $this->Translation->id . "/clips");
					} else{
						$this->Session->setFlash(__('Unable to save the translation.'), '_flash_msg', array('msgType' => 'error'));
					}
				}else {
					$this->Session->setFlash(__('Unable to save the translation.'), '_flash_msg', array('msgType' => 'error'));
				}
			}
		}
		
		/**
		 * Edit a translation
		 *
		 * @param integer $id Translation.id
		 * @return void
		 * @access public
		 * @author Johnathan Pulos
		 */
		public function edit($id = null) {
			$this->Translation->id = $id;
			if (!$this->Translation->exists()) {
				throw new NotFoundException(__('Invalid translation'));
			}
			$translation = $this->Translation->read(null, $id);
			if($translation['Translation']['user_id'] != $this->Auth->user('id')) {
				throw new NotFoundException(__('You do not have permission.'));
			}
			if ($this->request->is('post') || $this->request->is('put')) {
				if ($this->Translation->save($this->request->data, true, $this->Translation->attrAccessible)) {
					$this->Session->setFlash(__('The translation has been updated.'), '_flash_msg', array('msgType' => 'info'));
					$this->redirect(array('action'	=>	'index'));
				} else{
					$this->Session->setFlash(__('Unable to update the translation.'), '_flash_msg', array('msgType' => 'error'));
				}
			}else {
				$this->request->data = $translation;
			}
		}
		
		/**
		 * Delete a translation
		 *
		 * @param integer $id	Translation.id 
		 * @return void
		 * @access public
		 * @author Johnathan Pulos
		 */
		public function delete($id = null) {
			if (!$this->request->is('post')) {
				throw new MethodNotAllowedException();
			}
			$this->Translation->id = $id;
			if (!$this->Translation->exists()) {
				throw new NotFoundException(__('Invalid translation'));
			}
			$translation = $this->Translation->read(null, $id);
			if($translation['Translation']['user_id'] != $this->Auth->user('id')) {
				throw new NotFoundException(__('You do not have permission.'));
			}
			$this->TranslationRequest->id = $translation['Translation']['vts_translation_request_id'];
			if ($this->TranslationRequest->delete()) {
				if ($this->Translation->delete()) {
					$this->Session->setFlash(__('The translation has been deleted.'), '_flash_msg', array('msgType' => 'info'));
					$this->redirect(array('action' => 'index'));
				}
			}
			$this->Session->setFlash(__('Unable to delete the translation.'), '_flash_msg', array('msgType' => 'error'));
			$this->redirect(array('action' => 'index'));
		}
		
		/**
		 * Begin the video rendering process
		 *
		 * @param integer $id Translation.id
		 * @return void
		 * @access public
		 * @author Johnathan Pulos
		 */
		public function render_video($id = null) {
			$this->Translation->id = $id;
			if (!$this->Translation->exists()) {
				throw new NotFoundException(__('Invalid Translation'));
			}
			$translation = $this->Translation->read(null, $this->Translation->id);
			if($translation['Translation']['user_id'] != $this->Auth->user('id')) {
				throw new NotFoundException(__('You do not have permission.'));
			}
			$finalFilename = $this->Translation->id . "_translated_video";
			$data = array(	'translation_request_token' => $translation['Translation']['token'],
											'title' 										=> $translation['Translation']['title'],
											'language' 									=> $translation['Translation']['language'],
											'final_filename'						=> $finalFilename
									);
			if($this->MasterRecording->save($data, false)) {
				$this->Translation->set('vts_master_recording_id', $this->MasterRecording->id);
				$this->Translation->set('master_recording_file', VTS_URL . 'files/master_recordings/' . $finalFilename . '/' . $finalFilename . '.mp4');
				$this->Translation->set('status', 'RENDERING');
				$this->Translation->save();
				$this->Session->setFlash(__('The translation is being rendered.  This may take a few minutes.'), '_flash_msg', array('msgType' => 'info'));
				$this->redirect("/translations/" . $this->Translation->id . "/clips");
			}else {
				$this->Session->setFlash(__('Unable to render the translation.'), '_flash_msg', array('msgType' => 'error'));
				$this->redirect("/translations/" . $this->Translation->id . "/clips");
			}
		}
		
		/**
		 * Remove the video, so they can re edit the clips
		 *
		 * @param integer $id Translation.id
		 * @return void
		 * @access public
		 * @author Johnathan Pulos
		 */
		public function remove_video($id = null) {
			$this->Translation->id = $id;
			if (!$this->Translation->exists()) {
				throw new NotFoundException(__('Invalid Translation'));
			}
			$translation = $this->Translation->read(null, $this->Translation->id);
			if($translation['Translation']['user_id'] != $this->Auth->user('id')) {
				throw new NotFoundException(__('You do not have permission.'));
			}
			$this->MasterRecording->id = $translation['Translation']['vts_master_recording_id'];
		 	$this->MasterRecording->translation_request_token = $translation['Translation']['token'];
		 	if($this->MasterRecording->delete()) {
				$this->Translation->set('vts_master_recording_id', '');
				$this->Translation->set('master_recording_file', '');
				$this->Translation->set('status', 'PENDING');
				$this->Translation->save();
				$this->Session->setFlash(__('The clips are now editable.'), '_flash_msg', array('msgType' => 'info'));
				$this->redirect("/translations/" . $this->Translation->id . "/clips");
			}else {
				$this->Session->setFlash(__('Unable to remove the translation video. Please try again later.'), '_flash_msg', array('msgType' => 'error'));
				$this->redirect("/translations/" . $this->Translation->id . "/clips");
			}
		}
		
		/**
		 * Publish the current video
		 *
		 * @param integer $id Translation.id
		 * @return void
		 * @access public
		 * @author Johnathan Pulos
		 */
		public function publish_video($id = null) {
			$this->Translation->id = $id;
			if (!$this->Translation->exists()) {
				throw new NotFoundException(__('Invalid Translation'));
			}
			$translation = $this->Translation->read(null, $this->Translation->id);
			if($translation['Translation']['user_id'] != $this->Auth->user('id')) {
				throw new NotFoundException(__('You do not have permission.'));
			}
			$this->Translation->set('status', 'PUBLISHED');
			$this->Translation->save();
			$this->Session->setFlash(__('The video has been published.  Thank you for your contribution.'), '_flash_msg', array('msgType' => 'info'));
			$this->redirect("/translations/" . $this->Translation->id . "/clips");
		}

/**
 * ADMINISTRATION FUNCTIONS
 *
 * @author Johnathan Pulos
 */
				
		/**
		 * The admin index action
		 *
		 * @return void
		 * @access public
		 * @author Johnathan Pulos
		 */
		public function admin_index() {
			$this->Translation->recursive = 0;
			$this->set('translations', $this->paginate());
		}
		
		/**
		 * admin_view method
		 *
		 * @param integer $id Translation.id
		 * @return void
		 */
		public function admin_view($id = null) {
			$this->Translation->id = $id;
			if (!$this->Translation->exists()) {
				throw new NotFoundException(__('Invalid translation'));
			}
			$translation = $this->Translation->read(null, $id);
			$this->set('translation', $translation);
			$this->set('uploadedClips', $this->Translation->getUploadedClipsArray($translation['TranslationClip']));
			$this->set('maxClips', count($this->Translation->TranslationClip->videoClipsNeeded['compassionateFather']));
			$this->set('videoUrl', $translation['Translation']['master_recording_file']);
		}
		
		/**
		 * admin_delete method
		 *
		 * @param integer $id Translation.id
		 * @return void
		 */
		public function admin_delete($id = null) {
			if (!$this->request->is('post')) {
				throw new MethodNotAllowedException();
			}
			$this->Translation->id = $id;
			if (!$this->Translation->exists()) {
				throw new NotFoundException(__('Invalid translation'));
			}
			$translation = $this->Translation->read('vts_translation_request_id', $id);
			$this->TranslationRequest->id = $translation['Translation']['vts_translation_request_id'];
			if ($this->TranslationRequest->delete()) {
				if ($this->Translation->delete()) {
					$this->Session->setFlash(__('The translation has been deleted.'), '_flash_msg', array('msgType' => 'info'));
					$this->redirect(array('action' => 'index'));
				}
			}
			$this->Session->setFlash(__('Unable to delete the translation.'), '_flash_msg', array('msgType' => 'error'));
			$this->redirect(array('action' => 'index'));
		}

}