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
		 * Call any necessary Components
		 *
		 * @var array
		 * @access public
		 */
		public $components = array('SpycYAML');

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
		 * The current translation
		 *
		 * @var array
		 * @access public
		 */
		public $currentTranslation = array();
		
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
			if(!in_array($this->action, array('index', 'admin_index', 'add'))) {
				/**
				 * Setup the current translation.  Id is sent in the $this->request->pass[0] since we are using CakePHP's mapping resource.
				 *
				 * @author Johnathan Pulos
				 */
				$this->Translation->id = $this->request->pass[0];
				if (!$this->Translation->exists()) {
					throw new NotFoundException(__('Invalid Translation'));
				}
				$this->currentTranslation = $this->Translation->read(null);
				$this->set('translation', $this->currentTranslation);
			}
			if(in_array($this->action, array('edit', 'delete', 'render_video', 'remove_video', 'publish_video'))) {
				/**
				 * Block users who do not have access to it
				 *
				 * @author Johnathan Pulos
				 */
				if($this->currentTranslation['Translation']['user_id'] != $this->Auth->user('id')) {
					throw new NotFoundException(__('You do not have permission.'));
				}
			}
			if(in_array($this->action, array('add'))) {
				/**
				 * Setup the videos they can translate
				 *
				 * @author Johnathan Pulos
				 */
				if(strlen(trim($this->locale)) != 3) {
					throw new NotFoundException(__("You must set the website's locale to the standard ISO 639-2 three letter code."));
				}
				$videosYmlFile = ROOT . DS . APP_DIR . DS . 'Locale' . DS . $this->locale . DS . 'VIDEOS' . DS . 'videos.yml';
				if(!file_exists($videosYmlFile)) {
					/**
					 * default to english
					 *
					 * @author Johnathan Pulos
					 */
					$videosYmlFile = ROOT . DS . APP_DIR . DS . 'Locale' . DS . 'eng' . DS . 'VIDEOS' . DS . 'videos.yml';
				}
				$videoOptionsArray = $this->SpycYAML->toArray($videosYmlFile);
				$videoOptions = array();
				foreach ($videoOptionsArray['videos'] as $key => $val) {
					$videoOptions[$val['settings_prefix']] = $val['title'];
				}
				$this->set('videoOptions', $videoOptions);
			}
		}
		
	/**
	 * index method
	 *
	 * @return void
	 */
		public function index() {
			$translationRequest = $this->TranslationRequest->read(null, 1);
			$this->set('translations', $this->Translation->find('all', array('conditions'=> array('Translation.user_id'	=>	$this->Auth->user('id')))));
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
			Configure::write('debug',0);
			$this->viewClass = 'Media';
	    $params = array(
	        'id'        => basename($this->currentTranslation['Translation']['master_recording_file']),
	        'name'      => 'obs_video',
	        'extension' => 'mp4',
					'download'  => true,
	        'mimeType'  => array('mp4' => 'video/mp4'),
	        'path'      => 'files' . DS . 'completed' . DS
	    );
	    $this->set($params);
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
			if ($this->request->is('post') || $this->request->is('put')) {
				if ($this->Translation->save($this->request->data, true, $this->Translation->attrAccessible)) {
					$this->Session->setFlash(__('The translation has been updated.'), '_flash_msg', array('msgType' => 'info'));
					$this->redirect(array('action'	=>	'index'));
				} else{
					$this->Session->setFlash(__('Unable to update the translation.'), '_flash_msg', array('msgType' => 'error'));
				}
			}else {
				$this->request->data = $this->currentTranslation;
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
			$this->TranslationRequest->id = $this->currentTranslation['Translation']['vts_translation_request_id'];
			$this->TranslationRequest->translation_request_token = $this->currentTranslation['Translation']['token'];
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
			$this->MasterRecording->create();
			$finalFilename = $id . "_translated_video";
			$data = array(	'translation_request_token' => $this->currentTranslation['Translation']['token'],
											'title' 										=> $this->currentTranslation['Translation']['title'],
											'language' 									=> $this->currentTranslation['Translation']['language'],
											'final_filename'						=> $finalFilename
									);
			if($this->MasterRecording->save($data)) {
				$this->Translation->id = $id;
				$this->Translation->set('vts_master_recording_id', $this->MasterRecording->id);
				$this->Translation->set('master_recording_file', VTS_URL . 'files/master_recordings/' . $finalFilename . '/' . $finalFilename . '.mp4');
				$this->Translation->set('status', 'RENDERING');
				$this->Translation->save();
				$this->Session->setFlash(__('The translation is being rendered.  This may take a few minutes.'), '_flash_msg', array('msgType' => 'info'));
				$this->redirect("/translations/" . $id . "/clips");
			}else {
				$errors = $this->MasterRecording->invalidFields();
				if(!empty($errors)) {
						$this->Session->setFlash($this->ppErrors($errors), '_flash_msg', array('msgType' => 'error'));
				}else {
					$this->Session->setFlash(__('Unable to render the translation.'), '_flash_msg', array('msgType' => 'error'));
				}
				$this->redirect("/translations/" . $id . "/clips");
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
			$this->MasterRecording->id = $this->currentTranslation['Translation']['vts_master_recording_id'];
		 	$this->MasterRecording->translation_request_token = $this->currentTranslation['Translation']['token'];
		 	if($this->MasterRecording->delete()) {
				$this->Translation->id = $id;
				$this->Translation->set('vts_master_recording_id', '');
				$this->Translation->set('master_recording_file', '');
				$this->Translation->set('status', 'PENDING');
				$this->Translation->save();
				$this->Session->setFlash(__('The clips are now editable.'), '_flash_msg', array('msgType' => 'info'));
				$this->redirect("/translations/" . $id . "/clips");
			}else {
				$this->Session->setFlash(__('Unable to remove the translation video. Please try again later.'), '_flash_msg', array('msgType' => 'error'));
				$this->redirect("/translations/" . $id . "/clips");
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
			if($this->Translation->downloadAndPublish($this->currentTranslation['Translation']['master_recording_file'])) {
				$this->Session->setFlash(__('The video has been published.  Thank you for your contribution.'), '_flash_msg', array('msgType' => 'info'));
				$this->redirect(array('controller'	=>	'translations', 'action'	=>	'index',	'admin'	=>	false));
			}else {
				$this->Session->setFlash(__('The video could not be published.  Please try again, or contact the webmaster.'), '_flash_msg', array('msgType' => 'error'));
				$this->redirect(array('controller'	=>	'translations', 'action'	=>	'index',	'admin'	=>	false));
			}
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
			$this->TranslationRequest->id = $this->currentTranslation['Translation']['vts_translation_request_id'];
			$this->TranslationRequest->translation_request_token = $this->currentTranslation['Translation']['token'];
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